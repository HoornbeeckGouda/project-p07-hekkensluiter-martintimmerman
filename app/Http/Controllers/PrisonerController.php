<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Prisoner;
use App\Models\Cell;
use App\Models\CellMovement;
use App\Models\Antecedent;
use App\Models\Interrogation;
use App\Models\PrisonerLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\UserLogController;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class PrisonerController extends Controller
{
    public function __construct()
    {
        // Vereist authenticatie voor alle methodes
        $this->middleware('auth');

        // Blokkeer 'bewaker'-rol voor create en store methodes
        $this->middleware(\App\Http\Middleware\BlockRole::class . ':bewaker,')->only(['create', 'store']);
    }

    public function index(Request $request)
    {
        // Zoek/filter op naam of BSN
        $query = Prisoner::query();

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('roepnaam', 'like', "%{$search}%")
                  ->orWhere('achternaam', 'like', "%{$search}%")
                  ->orWhere('bsn', 'like', "%{$search}%");
            });
        }

        $prisoners = $query->paginate(10);
        return view('prisoners.index', compact('prisoners'));
    }

    public function create()
    {
        // Toon formulier voor nieuwe gevangene
        Log::info('Create method called');
        $cells = Cell::all();
        return view('prisoners.create', compact('cells'));
    }

    public function store(Request $request)
    {
        // Valideer en sla een nieuwe gevangene op
        $validated = $request->validate([
            'roepnaam' => 'required|string|max:255',
            'tussenvoegsel' => 'nullable|string|max:255',
            'achternaam' => 'required|string|max:255',
            'straat' => 'required|string|max:255',
            'huisnummer' => 'required|string|max:255',
            'toevoeging' => 'nullable|string|max:255',
            'postcode' => 'required|string|max:255',
            'woonplaats' => 'required|string|max:255',
            'bsn' => 'required|string|max:255',
            'delict' => 'required|string|max:255',
            'foto' => 'required|image|max:2048',
            'geboortedatum' => 'required|date',
            'cell_id' => 'required|exists:cells,id',
            'datum_arrestatie' => 'required|date',
            'datum_in_bewaring' => 'required|date',
        ]);

        $cell = Cell::findOrFail($validated['cell_id']);
        if ($cell->isOccupied()) {
            return back()->withErrors(['cell_id' => 'Deze cel is al bezet.'])->withInput();
        }

        if ($request->hasFile('foto')) {
            $path = $request->file('foto')->store('prisoners', 'public');
            $validated['foto'] = $path;
        }

        $prisoner = Prisoner::create($validated);

        // Koppel gevangene aan gekozen cel
        $prisoner->cells()->attach($validated['cell_id'], [
            'datum_start' => now()->toDateString(),
            'tijd_start' => now()->format('H:i:s'),
        ]);

        return redirect()->route('prisoners.index')->with('success', 'Gevangene succesvol toegevoegd.');
    }

    public function show(Prisoner $prisoner)
    {
        $prisoner->load(['cells', 'antecedents', 'interrogations']);
        $currentCell = $prisoner->currentCell();
        $availableCells = Cell::whereDoesntHave('currentPrisoners')
            ->orWhere('id', $currentCell ? $currentCell->id : 0)
            ->get();
        $logs = $prisoner->logs()->orderBy('log_date', 'desc')->paginate(10);

        return view('prisoners.show', compact('prisoner', 'currentCell', 'availableCells', 'logs'));
    }

    public function edit(Prisoner $prisoner)
    {
        // Toon bewerkformulier voor gevangene
        $cells = Cell::all();
        $currentCell = $prisoner->currentCell();
        return view('prisoners.edit', compact('prisoner', 'cells', 'currentCell'));
    }

    public function update(Request $request, Prisoner $prisoner)
    {
        // Update gegevens van gevangene
        $validated = $request->validate([
            'roepnaam' => 'required|string|max:45',
            'tussenvoegsel' => 'nullable|string|max:10',
            'achternaam' => 'required|string|max:45',
            'straat' => 'nullable|string|max:150',
            'huisnummer' => 'nullable|string|max:3',
            'toevoeging' => 'nullable|string|max:10',
            'postcode' => 'nullable|string|max:6',
            'woonplaats' => 'nullable|string|max:100',
            'bsn' => 'nullable|string|max:9',
            'delict' => 'required|string|max:255',
            'foto' => 'nullable|image|max:2048',
            'geboortedatum' => 'nullable|date',
        ]);

        if ($request->hasFile('foto')) {
            if ($prisoner->foto) {
                Storage::disk('public')->delete($prisoner->foto);
            }
            $validated['foto'] = $request->file('foto')->store('prisoners', 'public');
        }

        $prisoner->update($validated);

        return redirect()->route('prisoners.show', $prisoner)->with('success', 'Gedetineerde gegevens bijgewerkt.');
    }

    public function destroy(Prisoner $prisoner)
    {
        // Verwijder gevangene als hij niet in een cel zit
        if ($prisoner->currentCell()) {
            return redirect()->route('prisoners.index')->with('error', 'Kan gedetineerde niet verwijderen omdat deze momenteel in een cel zit.');
        }

        if ($prisoner->foto) {
            Storage::disk('public')->delete($prisoner->foto);
        }

        $prisoner->delete();

        return redirect()->route('prisoners.index')->with('success', 'Gedetineerde succesvol verwijderd.');
    }

    public function move(Request $request, $id)
    {
        // Verplaats gevangene naar een andere cel
        $prisoner = Prisoner::findOrFail($id);

        // Bewakers mogen dit niet
        if (auth()->user()->hasRole('bewaker')) {
            return redirect()->route('prisoners.show', $prisoner)->with('error', 'Je hebt geen toestemming om deze actie uit te voeren.');
        }

        $validated = $request->validate([
            'to_cell_id' => 'required|exists:cells,id',
            'reden' => 'required|string',
        ]);

        $newCell = Cell::findOrFail($validated['to_cell_id']);
        if ($newCell->isOccupied()) {
            return back()->withErrors(['to_cell_id' => 'De geselecteerde cel is al bezet.'])->withInput();
        }

        $currentCell = $prisoner->currentCell();

        if ($currentCell && $currentCell->id == $newCell->id) {
            return back()->with('error', 'De gevangene zit al in deze cel.');
        }

        // Sluit huidige celperiode af
        if ($currentCell) {
            $prisoner->cells()->updateExistingPivot($currentCell->id, [
                'datum_eind' => now()->toDateString(),
                'tijd_eind' => now()->format('H:i:s'),
            ]);
        }

        // Koppel nieuwe cel
        $prisoner->cells()->attach($newCell->id, [
            'datum_start' => now()->toDateString(),
            'tijd_start' => now()->format('H:i:s'),
        ]);

        // Log de verplaatsing
        CellMovement::create([
            'prisoner_id' => $prisoner->id,
            'from_cell_id' => $currentCell?->id,
            'to_cell_id' => $newCell->id,
            'datum_start' => now()->toDateString(),
            'reden' => $validated['reden'],
        ]);

        // Sla actie op in gebruikerslog
        $fromCellInfo = $currentCell ? "{$currentCell->afdeling} {$currentCell->celnummer}" : "geen cel";
        $toCellInfo = "{$newCell->afdeling} {$newCell->celnummer}";

        UserLogController::createLog(
            auth()->id(),
            'prisoner_moved',
            "Gevangene {$prisoner->volledigeNaam()} verplaatst van {$fromCellInfo} naar {$toCellInfo}. Reden: {$validated['reden']}",
            'Prisoner',
            $prisoner->id
        );

        return redirect()->route('prisoners.show', $prisoner)->with('success', 'Gevangene succesvol verplaatst.');
    }

    public function storeAntecedent(Request $request, Prisoner $prisoner)
    {
        $validated = $request->validate([
            'delict' => 'required|string|max:255',
            'datum_delict' => 'required|date',
            'beschrijving' => 'nullable|string',
            'bewijsmateriaal' => 'nullable|file|mimes:jpg,png,mp4,pdf|max:10240', // Max 10MB
            'bewijsmateriaal_type' => 'required_with:bewijsmateriaal|string|in:photo,video,document',
        ]);

        if ($request->hasFile('bewijsmateriaal')) {
            $path = $request->file('bewijsmateriaal')->store('antecedents', 'public');
            $validated['bewijsmateriaal'] = $path;
        }

        $prisoner->antecedents()->create($validated);

        return redirect()->route('prisoners.show', $prisoner)->with('success', 'Antecedent toegevoegd.');
    }

    public function deleteAntecedent(Request $request, Antecedent $antecedent)
    {
        // Controleer of de gebruiker toestemming heeft
        if (!auth()->user()->hasPermission('prisoners.antecedents.delete') && !auth()->user()->hasRole('admin')) {
            return redirect()->route('prisoners.show', $antecedent->prisoner_id)->with('error', 'Je hebt geen toestemming om deze actie uit te voeren.');
        }

        // Verwijder bijbehorend bestand, indien aanwezig
        if ($antecedent->bewijsmateriaal) {
            Storage::disk('public')->delete($antecedent->bewijsmateriaal);
        }

        // Haal prisoner_id op voor redirect
        $prisonerId = $antecedent->prisoner_id;
        $antecedent->delete();

        // Log de verwijdering
        UserLogController::createLog(
            auth()->id(),
            'antecedent_deleted',
            "Antecedent met delict '{$antecedent->delict}' verwijderd voor gevangene ID {$prisonerId}.",
            'Antecedent',
            $antecedent->id
        );

        return redirect()->route('prisoners.show', $prisonerId)->with('success', 'Antecedent verwijderd.');
    }

    public function storeInterrogation(Request $request, Prisoner $prisoner)
    {
        $validated = $request->validate([
            'datum_tijd' => 'required|date',
            'verslag' => 'required|string',
            'bijlage' => 'nullable|file|mimes:mp3,mp4,pdf,doc,docx|max:10240', // Max 10MB
            'bijlage_type' => 'required_with:bijlage|string|in:audio,video,document',
        ]);

        if ($request->hasFile('bijlage')) {
            $path = $request->file('bijlage')->store('interrogations', 'public');
            $validated['bijlage'] = $path;
        }

        $validated['user_id'] = auth()->id();
        $prisoner->interrogations()->create($validated);

        return redirect()->route('prisoners.show', $prisoner)->with('success', 'Verhoor toegevoegd.');
    }

    public function deleteInterrogation(Request $request, Interrogation $interrogation)
    {
        // Controleer of de gebruiker toestemming heeft
        if (!auth()->user()->hasPermission('prisoners.interrogations.delete') && !auth()->user()->hasRole('admin')) {
            return redirect()->route('prisoners.show', $interrogation->prisoner_id)->with('error', 'Je hebt geen toestemming om deze actie uit te voeren.');
        }

        // Verwijder bijbehorend bestand, indien aanwezig
        if ($interrogation->bijlage) {
            Storage::disk('public')->delete($interrogation->bijlage);
        }

        // Haal prisoner_id op voor redirect
        $prisonerId = $interrogation->prisoner_id;
        $interrogation->delete();

        // Log de verwijdering
        UserLogController::createLog(
            auth()->id(),
            'interrogation_deleted',
            "Verhoor met datum '{$interrogation->datum_tijd->format('d-m-Y H:i')}' verwijderd voor gevangene ID {$prisonerId}.",
            'Interrogation',
            $interrogation->id
        );

        return redirect()->route('prisoners.show', $prisonerId)->with('success', 'Verhoor verwijderd.');
    }

    public function release(Request $request, Prisoner $prisoner)
    {
        // Laat gevangene vrij
        $validated = $request->validate([
            'reden' => 'required|string|max:255',
        ]);

        $currentCell = $prisoner->currentCell();

        // Sluit huidige celperiode af
        if ($currentCell) {
            $prisoner->cells()->updateExistingPivot($currentCell->id, [
                'datum_eind' => now()->toDateString(),
                'tijd_eind' => now()->format('H:i:s'),
            ]);
        }

        // Registreer als verplaatsing naar "geen cel"
        CellMovement::create([
            'prisoner_id' => $prisoner->id,
            'from_cell_id' => $currentCell?->id,
            'to_cell_id' => null,
            'datum_start' => now()->toDateString(),
            'reden' => $validated['reden'],
        ]);

        // Log de vrijlating
        $fromCellInfo = $currentCell ? "{$currentCell->afdeling} {$currentCell->celnummer}" : "geen cel";

        UserLogController::createLog(
            auth()->id(),
            'prisoner_released',
            "Gevangene {$prisoner->volledigeNaam()} vrijgelaten uit {$fromCellInfo}. Reden: {$validated['reden']}",
            'Prisoner',
            $prisoner->id
        );

        return redirect()->route('prisoners.show', $prisoner)->with('success', 'Gedetineerde succesvol vrijgelaten.');
    }

    public function storeLog(Request $request, Prisoner $prisoner)
    {
        $validated = $request->validate([
            'log_type' => 'required|string|max:50',
            'description' => 'required|string',
            'log_date' => 'required|date',
            'bijlage' => 'nullable|file|mimes:jpg,png,mp4,pdf|max:10240', // Max 10MB
            'bijlage_type' => 'required_with:bijlage|string|in:photo,video,document',
        ]);

        if ($request->hasFile('bijlage')) {
            $path = $request->file('bijlage')->store('logs', 'public');
            $validated['bijlage'] = $path;
        }

        $validated['user_id'] = auth()->id();
        $prisoner->logs()->create($validated);

        return redirect()->route('prisoners.show', $prisoner)->with('success', 'Log toegevoegd aan gevangene.');
    }

    public function deleteLog(Request $request, PrisonerLog $log)
    {
        // Verwijder logregel van gevangene
        $prisonerId = $log->prisoner_id;
        $log->delete();

        return redirect()->route('prisoners.show', $prisonerId)->with('success', 'Log verwijderd.');
    }
    
