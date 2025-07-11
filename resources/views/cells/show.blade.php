@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Cel Details: {{ $cell->afdeling }} - {{ $cell->celnummer }}</h1>
        <a href="{{ route('cells.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-semibold px-4 py-2 rounded">Terug naar lijst</a>
    </div>

    <!-- Cell Information Card -->
    <div class="bg-white shadow-md rounded-lg p-6 mb-6">
        <h2 class="text-xl font-semibold mb-4">Cel Informatie</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <p class="text-gray-600">ID:</p>
                <p class="font-medium">{{ $cell->id }}</p>
            </div>
            <div>
                <p class="text-gray-600">Afdeling:</p>
                <p class="font-medium">{{ $cell->afdeling }}</p>
            </div>
            <div>
                <p class="text-gray-600">Celnummer:</p>
                <p class="font-medium">{{ $cell->celnummer }}</p>
            </div>
            <div>
                <p class="text-gray-600">Status:</p>
                <p class="font-medium">
                    @if($cell->currentPrisoners->count() > 0)
                        <span class="inline-block bg-red-100 text-red-800 px-3 py-1 rounded-full">Bezet</span>
                    @else
                        <span class="inline-block bg-green-100 text-green-800 px-3 py-1 rounded-full">Beschikbaar</span>
                    @endif
                </p>
            </div>
            <div>
                <p class="text-gray-600">Aangemaakt op:</p>
                <p class="font-medium">{{ $cell->created_at ? date('d-m-Y H:i', strtotime($cell->created_at)) : 'N/A' }}</p>
            </div>
            <div>
                <p class="text-gray-600">Laatst bijgewerkt:</p>
                <p class="font-medium">{{ $cell->updated_at ? date('d-m-Y H:i', strtotime($cell->updated_at)) : 'N/A' }}</p>
            </div>
        </div>
    </div>

    <!-- Current Occupants Section -->
    <div class="bg-white shadow-md rounded-lg p-6 mb-6">
        <h2 class="text-xl font-semibold mb-4">Huidige Gedetineerden</h2>
        @if($cell->currentPrisoners->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">ID</th>
                            <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Naam</th>
                            <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Datum Start</th>
                            <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Tijd Start</th>
                            <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Verslag Bewaker</th>
                            <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Acties</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($cell->currentPrisoners as $prisoner)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 text-sm text-gray-800">{{ $prisoner->id }}</td>
                            <td class="px-6 py-4 text-sm text-gray-800">
                                {{ $prisoner->roepnaam ?? '' }} 
                                {{ $prisoner->tussenvoegsel ?? '' }} 
                                {{ $prisoner->achternaam }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-800">{{ date('d-m-Y', strtotime($prisoner->pivot->datum_start)) }}</td>
                            <td class="px-6 py-4 text-sm text-gray-800">{{ date('H:i', strtotime($prisoner->pivot->tijd_start)) }}</td>
                            <td class="px-6 py-4 text-sm text-gray-800">
                                @if($prisoner->pivot->verslag_bewaker)
                                    <span class="text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded">Verslag aanwezig</span>
                                @else
                                    <span class="text-xs">Geen verslag</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 space-x-2">
                                <a href="{{ route('prisoners.show', $prisoner) }}" class="inline-block bg-blue-500 hover:bg-blue-600 text-white text-xs font-semibold px-3 py-1 rounded">Details</a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-gray-500 italic">Deze cel is momenteel niet bezet.</p>
        @endif
    </div>

    <!-- Cell History Section -->
    <div class="bg-white shadow-md rounded-lg p-6">
        <h2 class="text-xl font-semibold mb-4">Celgeschiedenis</h2>
        @if($history->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">ID</th>
                            <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Naam</th>
                            <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Periode</th>
                            <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Tijdsperiode</th>
                            <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Verslag Bewaker</th>
                            <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Acties</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($history as $prisoner)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 text-sm text-gray-800">{{ $prisoner->id }}</td>
                            <td class="px-6 py-4 text-sm text-gray-800">
                                {{ $prisoner->roepnaam ?? '' }} 
                                {{ $prisoner->tussenvoegsel ?? '' }} 
                                {{ $prisoner->achternaam }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-800">
                                {{ date('d-m-Y', strtotime($prisoner->pivot->datum_start)) }} tot 
                                {{ $prisoner->pivot->datum_eind ? date('d-m-Y', strtotime($prisoner->pivot->datum_eind)) : 'N/A' }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-800">
                                {{ date('H:i', strtotime($prisoner->pivot->tijd_start)) }} tot 
                                {{ $prisoner->pivot->tijd_eind ? date('H:i', strtotime($prisoner->pivot->tijd_eind)) : 'N/A' }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-800">
                                @if($prisoner->pivot->verslag_bewaker)
                                    <button class="text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded hover:bg-blue-200 show-report" 
                                            data-report="{{ $prisoner->pivot->verslag_bewaker }}">
                                        Toon verslag
                                    </button>
                                @else
                                    <span class="text-xs">Geen verslag</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 space-x-2">
                                <a href="{{ route('prisoners.show', $prisoner) }}" class="inline-block bg-blue-500 hover:bg-blue-600 text-white text-xs font-semibold px-3 py-1 rounded">Details</a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-gray-500 italic">Geen historische gegevens beschikbaar voor deze cel.</p>
        @endif
    </div>
</div>

<!-- Report Modal -->
<div id="reportModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-lg p-6 max-w-2xl w-full max-h-[80vh] overflow-y-auto">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-semibold">Verslag Bewaker</h3>
            <button id="closeReportModal" class="text-gray-500 hover:text-gray-700">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <div id="reportContent" class="text-gray-800 whitespace-pre-wrap"></div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Show report modal
        const reportButtons = document.querySelectorAll('.show-report');
        const reportModal = document.getElementById('reportModal');
        const reportContent = document.getElementById('reportContent');
        const closeReportModal = document.getElementById('closeReportModal');
        
        reportButtons.forEach(button => {
            button.addEventListener('click', function() {
                const report = this.getAttribute('data-report');
                reportContent.textContent = report;
                reportModal.classList.remove('hidden');
            });
        });
        
        // Close modal
        closeReportModal.addEventListener('click', function() {
            reportModal.classList.add('hidden');
        });
        
        // Close modal when clicking outside
        reportModal.addEventListener('click', function(e) {
            if (e.target === reportModal) {
                reportModal.classList.add('hidden');
            }
        });
    });
</script>
@endsection