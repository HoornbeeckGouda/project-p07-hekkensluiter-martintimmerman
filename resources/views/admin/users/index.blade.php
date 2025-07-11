
@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-[#6D4C41]">Gebruikers Beheer</h1>
        <div class="flex space-x-4">
            @if(auth()->user() && auth()->user()->hasPermission('admin.roles.manage'))
                <a href="{{ route('admin.roles.index') }}" class="inline-flex items-center px-4 py-2 bg-[#6D4C41] border border-[#EFEBE9] rounded-md font-semibold text-xs text-[#EFEBE9] uppercase tracking-widest hover:bg-[#795548] active:bg-[#795548] focus:outline-none focus:border-[#EFEBE9] focus:ring ring-[#D7CCC8] disabled:opacity-25 transition ease-in-out duration-150">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7v-2a3 3 0 005.356-1.857M17 20v2M7 20v2M4 13h16M4 13a9 9 0 0118 0H2z" />
                    </svg>
                    Rollen Beheren
                </a>
            @endif
            @if(auth()->user() && auth()->user()->hasPermission('admin.users.create'))
                <a href="{{ route('admin.users.create') }}" class="inline-flex items-center px-4 py-2 bg-[#6D4C41] border border-[#EFEBE9] rounded-md font-semibold text-xs text-[#EFEBE9] uppercase tracking-widest hover:bg-[#795548] active:bg-[#795548] focus:outline-none focus:border-[#EFEBE9] focus:ring ring-[#D7CCC8] disabled:opacity-25 transition ease-in-out duration-150">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    Nieuwe Gebruiker
                </a>
            @endif
        </div>
    </div>

    @if(session('success'))
        <div class="bg-[#D7CCC8] border-l-4 border-[#6D4C41] text-[#6D4C41] p-4 rounded-lg mb-6">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-lg mb-6">
            {{ session('error') }}
        </div>
    @endif

    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-[#D7CCC8]">
                <thead class="bg-[#EFEBE9]">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-[#6D4C41] uppercase tracking-wider">Naam</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-[#6D4C41] uppercase tracking-wider">E-mail</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-[#6D4C41] uppercase tracking-wider">Rollen</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-[#6D4C41] uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-[#6D4C41] uppercase tracking-wider">Acties</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-[#D7CCC8]">
                    @foreach($users as $user)
                    <tr class="hover:bg-[#EFEBE9]">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-[#6D4C41]">{{ $user->name }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-[#6D4C41]">{{ $user->email }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-[#D7CCC8] text-[#6D4C41]">
                                {{ $user->roles->pluck('name')->implode(', ') ?: 'Geen' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $user->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $user->is_active ? 'Actief' : 'Inactief' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                            @if(auth()->user() && auth()->user()->hasPermission('admin.users.edit'))
                                <a href="{{ route('admin.users.edit', $user) }}" class="inline-flex items-center px-3 py-1 border border-transparent text-xs leading-4 font-medium rounded-md text-[#EFEBE9] bg-[#6D4C41] hover:bg-[#795548] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#D7CCC8]">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                    Bewerken
                                </a>
                            @endif
                            @if(auth()->user() && auth()->user()->hasPermission('admin.users.delete') && $user->id !== auth()->id())
                                <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline-flex" onsubmit="return confirm('Weet je zeker dat je deze gebruiker wilt verwijderen?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="inline-flex items-center px-3 py-1 border border-transparent text-xs leading-4 font-medium rounded-md text-[#EFEBE9] bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5-4h4" />
                                        </svg>
                                        Verwijderen
                                    </button>
                                </form>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-6">
        {{ $users->links() }}
    </div>
</div>
@endsection