// Voeg deze methode toe aan je PrisonerController

public function viewFile(Request $request, $type, $filename)
{
    // Validate the type
    $validTypes = ['antecedents', 'interrogations', 'logs'];
    if (!in_array($type, $validTypes)) {
        abort(404, 'Invalid file type');
    }
    
    // Check permissions
    if (!Auth::user()->hasRole('admin') && !Auth::user()->hasPermission("prisoners.{$type}.view")) {
        abort(403, 'Unauthorized access');
    }
    
    // Use the public disk and construct the file path
    $publicDisk = Storage::disk('public');
    $filePath = "{$type}/{$filename}";
    
    // Check if the file exists on public disk
    if (!$publicDisk->exists($filePath)) {
        abort(404, 'File not found');
    }
    
    // Return the file for viewing in browser
    return $publicDisk->response($filePath);
}

public function downloadFile(Request $request, $type, $filename)
{
    // Validate the type
    $validTypes = ['antecedents', 'interrogations', 'logs'];
    if (!in_array($type, $validTypes)) {
        abort(404, 'Invalid file type');
    }
    
    // Check permissions
    if (!Auth::user()->hasRole('admin') && !Auth::user()->hasPermission("prisoners.{$type}.view")) {
        abort(403, 'Unauthorized access');
    }
    
    // Use the public disk and construct the file path
    $publicDisk = Storage::disk('public');
    $filePath = "{$type}/{$filename}";
    
    // Check if the file exists
    if (!$publicDisk->exists($filePath)) {
        abort(404, 'File not found');
    }
    
    // Return the file for download
    return $publicDisk->download($filePath);
}
}