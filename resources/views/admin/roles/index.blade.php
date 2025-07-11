@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-[#6D4C41]">Rollen Beheer</h1>
        <div class="flex space-x-4">
            @if(auth()->user() && auth()->user()->hasPermission('admin.roles.manage'))
                <a href="{{ route('admin.users.index') }}" class="inline-flex items-center px-4 py-2 bg-[#6D4C41] border border-[#EFEBE9] rounded-md font-semibold text-xs text-[#EFEBE9] uppercase tracking-widest hover:bg-[#795548] active:bg-[#795548] focus:outline-none focus:border-[#EFEBE9] focus:ring ring-[#D7CCC8] disabled:opacity-25 transition ease-in-out duration-150">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    Gebruikers Beheren
                </a>
                <a href="{{ route('admin.roles.create') }}" class="inline-flex items-center px-4 py-2 bg-[#6D4C41] border border-[#EFEBE9] rounded-md font-semibold text-xs text-[#EFEBE9] uppercase tracking-widest hover:bg-[#795548] active:bg-[#795548] focus:outline-none focus:border-[#EFEBE9] focus:ring ring-[#D7CCC8] disabled:opacity-25 transition ease-in-out duration-150">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    Nieuwe Rol
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
                        <th class="px-6 py-3 text-left text-xs font-medium text-[#6D4C41] uppercase tracking-wider">Beschrijving</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-[#6D4C41] uppercase tracking-wider">Gebruikers</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-[#6D4C41] uppercase tracking-wider">Rechten</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-[#6D4C41] uppercase tracking-wider">Acties</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-[#D7CCC8]">
                    @foreach($roles as $role)
                    <tr class="hover:bg-[#EFEBE9]">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-[#6D4C41]">{{ $role->name }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-[#6D4C41]">{{ $role->description }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-[#D7CCC8] text-[#6D4C41]">
                                {{ $role->users_count }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-[#D7CCC8] text-[#6D4C41]">
                                {{ $role->permissions_count }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                            @if(auth()->user() && auth()->user()->hasPermission('admin.roles.manage'))
                                <a href="{{ route('admin.roles.show', $role) }}" class="inline-flex items-center px-3 py-1 border border-transparent text-xs leading-4 font-medium rounded-md text-[#EFEBE9] bg-[#6D4C41] hover:bg-[#795548] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#D7CCC8]">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    Bekijken
                                </a>
                                <a href="{{ route('admin.roles.permissions', $role) }}" class="inline-flex items-center px-3 py-1 border border-transparent text-xs leading-4 font-medium rounded-md text-[#EFEBE9] bg-[#6D4C41] hover:bg-[#795548] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#D7CCC8]">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                    </svg>
                                    Rechten
                                </a>
                                @if(!in_array($role->name, ['admin', 'directeur', 'coordinator', 'bewaker']))
                                    <a href="{{ route('admin.roles.edit', $role) }}" class="inline-flex items-center px-3 py-1 border border-transparent text-xs leading-4 font-medium rounded-md text-[#EFEBE9] bg-[#6D4C41] hover:bg-[#795548] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#D7CCC8]">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                        Bewerken
                                    </a>
                                @endif
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-6">
        {{ $roles->links() }}
    </div>
</div>
@endsection
