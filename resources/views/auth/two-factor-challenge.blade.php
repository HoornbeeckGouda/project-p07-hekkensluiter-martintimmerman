<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tweefactor Authenticatie - Hekkensluiter</title>
    @vite('resources/css/app.css')
</head>
<body>
    <header class="bg-brown-600 text-white py-4">
        <div class="header-container max-w-screen-lg mx-auto flex justify-between items-center">
            <a href="{{ url('/') }}">
                <img src="{{ asset('images/logohoornhack.png') }}" alt="Hekkensluiter Logo" class="logo">
            </a>
        </div>
    </header>
    
    <div class="content2 flex flex-col items-center justify-center px-4 mt-24">
        <x-guest-layout>
            <div class="mb-6 text-center">
                <h2 class="text-2xl font-bold text-gray-900 mb-2">Verificatie vereist</h2>
                <div class="text-sm text-gray-600">
                    <p class="mb-2">Voor je veiligheid hebben we een verificatiecode naar je e-mailadres gestuurd.</p>
                    <p>Controleer je inbox en voer de 6-cijferige code hieronder in om door te gaan.</p>
                </div>
            </div>

            @if (session('status'))
                <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-md">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-green-800">{{ session('status') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <x-auth-session-status class="mb-4" :status="session('status')" />
            
            <form method="POST" action="{{ route('two-factor.verify') }}">
                @csrf
                
                <div>
                    <x-input-label for="code" :value="__('Verificatiecode')" />
                    <x-text-input 
                        id="code" 
                        class="block mt-1 w-full text-center text-2xl tracking-widest" 
                        type="text" 
                        name="code" 
                        required 
                        autofocus 
                        autocomplete="one-time-code" 
                        maxlength="6" 
                        pattern="[0-9]{6}"
                        placeholder="000000"
                        oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                    />
                    <x-input-error :messages="$errors->get('code')" class="mt-2" />
                </div>

                <div class="flex items-center justify-center mt-6">
                    <x-primary-button class="w-full justify-center">
                        {{ __('Verifiëren') }}
                    </x-primary-button>
                </div>
            </form>

            <div class="mt-6 text-center">
                <p class="text-sm text-gray-600">
                    Geen e-mail ontvangen? Controleer je spam/junk folder.
                </p>
                <p class="text-sm text-gray-600 mt-2">
                    <a href="{{ route('login') }}" class="text-indigo-600 hover:text-indigo-500">
                        Terug naar inloggen
                    </a>
                </p>
            </div>
        </x-guest-layout>
    </div>

    <script>
        document.getElementById('code').addEventListener('input', function(e) {
            if (e.target.value.length === 6) {
                setTimeout(() => {
                    e.target.form.submit();
                }, 500);
            }
        });
    </script>
</body>
</html>