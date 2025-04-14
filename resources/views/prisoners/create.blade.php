<!-- resources/views/prisoners/create.blade.php -->

@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
    <h1 class="text-3xl font-bold mb-8 text-center">Nieuwe Gevangene Toevoegen</h1>

    <div class="overflow-hidden bg-white shadow sm:rounded-lg p-8">
        <form action="{{ route('prisoners.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="space-y-8">
                <!-- Persoonlijke Gegevens -->
                <div class="bg-gray-50 p-6 rounded-lg shadow-md">
                    <h3 class="text-xl font-semibold mb-4">Persoonlijke Gegevens</h3>
                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        <!-- Roepnaam -->
                        <div class="sm:col-span-1">
                            <label for="roepnaam" class="block text-sm font-medium text-gray-700">Roepnaam</label>
                            <input type="text" name="roepnaam" id="roepnaam" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" value="{{ old('roepnaam') }}" required>
                            @error('roepnaam')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Tussenvoegsel -->
                        <div class="sm:col-span-1">
                            <label for="tussenvoegsel" class="block text-sm font-medium text-gray-700">Tussenvoegsel</label>
                            <input type="text" name="tussenvoegsel" id="tussenvoegsel" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" value="{{ old('tussenvoegsel') }}">
                        </div>

                        <!-- Achternaam -->
                        <div class="sm:col-span-1">
                            <label for="achternaam" class="block text-sm font-medium text-gray-700">Achternaam</label>
                            <input type="text" name="achternaam" id="achternaam" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" value="{{ old('achternaam') }}" required>
                            @error('achternaam')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Overige Gegevens -->
                <div class="bg-gray-50 p-6 rounded-lg shadow-md">
                    <h3 class="text-xl font-semibold mb-4">Overige Gegevens</h3>
                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        <!-- BSN -->
                        <div class="sm:col-span-1">
                            <label for="bsn" class="block text-sm font-medium text-gray-700">BSN</label>
                            <input type="text" name="bsn" id="bsn" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" value="{{ old('bsn') }}" required>
                            @error('bsn')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Delict -->
                        <div class="sm:col-span-1">
                            <label for="delict" class="block text-sm font-medium text-gray-700">Delict</label>
                            <input type="text" name="delict" id="delict" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" value="{{ old('delict') }}" required>
                            @error('delict')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        <!-- Geboortedatum -->
                        <div class="sm:col-span-1">
                            <label for="geboortedatum" class="block text-sm font-medium text-gray-700">Geboortedatum</label>
                            <input type="date" name="geboortedatum" id="geboortedatum" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" value="{{ old('geboortedatum') }}" required>
                        </div>

                        <!-- Foto -->
                        <div class="sm:col-span-1">
                            <label for="foto" class="block text-sm font-medium text-gray-700">Foto</label>
                            <input type="file" name="foto" id="foto" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                            @error('foto')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Datum Arrestatie -->
                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 mt-6">
                        <div class="sm:col-span-1">
                            <label for="datum_arrestatie" class="block text-sm font-medium text-gray-700">Datum Arrestatie</label>
                            <input type="date" name="datum_arrestatie" id="datum_arrestatie" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" value="{{ old('datum_arrestatie') }}" required>
                        </div>

                        <!-- Datum in Bewaring -->
                        <div class="sm:col-span-1">
                            <label for="datum_in_bewaring" class="block text-sm font-medium text-gray-700">Datum in Bewaring</label>
                            <input type="date" name="datum_in_bewaring" id="datum_in_bewaring" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" value="{{ old('datum_in_bewaring') }}" required>
                        </div>
                    </div>
                </div>

                <!-- Adres Gegevens -->
                <div class="bg-gray-50 p-6 rounded-lg shadow-md">
                    <h3 class="text-xl font-semibold mb-4">Adres Gegevens</h3>
                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-3">
                        <div class="sm:col-span-1">
                            <label for="straat" class="block text-sm font-medium text-gray-700">Straat</label>
                            <input type="text" name="straat" id="straat" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" value="{{ old('straat') }}" required>
                        </div>
                        <div class="sm:col-span-1">
                            <label for="huisnummer" class="block text-sm font-medium text-gray-700">Huisnummer</label>
                            <input type="text" name="huisnummer" id="huisnummer" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" value="{{ old('huisnummer') }}" required>
                        </div>
                        <div class="sm:col-span-1">
                            <label for="toevoeging" class="block text-sm font-medium text-gray-700">Toevoeging</label>
                            <input type="text" name="toevoeging" id="toevoeging" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" value="{{ old('toevoeging') }}">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 mt-6">
                        <div class="sm:col-span-1">
                            <label for="postcode" class="block text-sm font-medium text-gray-700">Postcode</label>
                            <input type="text" name="postcode" id="postcode" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" value="{{ old('postcode') }}" required>
                        </div>
                        <div class="sm:col-span-1">
                            <label for="woonplaats" class="block text-sm font-medium text-gray-700">Woonplaats</label>
                            <input type="text" name="woonplaats" id="woonplaats" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" value="{{ old('woonplaats') }}" required>
                        </div>
                    </div>
                </div>

                <!-- Cel Gegevens -->
                <div class="bg-gray-50 p-6 rounded-lg shadow-md">
                    <h3 class="text-xl font-semibold mb-4">Cel Gegevens</h3>
                    <div class="mt-6">
                        <label for="cell_id" class="block text-sm font-medium text-gray-700">Kies een cel</label>
                        <select id="cell_id" name="cell_id" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                            @foreach($cells as $cell)
                                <option value="{{ $cell->id }}" {{ old('cell_id') == $cell->id ? 'selected' : '' }}>{{ $cell->celnummer }} - {{ $cell->afdeling }}</option>
                            @endforeach
                        </select>
                        @error('cell_id')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                        
                    </div>
                </div>
            </div>
            <div class="px-4 py-3 bg-gray-50 text-right sm:px-6">
                <button type="submit" class="inline-block bg-blue-500 hover:bg-blue-600 text-white text-sm font-semibold px-6 py-3 rounded-lg">
                    Gevangene Toevoegen
                </button>
                
            </div>
        </form>
    </div>
    
</div>
@endsection
