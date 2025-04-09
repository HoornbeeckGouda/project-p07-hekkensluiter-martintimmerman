@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
    <h1 class="text-2xl font-bold mb-6 text-center text-gray-900">Details van Gevangene</h1>

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
                            <strong>Geboortedatum:</strong> {{ $prisoner->geboortedatum }}
                        </div>
                        <div class="mt-2 text-sm text-gray-800">
                            <strong>Datum Arrestatie:</strong> {{ $prisoner->datum_arrestatie }}
                        </div>
                        <div class="mt-2 text-sm text-gray-800">
                            <strong>Datum In Bewaring:</strong> {{ $prisoner->datum_in_bewaring }}
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

        <!-- Celtoewijzing Tabel -->
        <div class="px-6 py-5 bg-gray-50 shadow-sm rounded-lg mt-6">
            <h3 class="text-lg font-semibold text-gray-900">Celtoewijzing</h3>
            <p class="text-sm text-gray-500">Celtoewijzing en gerelateerde informatie.</p>
            <table class="min-w-full mt-4 border-collapse">
                <thead>
                    <tr>
                        <th class="px-4 py-2 border text-left">Vleugel</th>
                        <th class="px-4 py-2 border text-left">Celnummer</th>
                        <th class="px-4 py-2 border text-left">Reden Celtoewijzing</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="px-4 py-2 border">{{ $prisoner->cel_vleugel }}</td>
                        <td class="px-4 py-2 border">{{ $prisoner->cel_nummer }}</td>
                        <td class="px-4 py-2 border">{{ $prisoner->cel_toewijzing_reden }}</td>
                    </tr>
                </tbody>
            </table>
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
                        <th class="px-4 py-2 border text-left">Reden</th>
                        <th class="px-4 py-2 border text-left">Verantwoordelijke Bewaker</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($prisoner->movementHistory as $movement)
                    <tr>
                        <td class="px-4 py-2 border">{{ $movement->datum }}</td>
                        <td class="px-4 py-2 border">{{ $movement->reden }}</td>
                        <td class="px-4 py-2 border">{{ $movement->bewaker->naam }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="px-6 py-5 bg-gray-50 shadow-sm rounded-lg mt-6">
            <p class="text-sm text-gray-500">Geen bewegingshistorie beschikbaar voor deze gevangene.</p>
        </div>
        @endif

        <!-- Reden van Vertrek/Verplaatsing Tabel -->
        <div class="px-6 py-5 bg-gray-50 shadow-sm rounded-lg mt-6">
            <h3 class="text-lg font-semibold text-gray-900">Reden van Vertrek/Verplaatsing</h3>
            <p class="text-sm text-gray-500">Reden waarom de gevangene is vertrokken of verplaatst uit een cel of instelling.</p>
            <table class="min-w-full mt-4 border-collapse">
                <thead>
                    <tr>
                        <th class="px-4 py-2 border text-left">Reden</th>
                        <th class="px-4 py-2 border text-left">Datum</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="px-4 py-2 border">{{ $prisoner->reden_vertrek_verplaatsing }}</td>
                        <td class="px-4 py-2 border">{{ $prisoner->datum_vertrek_verplaatsing }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="px-4 py-3 bg-gray-50 text-right sm:px-6 mt-6">
            <a href="{{ route('prisoners.index') }}" class="inline-block bg-blue-500 hover:bg-blue-600 text-white text-sm font-semibold px-4 py-2 rounded-lg shadow-md">
                Terug naar Lijst
            </a>
        </div>
    </div>
</div>
@endsection
