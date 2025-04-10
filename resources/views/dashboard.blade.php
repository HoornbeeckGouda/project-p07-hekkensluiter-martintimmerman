@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
    <h1 class="text-2xl font-bold mb-6">Dashboard</h1>
    
    <!-- Stats Overview -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Prisoners -->
        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-blue-500">
            <div class="flex items-center">
                <div class="rounded-full bg-blue-100 p-3 mr-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                </div>
                <div>
                    <h2 class="text-sm text-gray-600 uppercase font-semibold">Totaal Gedetineerden</h2>
                    <p class="text-2xl font-bold">{{ $totalPrisoners }}</p>
                </div>
            </div>
        </div>
        
        <!-- Available Cells -->
        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-green-500">
            <div class="flex items-center">
                <div class="rounded-full bg-green-100 p-3 mr-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                </div>
                <div>
                    <h2 class="text-sm text-gray-600 uppercase font-semibold">Beschikbare Cellen</h2>
                    <p class="text-2xl font-bold">{{ $availableCells }}</p>
                </div>
            </div>
        </div>
        
        <!-- Occupied Cells -->
        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-red-500">
            <div class="flex items-center">
                <div class="rounded-full bg-red-100 p-3 mr-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                </div>
                <div>
                    <h2 class="text-sm text-gray-600 uppercase font-semibold">Bezette Cellen</h2>
                    <p class="text-2xl font-bold">{{ $occupiedCells }}</p>
                </div>
            </div>
        </div>
        
        <!-- Recent Movements -->
        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-purple-500">
            <div class="flex items-center">
                <div class="rounded-full bg-purple-100 p-3 mr-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-purple-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                    </svg>
                </div>
                <div>
                    <h2 class="text-sm text-gray-600 uppercase font-semibold">Verplaatsingen (7d)</h2>
                    <p class="text-2xl font-bold">{{ $recentMovements }}</p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Section Row -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Occupancy by Section -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-lg font-semibold mb-4">Bezetting per Afdeling</h2>
            <div class="overflow-hidden">
                <canvas id="occupancyChart" width="400" height="200"></canvas>
            </div>
        </div>
        
        <!-- Recent Cell Movements -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-lg font-semibold mb-4">Recente Celverplaatsingen</h2>
            @if ($cellMovements->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-700 uppercase">Gedetineerde</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-700 uppercase">Van</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-700 uppercase">Naar</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-700 uppercase">Datum</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($cellMovements as $movement)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-2 text-sm text-gray-800">
                                        {{ $movement->prisoner->roepnaam ?? '' }} {{ $movement->prisoner->tussenvoegsel ?? '' }} {{ $movement->prisoner->achternaam }}
                                    </td>
                                    <td class="px-4 py-2 text-sm text-gray-800">
                                        @if ($movement->fromCell)
                                            {{ $movement->fromCell->afdeling }} - {{ $movement->fromCell->celnummer }}
                                        @else
                                            <span class="text-gray-500">Nieuwe opname</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-2 text-sm text-gray-800">
                                        @if ($movement->toCell)
                                            {{ $movement->toCell->afdeling }} - {{ $movement->toCell->celnummer }}
                                        @else
                                            <span class="text-gray-500">Vrijlating</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-2 text-sm text-gray-800">
                                        {{ date('d-m-Y', strtotime($movement->datum_start)) }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-gray-500 italic">Geen recente verplaatsingen</p>
            @endif
        </div>
    </div>
    
    <!-- Bottom Row -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Quick Actions -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-lg font-semibold mb-4">Snelle Acties</h2>
            <div class="space-y-2">
                <a href="{{ route('prisoners.create') }}" class="flex items-center p-3 rounded-lg hover:bg-blue-50 transition-colors">
                    <div class="rounded-full bg-blue-100 p-2 mr-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                        </svg>
                    </div>
                    <span class="text-gray-700">Nieuwe Gedetineerde</span>
                </a>
                
                <a href="{{ route('cells.create') }}" class="flex items-center p-3 rounded-lg hover:bg-blue-50 transition-colors">
                    <div class="rounded-full bg-blue-100 p-2 mr-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 14v6m-3-3h6M6 10h2a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v2a2 2 0 002 2zm10 0h2a2 2 0 002-2V6a2 2 0 00-2-2h-2a2 2 0 00-2 2v2a2 2 0 002 2zM6 20h2a2 2 0 002-2v-2a2 2 0 00-2-2H6a2 2 0 00-2 2v2a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <span class="text-gray-700">Nieuwe Cel</span>
                </a>
                
                <a href="{{ route('cells.index') }}" class="flex items-center p-3 rounded-lg hover:bg-blue-50 transition-colors">
                    <div class="rounded-full bg-blue-100 p-2 mr-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                    </div>
                    <span class="text-gray-700">Cellenoverzicht</span>
                </a>
                
                <a href="{{ route('prisoners.index') }}" class="flex items-center p-3 rounded-lg hover:bg-blue-50 transition-colors">
                    <div class="rounded-full bg-blue-100 p-2 mr-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                    <span class="text-gray-700">Gedetineerdenoverzicht</span>
                </a>
            </div>
        </div>
        
        <!-- Cells with space -->
        <div class="bg-white rounded-lg shadow-md p-6 col-span-1 lg:col-span-2">
            <h2 class="text-lg font-semibold mb-4">Beschikbare Cellen per Afdeling</h2>
            @if ($availableCellsBySection->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-700 uppercase">Afdeling</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-700 uppercase">Celnummer</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-700 uppercase">Status</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-700 uppercase">Acties</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($availableCellsBySection as $cell)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-2 text-sm text-gray-800">{{ $cell->afdeling }}</td>
                                    <td class="px-4 py-2 text-sm text-gray-800">{{ $cell->celnummer }}</td>
                                    <td class="px-4 py-2 text-sm text-gray-800">
                                        <span class="inline-block bg-green-100 text-green-800 text-xs px-2 py-1 rounded-full">Beschikbaar</span>
                                    </td>
                                    <td class="px-4 py-2 text-sm space-x-2">
                                        <a href="{{ route('cells.show', $cell) }}" class="inline-block bg-blue-500 hover:bg-blue-600 text-white text-xs font-semibold px-3 py-1 rounded">Details</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-gray-500 italic">Geen beschikbare cellen</p>
            @endif
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Chart for occupancy by section
        const ctx = document.getElementById('occupancyChart').getContext('2d');
        
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($sectionLabels) !!},
                datasets: [
                    {
                        label: 'Bezet',
                        data: {!! json_encode($sectionOccupied) !!},
                        backgroundColor: 'rgba(239, 68, 68, 0.7)',
                        borderColor: 'rgb(239, 68, 68)',
                        borderWidth: 1
                    },
                    {
                        label: 'Beschikbaar',
                        data: {!! json_encode($sectionAvailable) !!},
                        backgroundColor: 'rgba(16, 185, 129, 0.7)',
                        borderColor: 'rgb(16, 185, 129)',
                        borderWidth: 1
                    }
                ]
            },
            options: {
                responsive: true,
                scales: {
                    x: {
                        stacked: true,
                    },
                    y: {
                        stacked: true,
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                }
            }
        });
    });
</script>
@endsection