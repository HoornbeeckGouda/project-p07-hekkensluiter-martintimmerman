<?php

namespace App\Http\Controllers;

use App\Models\Cell;
use Illuminate\Http\Request;

class CellController extends Controller
{
    public function __construct()
    {
        // Beperk toegang tot bepaalde acties op basis van rollen
        $this->middleware('role:admin,directeur,coordinator')->only(['create', 'store', 'edit', 'update', 'destroy']);
        $this->middleware('auth');
    }

    public function index()
    {
        $cells = Cell::withCount(['currentPrisoners'])->paginate(15);
        return view('cells.index', compact('cells'));
    }
    
    public function create()
    {
        return view('cells.create');
    }
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'afdeling' => 'required|string|max:15',
            'celnummer' => 'required|string|max:4',
        ]);
        
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
    
    public function show(Cell $cell)
    {
        $cell->load('currentPrisoners');
        $history = $cell->prisoners()
            ->wherePivotNotNull('datum_eind')
            ->withPivot(['datum_start', 'datum_eind', 'tijd_start', 'tijd_eind', 'verslag_bewaker'])
            ->get();
            
        return view('cells.show', compact('cell', 'history'));
    }
    
    public function edit(Cell $cell)
    {
        return view('cells.edit', compact('cell'));
    }
    
    public function update(Request $request, Cell $cell)
    {
        $validated = $request->validate([
            'afdeling' => 'required|string|max:15',
            'celnummer' => 'required|string|max:4',
        ]);
        
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
