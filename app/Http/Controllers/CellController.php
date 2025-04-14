<?php

namespace App\Http\Controllers;

use App\Models\Cell;
use Illuminate\Http\Request;

class CellController extends Controller
{
    public function __construct()
    {
        // Beperk bepaalde acties tot specifieke rollen
        $this->middleware('role:admin,directeur,coordinator')->only(['create', 'store', 'edit', 'update', 'destroy']);
        // Vereis authenticatie voor alle methodes
        $this->middleware('auth');
    }

    // Toon een lijst van cellen met aantal huidige gedetineerden
    public function index()
    {
        $cells = Cell::withCount(['currentPrisoners'])->paginate(15);
        return view('cells.index', compact('cells'));
    }
    
    // Toon het formulier om een nieuwe cel aan te maken
    public function create()
    {
        return view('cells.create');
    }
    
    // Sla een nieuwe cel op in de database
    public function store(Request $request)
    {
        $validated = $request->validate([
            'afdeling' => 'required|string|max:15',
            'celnummer' => 'required|string|max:4',
        ]);
        
        // Controleer of de cel al bestaat
        $exists = Cell::where('afdeling', $validated['afdeling'])
            ->where('celnummer', $validated['celnummer'])
            ->exists();
            
        if ($exists) {
            return back()->withErrors(['celnummer' => 'Deze cel bestaat al.'])->withInput();
        }
        
        Cell::create($validated);
        
        return redirect()->route('cells.index')
            ->with('success', 'Cel succesvol aangemaakt.');
    }
    
    // Toon de details van een specifieke cel, inclusief historie
    public function show(Cell $cell)
    {
        $cell->load('currentPrisoners');

        // Haal alle eerdere bewoners van deze cel op
        $history = $cell->prisoners()
            ->wherePivotNotNull('datum_eind')
            ->withPivot(['datum_start', 'datum_eind', 'tijd_start', 'tijd_eind', 'verslag_bewaker'])
            ->get();
            
        return view('cells.show', compact('cell', 'history'));
    }
    
    // Toon het formulier om een cel te bewerken
    public function edit(Cell $cell)
    {
        return view('cells.edit', compact('cell'));
    }
    
    // Werk de gegevens van een cel bij
    public function update(Request $request, Cell $cell)
    {
        $validated = $request->validate([
            'afdeling' => 'required|string|max:15',
            'celnummer' => 'required|string|max:4',
        ]);
        
        // Controleer of de nieuwe combinatie al bestaat bij een andere cel
        $exists = Cell::where('afdeling', $validated['afdeling'])
            ->where('celnummer', $validated['celnummer'])
            ->where('id', '!=', $cell->id)
            ->exists();
            
        if ($exists) {
            return back()->withErrors(['celnummer' => 'Deze cel bestaat al.'])->withInput();
        }
        
        $cell->update($validated);
        
        return redirect()->route('cells.index')
            ->with('success', 'Cel succesvol bijgewerkt.');
    }
    
    // Verwijder een cel als deze leeg is
    public function destroy(Cell $cell)
    {
        if ($cell->currentPrisoners()->count() > 0) {
            return redirect()->route('cells.index')
                ->with('error', 'Kan cel niet verwijderen omdat er gedetineerden in zitten.');
        }
        
        $cell->delete();
        
        return redirect()->route('cells.index')
            ->with('success', 'Cel succesvol verwijderd.');
    }
}
