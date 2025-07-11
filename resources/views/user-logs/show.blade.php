@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
    <h1 class="text-2xl font-bold mb-6">Actie Logboek</h1>

    <!-- Filters -->
    <div class="bg-white shadow-md rounded-lg mb-6">
        <div class="px-6 py-4 border-b">
            <h2 class="text-lg font-semibold">Filters</h2>
        </div>
        <div class="px-6 py-4">
            <form action="{{ route('user-logs.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label for="user_id" class="block text-sm font-medium text-gray-700">Medewerker</label>
                    <select name="user_id" id="user_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Alle medewerkers</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                {{ $user->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div>
                    <label for="action" class="block text-sm font-medium text-gray-700">Actie</label>
                    <select name="action" id="action" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Alle acties</option>
                        @foreach($actions as $action)
                            <option value="{{ $action }}" {{ request('action') == $action ? 'selected' : '' }}>
                                @switch($action)
                                    @case('prisoner_moved')
                                        Gevangene verplaatst
                                        @break
                                    @case('prisoner_released')
                                        Gevangene vrijgelaten
                                        @break
                                    @default
                                        {{ $action }}
                                @endswitch
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div>
                    <label for="date_from" class="block text-sm font-medium text-gray-700">Datum vanaf</label>
                    <input type="date" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500" id="date_from" name="date_from" value="{{ request('date_from') }}">
                </div>
                
                <div>
                    <label for="date_to" class="block text-sm font-medium text-gray-700">Datum tot</label>
                    <input type="date" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500" id="date_to" name="date_to" value="{{ request('date_to') }}">
                </div>
                
                <div class="col-span-1 md:col-span-4 flex justify-end items-center gap-4">
                    <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white text-sm font-semibold px-4 py-2 rounded-md">
                        Filter
                    </button>
                    <a href="{{ route('user-logs.index') }}" class="text-sm text-gray-600 hover:underline">
                        Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Logboek Tabel -->
    <div class="bg-white shadow-md rounded-lg">
        <div class="px-6 py-4 border-b">
            <h2 class="text-lg font-semibold">Logboek</h2>
        </div>
        <div class="px-6 py-4">
            <div class="overflow-x-auto">
                <table class="min-w-full table-auto">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Datum & Tijd</th>
                            <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Medewerker</th>
                            <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Actie</th>
                            <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Beschrijving</th>
                            <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Details</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($logs as $log)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-2 text-sm text-gray-800">{{ $log->log_date->format('d-m-Y H:i') }}</td>
                                <td class="px-4 py-2 text-sm text-gray-800">{{ $log->user->name }}</td>
                                <td class="px-4 py-2 text-sm text-gray-800">
                                    @switch($log->action)
                                        @case('prisoner_moved')
                                            <span class="inline-block bg-blue-500 text-white text-xs font-semibold px-2 py-1 rounded-full">Verplaatsing</span>
                                            @break
                                        @case('prisoner_released')
                                            <span class="inline-block bg-green-500 text-white text-xs font-semibold px-2 py-1 rounded-full">Vrijlating</span>
                                            @break
                                        @default
                                            <span class="inline-block bg-gray-500 text-white text-xs font-semibold px-2 py-1 rounded-full">{{ $log->action }}</span>
                                    @endswitch
                                </td>
                                <td class="px-4 py-2 text-sm text-gray-800">{{ $log->description }}</td>
                                <td class="px-4 py-2 text-sm text-gray-800">
                                    @if($log->related_model == 'Prisoner')
                                        <a href="{{ route('prisoners.show', $log->related_id) }}" class="inline-block bg-blue-500 hover:bg-blue-600 text-white text-xs font-semibold px-3 py-1 rounded-md">
                                            Bekijk gevangene
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-2 text-center text-sm text-gray-600">Geen logs gevonden</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $logs->withQueryString()->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
