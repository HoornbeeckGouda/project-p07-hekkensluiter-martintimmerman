<nav x-data="{ open: false }" class="bg-gradient-to-r from-[#5D4037] to-[#8D6E63] text-white fixed top-0 left-0 right-0 z-50 px-4 sm:px-6 lg:px-8 py-3 shadow-lg">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto">
        <div class="header-container flex items-center justify-between">
            <!-- Logo -->
            <div class="shrink-0">
                <a href="{{ route('dashboard') }}" class="flex items-center">
                    <img src="{{ asset('images/logohoornhack.png') }}" alt="Hekkensluiter Logo" class="logo max-h-12 w-auto border-2 border-[#EFEBE9] rounded-lg p-0.5 transition-transform hover:scale-110">
                    <span class="ml-3 text-xl font-bold hidden md:block text-[#EFEBE9]">Hekkensluiter</span>
                </a>
            </div>

            <!-- Navigation Links -->
            <div class="nav-center flex-1 hidden lg:flex justify-center gap-3">
                <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="nav-button bg-[#6D4C41] text-[#EFEBE9] px-4 py-2 rounded-lg font-semibold no-underline border border-[#EFEBE9] transition hover:bg-[#795548] hover:scale-105 hover:shadow-md">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    {{ __('Dashboard') }}
                </x-nav-link>
                
                <x-nav-link :href="route('cells.index')" :active="request()->routeIs('cells.index')" class="nav-button bg-[#6D4C41] text-[#EFEBE9] px-4 py-2 rounded-lg font-semibold no-underline border border-[#EFEBE9] transition hover:bg-[#795548] hover:scale-105 hover:shadow-md">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                    {{ __('Cellen') }}
                </x-nav-link>
                
                <x-nav-link :href="route('prisoners.index')" :active="request()->routeIs('prisoners.index')" class="nav-button bg-[#6D4C41] text-[#EFEBE9] px-4 py-2 rounded-lg font-semibold no-underline border border-[#EFEBE9] transition hover:bg-[#795548] hover:scale-105 hover:shadow-md">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    {{ __('Gevangenen') }}
                </x-nav-link>
                
                <x-nav-link :href="route('user-logs.index')" class="nav-button bg-[#6D4C41] text-[#EFEBE9] px-4 py-2 rounded-lg font-semibold no-underline border border-[#EFEBE9] transition hover:bg-[#795548] hover:scale-105 hover:shadow-md">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    {{ __('Logs') }}
                    
                </x-nav-link>
            </div>

            <!-- Settings Dropdown -->
            <div class="nav-right hidden sm:flex sm:items-center">
                @auth
                    <x-dropdown allign="right" width="48">
                        <x-slot name="trigger">
                            <button class="login-button flex items-center bg-[#5D4037] text-[#EFEBE9] px-4 py-2 rounded-lg border border-[#EFEBE9] font-semibold transition hover:bg-[#4E342E] hover:text-white hover:shadow-md">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                <div>{{ Auth::user()->name }}</div>

                                <div class="ms-1">
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <x-dropdown-link :href="route('profile.edit')" class="flex items-center hover:bg-[#D7CCC8] hover:text-[#3E2723]">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                                {{ __('Profile') }}
                            </x-dropdown-link>

                            <!-- Authentication -->
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf

                                <x-dropdown-link :href="route('logout')"
                                        onclick="event.preventDefault();
                                                    this.closest('form').submit();" class="flex items-center hover:bg-[#D7CCC8] hover:text-[#3E2723]">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                    </svg>
                                    {{ __('Log Out') }}
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                @else
                    <a href="{{ route('login') }}" class="login-button flex items-center bg-[#5D4037] text-[#EFEBE9] px-4 py-2 rounded-lg border border-[#EFEBE9] font-semibold transition hover:bg-[#4E342E] hover:text-white hover:shadow-md">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                        </svg>
                        {{ __('Inloggen') }}
                    </a>
                @endauth
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-[#EFEBE9] hover:text-white hover:bg-[#4E342E] focus:outline-none focus:bg-[#4E342E] focus:text-white transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="block bg-[#6D4C41] text-[#EFEBE9] px-4 py-2 rounded-lg font-semibold border border-[#EFEBE9] mb-2 transition hover:bg-[#795548] hover:shadow-md">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
            
            <x-responsive-nav-link :href="route('cells.index')" :active="request()->routeIs('cells.index')" class="block bg-[#6D4C41] text-[#EFEBE9] px-4 py-2 rounded-lg font-semibold border border-[#EFEBE9] mb-2 transition hover:bg-[#795548] hover:shadow-md">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                </svg>
                {{ __('Cellen') }}
            </x-responsive-nav-link>
            
            <x-responsive-nav-link :href="route('prisoners.index')" :active="request()->routeIs('prisoners.index')" class="block bg-[#6D4C41] text-[#EFEBE9] px-4 py-2 rounded-lg font-semibold border border-[#EFEBE9] mb-2 transition hover:bg-[#795548] hover:shadow-md">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
                {{ __('Gevangenen') }}
            </x-responsive-nav-link>
            
            <x-responsive-nav-link :href="route('user-logs.index')" :active="false" class="block bg-[#6D4C41] text-[#EFEBE9] px-4 py-2 rounded-lg font-semibold border border-[#EFEBE9] mb-2 transition hover:bg-[#795548] hover:shadow-md">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                {{ __('Gebruiker Logs') }}
            </x-responsive-nav-link>
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-[#BCAAA4]">
            @auth
                <div class="px-4">
                    <div class="font-medium text-base text-[#EFEBE9]">{{ Auth::user()->name }}</div>
                    <div class="font-medium text-sm text-[#D7CCC8]">{{ Auth::user()->email }}</div>
                </div>

                <div class="mt-3 space-y-1">
                    <x-responsive-nav-link :href="route('profile.edit')" class="block bg-[#6D4C41] text-[#EFEBE9] px-4 py-2 rounded-lg font-semibold border border-[#EFEBE9] mb-2 transition hover:bg-[#795548] hover:shadow-md flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        {{ __('Profile') }}
                    </x-responsive-nav-link>

                    <!-- Authentication -->
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf

                        <x-responsive-nav-link :href="route('logout')"
                                class="block bg-[#6D4C41] text-[#EFEBE9] px-4 py-2 rounded-lg font-semibold border border-[#EFEBE9] mb-2 transition hover:bg-[#795548] hover:shadow-md flex items-center"
                                onclick="event.preventDefault();
                                            this.closest('form').submit();">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                            {{ __('Log Out') }}
                        </x-responsive-nav-link>
                    </form>
                </div>
            @else
                <div class="mt-3 space-y-1 px-4">
                    <a href="{{ route('login') }}" class="block bg-[#6D4C41] text-[#EFEBE9] px-4 py-2 rounded-lg font-semibold border border-[#EFEBE9] mb-2 transition hover:bg-[#795548] hover:shadow-md flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                        </svg>
                        {{ __('Inloggen') }}
                    </a>
                </div>
            @endauth
        </div>
    </div>
</nav>

<!-- Add spacing to compensate for fixed navbar -->
<div class="pt-20"></div>