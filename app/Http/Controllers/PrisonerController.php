<?php

namespace App\Http\Controllers;

use App\Models\Cell;
use App\Models\Prisoner;
use App\Models\CellPrisoner;
use App\Models\CellMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PrisonerController extends Controller
{
    public function index(Request $request)
    {
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
        $cells = Cell::all();
        return view('prisoners.create', compact('cells'));
    }
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'roepnaam' => 'required|string|max:45',
            'tussenvoegsel' => 'nullable|string|max:10',
            'achternaam' => 'required|string|max:45',
            'straat' => 'nullable|string|max:150',
            'huisnummer' => 'nullable|string|max:3',
            'toevoeging' => 'nullable|string|max:10',
            'postcode' => 'nullable|string|max:6',
            'woonplaats' => 'nullable|string|max:100',
            'bsn' => 'required|string|max:9', 
            'delict' => 'required|string|max:255',
            'foto' => 'required|image|max:2048',
            'geboortedatum' => 'nullable|date',
            'datum_arrestatie' => 'required|date',
            'datum_in_bewaring' => 'required|date',
            'zaaknummer' => 'required|string|max:255',
            'cell_id' => 'required|exists:cells,id',
        ]);
        
        
        // Handle photo upload
        if ($request->hasFile('foto')) {
            $photoPath = $request->file('foto')->store('prisoners', 'public');
            $validated['foto'] = $photoPath;
        }
        
        // Remove cell_id from validated data as it's not a direct attribute of Prisoner
        $cellId = $validated['cell_id'];
        unset($validated['cell_id']);
        
        // Create the prisoner
        $prisoner = Prisoner::create($validated);
        
        // Create cell assignment
        CellPrisoner::create([
            'prisoner_id' => $prisoner->id,
            'cell_id' => $cellId,
            'datum_start' => now()->format('Y-m-d'),
            'tijd_start' => now()->format('H:i:s'),
        ]);
        
        // Create movement record
        CellMovement::create([
            'prisoner_id' => $prisoner->id,
            'to_cell_id' => $cellId,
            'datum_start' => now()->format('Y-m-d'),
            'reden' => 'Initial assignment',
        ]);
        
        return redirect()->route('prisoners.index')
            ->with('success', 'Gedetineerde succesvol geregistreerd.');
    }
    
    public function show(Prisoner $prisoner)
    {
        $prisoner->load('cells');
        $currentCell = $prisoner->currentCell();
        $movements = $prisoner->movements()->with(['fromCell', 'toCell'])->get();
        
        return view('prisoners.show', compact('prisoner', 'currentCell', 'movements'));
    }
    
    public function edit(Prisoner $prisoner)
    {
        $cells = Cell::all();
        $currentCell = $prisoner->currentCell();
        
        return view('prisoners.edit', compact('prisoner', 'cells', 'currentCell'));
    }
    
    public function update(Request $request, Prisoner $prisoner)
    {
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
        
        // Handle photo upload
        if ($request->hasFile('foto')) {
            // Delete old photo if exists
            if ($prisoner->foto) {
                Storage::disk('public')->delete($prisoner->foto);
            }
            
            $photoPath = $request->file('foto')->store('prisoners', 'public');
            $validated['foto'] = $photoPath;
        }
        
        $prisoner->update($validated);
        
        return redirect()->route('prisoners.show', $prisoner)
            ->with('success', 'Gedetineerde gegevens bijgewerkt.');
    }
    
    public function destroy(Prisoner $prisoner)
    {
        // Check if prisoner can be deleted (not currently assigned to a cell)
        if ($prisoner->currentCell()) {
            return redirect()->route('prisoners.index')
                ->with('error', 'Kan gedetineerde niet verwijderen omdat deze momenteel in een cel zit.');
        }
        
        // Delete photo if exists
        if ($prisoner->foto) {
            Storage::disk('public')->delete($prisoner->foto);
        }
        
        $prisoner->delete();
        
        return redirect()->route('prisoners.index')
            ->with('success', 'Gedetineerde succesvol verwijderd.');
    }
    
    public function move(Request $request, Prisoner $prisoner)
    {
        $validated = $request->validate([
            'to_cell_id' => 'required|exists:cells,id',
            'reden' => 'required|string|max:255',
        ]);
        
        $currentCell = $prisoner->currentCell();
        
        if ($currentCell) {
            $cellPrisoner = CellPrisoner::where('prisoner_id', $prisoner->id)
                ->where('cell_id', $currentCell->id)
                ->whereNull('datum_eind')
                ->first();
                
            if ($cellPrisoner) {
                // End current cell assignment
                $cellPrisoner->update([
                    'datum_eind' => now()->format('Y-m-d'),
                    'tijd_eind' => now()->format('H:i:s'),
                ]);
            }
        }
        
        // Create new cell assignment
        CellPrisoner::create([
            'prisoner_id' => $prisoner->id,
            'cell_id' => $validated['to_cell_id'],
            'datum_start' => now()->format('Y-m-d'),
            'tijd_start' => now()->format('H:i:s'),
        ]);
        
        // Create movement record
        CellMovement::create([
            'prisoner_id' => $prisoner->id,
            'from_cell_id' => $currentCell ? $currentCell->id : null,
            'to_cell_id' => $validated['to_cell_id'],
            'datum_start' => now()->format('Y-m-d'),
            'reden' => $validated['reden'],
        ]);
        
        return redirect()->route('prisoners.show', $prisoner)
            ->with('success', 'Gedetineerde succesvol verplaatst.');
    }
    
    public function release(Request $request, Prisoner $prisoner)
    {
        $validated = $request->validate([
            'reden' => 'required|string|max:255',
        ]);
        
        $currentCell = $prisoner->currentCell();
        
        if ($currentCell) {
            $cellPrisoner = CellPrisoner::where('prisoner_id', $prisoner->id)
                ->where('cell_id', $currentCell->id)
                ->whereNull('datum_eind')
                ->first();
                
            if ($cellPrisoner) {
                // End current cell assignment
                $cellPrisoner->update([
                    'datum_eind' => now()->format('Y-m-d'),
                    'tijd_eind' => now()->format('H:i:s'),
                ]);
            }
        }
        
        // Create movement record
        CellMovement::create([
            'prisoner_id' => $prisoner->id,
            'from_cell_id' => $currentCell ? $currentCell->id : null,
            'to_cell_id' => null,
            'datum_start' => now()->format('Y-m-d'),
            'reden' => $validated['reden'],
        ]);
        
        return redirect()->route('prisoners.show', $prisoner)
            ->with('success', 'Gedetineerde succesvol vrijgelaten.');
    }
}
