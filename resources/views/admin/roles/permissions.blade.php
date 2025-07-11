@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Rechten beheren voor rol: {{ $role->name }}</h1>
        <a href="{{ route('admin.roles.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Terug naar rollen
        </a>
    </div>

    <!-- Role Info Card -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6 border-l-4 border-blue-500">
        <div class="flex items-center">
            <div class="rounded-full bg-blue-100 p-3 mr-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
            </div>
            <div>
                <h3 class="text-lg font-semibold text-gray-900">{{ $role->name }}</h3>
                <p class="text-gray-600">{{ $role->description }}</p>
                <p class="text-sm text-gray-500 mt-1">
                    <strong>Gebruikers met deze rol:</strong> {{ $role->users()->count() }}
                </p>
            </div>
        </div>
    </div>

    <!-- Permissions Form -->
    <form method="POST" action="{{ route('admin.roles.updatePermissions', $role) }}">
        @csrf
        @method('PUT')
        
        @foreach($permissions as $group => $groupPermissions)
        <div class="bg-white rounded-lg shadow-md p-6 mb-6 border-l-4 border-purple-500">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900 capitalize">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline mr-2 text-purple-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                    {{ $group }}
                </h3>
                <div class="space-x-2">
                    <button type="button" class="select-all-btn inline-flex items-center px-3 py-1 border border-transparent text-xs leading-4 font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Alles selecteren
                    </button>
                    <button type="button" class="deselect-all-btn inline-flex items-center px-3 py-1 border border-transparent text-xs leading-4 font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                        Alles deselecteren
                    </button>
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach($groupPermissions as $permission)
                <label class="flex items-start p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors cursor-pointer">
                    <input type="checkbox" 
                           name="permissions[]" 
                           value="{{ $permission->id }}"
                           {{ in_array($permission->id, $rolePermissions) ? 'checked' : '' }}
                           class="mt-1 mr-3 h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                    <div class="flex-1">
                        <div class="text-sm font-medium text-gray-900">{{ $permission->name }}</div>
                        <div class="text-xs text-gray-500">{{ $permission->description }}</div>
                    </div>
                </label>
                @endforeach
            </div>
        </div>
        @endforeach
        
        <!-- Action Buttons -->
        <div class="bg-white rounded-lg shadow-md p-6 border-t-4 border-green-500">
            <div class="flex items-center justify-between">
                <div class="flex space-x-4">
                    <button type="submit" class="inline-flex items-center px-6 py-3 border border-transparent text-base leading-6 font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:border-green-700 focus:ring-2 focus:ring-green-500 focus:ring-offset-2 active:bg-green-700 transition ease-in-out duration-150">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Rechten Opslaan
                    </button>
                    <a href="{{ route('admin.roles.index') }}" class="inline-flex items-center px-6 py-3 border border-gray-300 text-base leading-6 font-medium rounded-md text-gray-700 bg-white hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 active:text-gray-800 active:bg-gray-50 transition ease-in-out duration-150">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                        Annuleren
                    </a>
                </div>
            </div>
        </div>
    </form>
</div>

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add select all/deselect all functionality
    const selectAllButtons = document.querySelectorAll('.select-all-btn');
    const deselectAllButtons = document.querySelectorAll('.deselect-all-btn');
    
    selectAllButtons.forEach((button, index) => {
        button.addEventListener('click', function() {
            const container = this.closest('.bg-white');
            const checkboxes = container.querySelectorAll('input[type="checkbox"]');
            checkboxes.forEach(checkbox => checkbox.checked = true);
        });
    });
    
    deselectAllButtons.forEach((button, index) => {
        button.addEventListener('click', function() {
            const container = this.closest('.bg-white');
            const checkboxes = container.querySelectorAll('input[type="checkbox"]');
            checkboxes.forEach(checkbox => checkbox.checked = false);
        });
    });
});
</script>
@endsection
@endsection