<?php

namespace App\Http\Controllers;

use App\Models\Cell;
use App\Models\CellMovement;
use App\Models\Prisoner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Basisstatistieken
        $totalPrisoners = Prisoner::count();
        
        // Celstatistieken
        $totalCells = Cell::count();
        $occupiedCells = Cell::whereHas('currentPrisoners')->count();
        $availableCells = $totalCells - $occupiedCells;
        
        // Recente celverplaatsingen (laatste 7 dagen)
        $recentMovements = CellMovement::where('datum_start', '>=', Carbon::now()->subDays(7))->count();
        $cellMovements = CellMovement::with(['prisoner', 'fromCell', 'toCell'])
            ->orderBy('datum_start', 'desc')
            ->limit(5)
            ->get();
        
        // Beschikbare cellen per afdeling
        $availableCellsBySection = Cell::whereDoesntHave('currentPrisoners')
            ->orderBy('afdeling')
            ->orderBy('celnummer')
            ->limit(10)
            ->get();
        
        // Data voor bezetting per afdeling grafiek
        $sectionData = Cell::select('afdeling', 
                DB::raw('COUNT(*) as total'),
                DB::raw('SUM(CASE WHEN EXISTS (
                    SELECT 1 FROM cell_prisoners 
                    WHERE cell_prisoners.cell_id = cells.id 
                    AND cell_prisoners.datum_eind IS NULL
                ) THEN 1 ELSE 0 END) as occupied')
            )
            ->groupBy('afdeling')
            ->get();
        
        $sectionLabels = $sectionData->pluck('afdeling')->toArray();
        $sectionOccupied = $sectionData->pluck('occupied')->toArray();
        $sectionAvailable = $sectionData->map(function ($item) {
            return $item->total - $item->occupied;
        })->toArray();
        
        return view('dashboard', compact(
            'totalPrisoners',
            'availableCells',
            'occupiedCells',
            'recentMovements',
            'cellMovements',
            'availableCellsBySection',
            'sectionLabels',
            'sectionOccupied',
            'sectionAvailable'
        ));
    }
}