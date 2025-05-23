@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
    <h1 class="text-2xl font-bold mb-6">Cellenlijst</h1>

    <div class="overflow-x-auto bg-white shadow-md rounded-lg">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">#</th>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Afdeling</th>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Celnummer</th>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Status</th>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Acties</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($cells as $cell)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 text-sm text-gray-800">{{ $cell->id }}</td>
                    <td class="px-6 py-4 text-sm text-gray-800">{{ $cell->afdeling }}</td>
                    <td class="px-6 py-4 text-sm text-gray-800">{{ $cell->celnummer }}</td>
                    <td class="px-6 py-4 text-sm">
    @if($cell->current_prisoners_count > 0)
        <span class="inline-block bg-red-100 text-red-800 text-xs px-3 py-1 rounded-full font-semibold">Bezet</span>
    @else
        <span class="inline-block bg-green-100 text-green-800 text-xs px-3 py-1 rounded-full font-semibold">Beschikbaar</span>
    @endif
</td>
                    <td class="px-6 py-4 space-x-2">
                        <a href="{{ route('cells.show', $cell) }}" class="inline-block bg-blue-500 hover:bg-blue-600 text-white text-xs font-semibold px-3 py-1 rounded">Details</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $cells->links() }}
    </div>
</div>
@endsection
