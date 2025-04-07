<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Dashboard') }}
            </h2>

            <div class="flex items-center space-x-4">
                <!-- Account info en uitlogknop rechtsboven -->
                <span class="text-sm text-gray-600">Welkom, {{ Auth::user()->name }}</span>

                <!-- Uitlogknop -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-primary-button type="submit" class="bg-red-600 hover:bg-red-700 text-white">
                        Uitloggen
                    </x-primary-button>
                </form>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <p>Je bent succesvol ingelogd!</p>
                    <p>Hier kun je de dashboard-functionaliteiten toevoegen.</p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
