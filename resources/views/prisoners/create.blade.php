<!-- resources/views/prisoners/create.blade.php -->

@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
    <h1 class="text-2xl font-bold mb-6">Nieuwe Gevangene Toevoegen</h1>

    <!-- Formulier voor het toevoegen van een gevangene -->
    <div class="overflow-hidden bg-white shadow sm:rounded-lg">
        <form action="{{ route('prisoners.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="px-4 py-5 sm:px-6">
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                    <!-- Roepnaam -->
                    <div class="sm:col-span-1">
                        <label for="roepnaam" class="block text-sm font-medium text-gray-700">Roepnaam</label>
                        <input type="text" name="roepnaam" id="roepnaam" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                    </div>

                    <!-- Tussenvoegsel -->
                    <div class="sm:col-span-1">
                        <label for="tussenvoegsel" class="block text-sm font-medium text-gray-700">Tussenvoegsel</label>
                        <input type="text" name="tussenvoegsel" id="tussenvoegsel" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    </div>

                    <!-- Achternaam -->
                    <div class="sm:col-span-1">
                        <label for="achternaam" class="block text-sm font-medium text-gray-700">Achternaam</label>
                        <input type="text" name="achternaam" id="achternaam" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                    </div>
                </div>
                
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                    <!-- BSN -->
                    <div class="sm:col-span-1">
                        <label for="bsn" class="block text-sm font-medium text-gray-700">BSN</label>
                        <input type="text" name="bsn" id="bsn" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    </div>

                    <!-- Delict -->
                    <div class="sm:col-span-1">
                        <label for="delict" class="block text-sm font-medium text-gray-700">Delict</label>
                        <input type="text" name="delict" id="delict" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                    <!-- Geboortedatum -->
                    <div class="sm:col-span-1">
                        <label for="geboortedatum" class="block text-sm font-medium text-gray-700">Geboortedatum</label>
                        <input type="date" name="geboortedatum" id="geboortedatum" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    </div>

                    <!-- Foto -->
                    <div class="sm:col-span-1">
                        <label for="foto" class="block text-sm font-medium text-gray-700">Foto</label>
                        <input type="file" name="foto" id="foto" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    </div>
                </div>

                <!-- Afdeling/Cell -->
                <div class="mt-6">
                    <label for="cell_id" class="block text-sm font-medium text-gray-700">Kies een cel</label>
                    <select id="cell_id" name="cell_id" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        @foreach($cells as $cell)
                            <option value="{{ $cell->id }}">{{ $cell->celnummer }} - {{ $cell->afdeling }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="px-4 py-3 bg-gray-50 text-right sm:px-6">
                <button type="submit" class="inline-block bg-blue-500 hover:bg-blue-600 text-white text-sm font-semibold px-4 py-2 rounded-lg">
                    Gevangene Toevoegen
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
