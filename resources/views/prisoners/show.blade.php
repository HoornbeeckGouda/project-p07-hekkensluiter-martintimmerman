@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
    <h1 class="text-2xl font-bold mb-6 text-center text-gray-900">Details van Gevangene</h1>

    @if (session('success'))
    <div class="mb-4 p-4 bg-green-100 border-l-4 border-green-500 text-green-700">
        {{ session('success') }}
    </div>
    @endif

    <div class="overflow-hidden bg-white shadow-lg sm:rounded-lg">
        <div class="px-6 py-5 sm:px-6">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <!-- Gevangenis Informatie -->
                <div class="space-y-6">
                    <div class="bg-gray-50 p-4 rounded-lg shadow-sm">
                        <h3 class="text-lg font-semibold text-gray-900">Persoonlijke Informatie</h3>
                        <p class="text-sm text-gray-500">Hieronder staan de gegevens van de gevangene.</p>

                        <div class="mt-2 text-sm text-gray-800">
                            <strong>Naam:</strong> {{ $prisoner->roepnaam }} 
                            @if($prisoner->tussenvoegsel) 
                                {{ $prisoner->tussenvoegsel }} 
                            @endif
                            {{ $prisoner->achternaam }}
                        </div>
                        <div class="mt-2 text-sm text-gray-800">
                            <strong>BSN:</strong> {{ $prisoner->bsn }}
                        </div>
                        <div class="mt-2 text-sm text-gray-800">
                            <strong>Delict:</strong> {{ $prisoner->delict }}
                        </div>
                        <div class="mt-2 text-sm text-gray-800">
                            <strong>Geboortedatum:</strong> {{ $prisoner->geboortedatum->format('d-m-Y') }}
                        </div>
                        <div class="mt-2 text-sm text-gray-800">
                            <strong>Datum Arrestatie:</strong> {{ $prisoner->datum_arrestatie->format('d-m-Y') }}
                        </div>
                        <div class="mt-2 text-sm text-gray-800">
                            <strong>Datum In Bewaring:</strong> {{ $prisoner->datum_in_bewaring->format('d-m-Y') }}
                        </div>
                    </div>
                </div>

                <!-- Gevangenis Foto -->
                <div class="flex justify-center items-center bg-gray-100 rounded-lg shadow-md p-4">
                    @if($prisoner->foto)
                    <div>
                        <img src="{{ asset('storage/' . $prisoner->foto) }}" alt="Foto van {{ $prisoner->roepnaam }}" class="w-28 h-28 object-cover rounded-full border-4 border-gray-300">
                    </div>
                    @else
                    <div class="text-center text-gray-400">Geen foto beschikbaar</div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Huidige Cel Sectie -->
        <div class="px-6 py-5 bg-gray-50 shadow-sm rounded-lg mt-6">
            <h3 class="text-lg font-semibold text-gray-900">Huidige Cel</h3>
            <p class="text-sm text-gray-500">Informatie over de huidige cel van de gevangene.</p>

            <div class="mb-4 p-4 bg-white rounded-md shadow-sm border-l-4 border-blue-500">
                <h4 class="text-md font-medium text-gray-900">Locatie:</h4>
                <div class="mt-2">
                    <span class="text-sm font-semibold text-gray-600">Vleugel:</span>
                    <p class="text-sm">{{ $currentCell->afdeling ?? 'Onbekend' }}, Cel {{ $currentCell->celnummer ?? 'Onbekend' }}</p>
                </div>
                <div class="mt-2">
                    <span class="text-sm font-semibold text-gray-600">Delict:</span>
                    <p class="text-sm">{{ $prisoner->delict ?? 'Onbekend' }}</p>
                </div>
                <div class="mt-2">
                    <span class="text-sm font-semibold text-gray-600">Datum Toewijzing:</span>
                    <p class="text-sm">{{ $prisoner->datum_in_bewaring->format('d-m-Y') }}</p>
                </div>
            </div>
        </div>

        <!-- Celtoewijzing Tabel -->
        <table class="min-w-full mt-4 border-collapse">
            <thead>
                <tr>
                    <th class="px-4 py-2 border text-left">Vleugel</th>
                    <th class="px-4 py-2 border text-left">Celnummer</th>
                    <th class="px-4 py-2 border text-left">Reden Celtoewijzing</th>
                    <th class="px-4 py-2 border text-left">Datum Toewijzing</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="px-4 py-2 border">{{ $currentCell->afdeling ?? 'Onbekend' }}</td>
                    <td class="px-4 py-2 border">{{ $currentCell->celnummer ?? 'Onbekend' }}</td>
                    <td class="px-4 py-2 border">Toewijzing wegens arrestatie</td>
                    <td class="px-4 py-2 border">{{ $prisoner->datum_in_bewaring->format('d-m-Y') }}</td>
                </tr>
            </tbody>
        </table>
        
        <!-- Logs Sectie (NIEUW) -->
        <div class="px-6 py-5 bg-gray-50 shadow-sm rounded-lg mt-6">
            <h3 class="text-lg font-semibold text-gray-900">Logboek Bewaker</h3>
            <p class="text-sm text-gray-500">Logs en aantekeningen over de gevangene.</p>

            <!-- Log Toevoegen Formulier -->
            <div class="bg-white p-4 rounded-md shadow-sm mt-4">
                <h4 class="font-medium text-gray-800 mb-3">Nieuwe Log Toevoegen</h4>
                <form action="{{ route('prisoners.logs.store', $prisoner->id) }}" method="POST">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="log_type" class="block text-sm font-medium text-gray-700">Type Log</label>
                            <select id="log_type" name="log_type" class="mt-1 p-2 w-full border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                                <option value="" disabled selected>Selecteer type</option>
                                <option value="recreation">Recreatie</option>
                                <option value="visit">Bezoek</option>
                                <option value="medical">Medisch</option>
                                <option value="incident">Incident</option>
                                <option value="transport">Transport</option>
                                <option value="other">Overig</option>
                            </select>
                        </div>
                        <div>
                            <label for="log_date" class="block text-sm font-medium text-gray-700">Datum en Tijd</label>
                            <input type="datetime-local" id="log_date" name="log_date" class="mt-1 p-2 w-full border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        </div>
                    </div>
                    <div class="mt-4">
                        <label for="description" class="block text-sm font-medium text-gray-700">Beschrijving</label>
                        <textarea id="description" name="description" rows="3" class="mt-1 p-2 w-full border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required placeholder="Voer hier een gedetailleerde beschrijving in..."></textarea>
                    </div>
                    <div class="mt-4 text-right">
                        <button type="submit" class="bg-green-500 hover:bg-green-600 text-white text-sm font-semibold px-4 py-2 rounded-lg shadow-md">
                            Log Toevoegen
                        </button>
                    </div>
                </form>
            </div>

            <!-- Logs Tabel -->
            <div class="mt-6">
                @if(isset($logs) && $logs->count() > 0)
                <table class="min-w-full mt-4 border-collapse bg-white">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-4 py-2 border text-left">Datum</th>
                            <th class="px-4 py-2 border text-left">Type</th>
                            <th class="px-4 py-2 border text-left">Beschrijving</th>
                            <th class="px-4 py-2 border text-left">Bewaker</th>
                            <th class="px-4 py-2 border text-left">Acties</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($logs as $log)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-2 border">{{ $log->log_date->format('d-m-Y H:i') }}</td>
                            <td class="px-4 py-2 border">
                                @switch($log->log_type)
                                    @case('recreation')
                                        <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-md text-xs">Recreatie</span>
                                        @break
                                    @case('visit')
                                        <span class="px-2 py-1 bg-green-100 text-green-800 rounded-md text-xs">Bezoek</span>
                                        @break
                                    @case('medical')
                                        <span class="px-2 py-1 bg-red-100 text-red-800 rounded-md text-xs">Medisch</span>
                                        @break
                                    @case('incident')
                                        <span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded-md text-xs">Incident</span>
                                        @break
                                    @case('transport')
                                        <span class="px-2 py-1 bg-purple-100 text-purple-800 rounded-md text-xs">Transport</span>
                                        @break
                                    @default
                                        <span class="px-2 py-1 bg-gray-100 text-gray-800 rounded-md text-xs">Overig</span>
                                @endswitch
                            </td>
                            <td class="px-4 py-2 border">{{ $log->description }}</td>
                            <td class="px-4 py-2 border">{{ $log->user->name ?? 'Onbekend' }}</td>
                            <td class="px-4 py-2 border">
                                <form action="{{ route('prisoners.logs.delete', $log->id) }}" method="POST" onsubmit="return confirm('Weet je zeker dat je deze log wilt verwijderen?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-700">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="mt-4">
                    {{ $logs->links() }} <!-- Paginering -->
                </div>
                @else
                <div class="p-4 bg-gray-100 rounded-lg text-center text-gray-600">
                    Geen logs beschikbaar voor deze gevangene.
                </div>
                @endif
            </div>
        </div>
        
        <!-- Formulier voor het Verplaatsen van de Gevangene -->
        <div class="px-6 py-5 bg-gray-50 shadow-sm rounded-lg mt-6">
            <h3 class="text-lg font-semibold text-gray-900">Verplaats Gevangene</h3>
            <p class="text-sm text-gray-500">Gebruik dit formulier om de gevangene naar een andere cel te verplaatsen.</p>

            <form action="{{ route('prisoners.move', $prisoner->id) }}" method="POST">
                @csrf
                @method('POST')

                <!-- Reden voor Verplaatsing -->
                <div class="mb-4">
                    <label for="reden" class="block text-sm font-medium text-gray-700">Reden voor Verplaatsing</label>
                    <textarea id="reden" name="reden" rows="3" class="mt-1 p-2 w-full border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required></textarea>
                </div>

                <!-- Nieuwe Cel Selectie -->
                <div class="mb-4">
                    <label for="to_cell_id" class="block text-sm font-medium text-gray-700">Nieuwe Cel</label>
                    <select name="to_cell_id" id="to_cell_id" class="mt-1 p-2 w-full border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        <option value="" disabled selected>Selecteer een cel</option>
                        @foreach($availableCells as $cell)
                            @if(!$currentCell || $cell->id != $currentCell->id)
                                <option value="{{ $cell->id }}">{{ $cell->afdeling }}, Cel {{ $cell->celnummer }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>

                <button type="submit" class="bg-green-500 hover:bg-green-600 text-white text-sm font-semibold px-6 py-3 rounded-lg shadow-md">
                    Verplaats Gevangene naar Andere Cel
                </button>
            </form>
        </div>

        <!-- Bewegingshistorie Tabel -->
@if($prisoner->movementHistory && $prisoner->movementHistory->isNotEmpty())
<div class="px-6 py-5 bg-gray-50 shadow-sm rounded-lg mt-6">
    <h3 class="text-lg font-semibold text-gray-900">Bewegingshistorie</h3>
    <p class="text-sm text-gray-500">De onderstaande verplaatsingen en registraties zijn gemaakt voor deze gedetineerde.</p>
    <table class="min-w-full mt-4 border-collapse">
        <thead>
            <tr>
                <th class="px-4 py-2 border text-left">Datum</th>
                <th class="px-4 py-2 border text-left">Van Cel</th>
                <th class="px-4 py-2 border text-left">Naar Cel</th>
                <th class="px-4 py-2 border text-left">Reden</th>
                <th class="px-4 py-2 border text-left">Verantwoordelijke</th>
            </tr>
        </thead>
        <tbody>
            @foreach($prisoner->movementHistory->sortByDesc('datum_start') as $movement)
            <tr>
                <td class="px-4 py-2 border">{{ $movement->datum_start->format('d-m-Y') }}</td>
                <td class="px-4 py-2 border">
                    @if($movement->fromCell)
                        {{ $movement->fromCell->afdeling }}, Cel {{ $movement->fromCell->celnummer }}
                    @else
                        Geen (Eerste plaatsing)
                    @endif
                </td>
                <td class="px-4 py-2 border">
                    @if($movement->toCell)
                        {{ $movement->toCell->afdeling }}, Cel {{ $movement->toCell->celnummer }}
                    @else
                        Vrijlating
                    @endif
                </td>
                <td class="px-4 py-2 border">{{ $movement->reden }}</td>
                <td class="px-4 py-2 border">{{ $movement->bewaker->name ?? 'Onbekend' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@else
<div class="px-6 py-5 bg-gray-50 shadow-sm rounded-lg mt-6">
    <h3 class="text-lg font-semibold text-gray-900">Bewegingshistorie</h3>
    <p class="text-sm text-gray-500">Geen bewegingshistorie beschikbaar voor deze gevangene.</p>
</div>
@endif

        <div class="px-4 py-3 bg-gray-50 text-right sm:px-6 mt-6">
            <a href="{{ route('prisoners.index') }}" class="inline-block bg-blue-500 hover:bg-blue-600 text-white text-sm font-semibold px-4 py-2 rounded-lg shadow-md">
                Terug naar Lijst
            </a>
        </div>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const showReleaseFormBtn = document.getElementById('showReleaseFormBtn');
        const releaseFormModal = document.getElementById('releaseFormModal');
        const closeReleaseFormBtn = document.getElementById('closeReleaseFormBtn');
        const cancelReleaseBtn = document.getElementById('cancelReleaseBtn');
        
        if (showReleaseFormBtn) {
            showReleaseFormBtn.addEventListener('click', function() {
                releaseFormModal.classList.remove('hidden');
            });
        }
        
        if (closeReleaseFormBtn) {
            closeReleaseFormBtn.addEventListener('click', function() {
                releaseFormModal.classList.add('hidden');
            });
        }
        
        if (cancelReleaseBtn) {
            cancelReleaseBtn.addEventListener('click', function() {
                releaseFormModal.classList.add('hidden');
            });
        }
    });
</script>
@endsection