<?php

namespace App\Http\Controllers;

use App\Models\Cell;
use App\Models\Prisoner;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    // Verwijder de constructor of middleware-aanroep hier.
    // Als er geen middleware nodig is, kun je deze regel gewoon weglaten.

    public function index()
{
    $totalPrisoners = Prisoner::count();

    // Aangepaste query voor actieve gevangenen
    $activePrisoners = Prisoner::whereHas('cells', function($query) {
        $query->whereNull('cell_prisoners.datum_eind');
    })->count();
    
    // Aangepaste query voor beschikbare cellen
    $availableCells = Cell::whereDoesntHave('prisoners', function($query) {
        $query->whereNull('cell_prisoners.datum_eind');
    })->count();
    
    $totalCells = Cell::count();
    
    $recentPrisoners = Prisoner::latest()->take(5)->get();
    
    return view('dashboard', compact(
        'totalPrisoners', 
        'activePrisoners', 
        'availableCells', 
        'totalCells', 
        'recentPrisoners'
    ));
}

}
