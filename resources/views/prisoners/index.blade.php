<!-- resources/views/prisoners/index.blade.php -->

@extends('layouts.app')

@section('content')

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
    <h1 class="text-2xl font-bold mb-6">Lijst van Gevangenen</h1>

    <!-- Knop om een nieuwe gevangene aan te maken -->
    <div class="mb-4">
        <a href="{{ route('prisoners.create') }}" class="inline-block bg-green-500 hover:bg-green-600 text-white text-xs font-semibold px-4 py-2 rounded-lg">
            Nieuwe Gevangene Toevoegen
        </a>
    </div>
<!-- Zoekformulier -->
<form method="GET" action="{{ route('prisoners.index') }}" class="mb-4">
    <div class="flex flex-wrap items-center gap-2">
        <input 
            type="text" 
            name="search" 
            value="{{ request('search') }}" 
            placeholder="Zoek op naam of celnummer..." 
            class="border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
        >
        <button 
            type="submit" 
            class="bg-blue-500 hover:bg-blue-600 text-white text-sm font-semibold px-4 py-2 rounded">
            Zoeken
        </button>
        <a 
            href="{{ route('prisoners.index') }}" 
            class="text-sm text-gray-600 hover:underline ml-2">
            Reset
        </a>
    </div>
</form>

    <div class="overflow-x-auto bg-white shadow-md rounded-lg">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">ID</th>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Naam</th>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">BSN</th>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Delict</th>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Acties</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($prisoners as $prisoner)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 text-sm text-gray-800">{{ $prisoner->id }}</td>
                    <td class="px-6 py-4 text-sm text-gray-800">
                        <!-- Voeg het tussenvoegsel toe tussen roepnaam en achternaam -->
                        {{ $prisoner->roepnaam }} 
                        @if($prisoner->tussenvoegsel) 
                            {{ $prisoner->tussenvoegsel }} 
                        @endif
                        {{ $prisoner->achternaam }}
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-800">{{ $prisoner->bsn }}</td>
                    <td class="px-6 py-4 text-sm text-gray-800">{{ $prisoner->delict }}</td>
                    <td class="px-6 py-4 space-x-2">
                        <a href="{{ route('prisoners.show', $prisoner) }}" class="inline-block bg-blue-500 hover:bg-blue-600 text-white text-xs font-semibold px-3 py-1 rounded">Details</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $prisoners->links() }} <!-- Paginering -->
    </div>
</div>
@endsection
