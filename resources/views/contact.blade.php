<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact - Hekkensluiter</title>

    <!-- Voeg hier de link naar app.css toe -->
    @vite('resources/css/app.css')
</head>
<body>

    <!-- Header -->
    <header>
        <div class="header-container">
            <!-- Logo met de klikbare link naar de homepagina -->
            <a href="{{ url('/') }}">
                <img src="{{ asset('images/logohoornhack.png') }}" alt="Hekkensluiter Logo" class="logo">
            </a>

            <nav class="space-x-4 ml-auto">
                <a href="{{ route('login') }}" class="login-button">Inloggen</a> <!-- Inlogknop in de header -->
            </nav>
        </div>
    </header>

    <!-- Main content -->
    <div class="content flex flex-col items-center justify-center px-4 mt-24">
        <h1 class="text-4xl font-bold text-gray-800 mb-4">Contacteer Ons</h1>

        <!-- Success message -->
        @if(session('success'))
            <div class="bg-green-500 text-white p-4 rounded-lg mb-4">
                {{ session('success') }}
            </div>
        @endif

        <!-- Contact form -->
        <form action="{{ route('contact.submit') }}" method="POST" class="w-full max-w-lg space-y-6">
            @csrf
            <div>
                <label for="name" class="block text-lg font-medium">Naam</label>
                <input type="text" name="name" id="name" class="w-full px-4 py-2 border rounded-lg" required>
            </div>

            <div>
                <label for="email" class="block text-lg font-medium">E-mail</label>
                <input type="email" name="email" id="email" class="w-full px-4 py-2 border rounded-lg" required>
            </div>

            <div>
                <label for="message" class="block text-lg font-medium">Bericht</label>
                <textarea name="message" id="message" rows="6" class="w-full px-4 py-2 border rounded-lg" required></textarea>
            </div>

            <button type="submit" class="w-full py-2 bg-[#6b4f29] text-white rounded-lg hover:bg-[#5a3e27]">Verstuur Bericht</button>
            </form>
    </div>

    <!-- Footer -->
    <footer class=" text-white text-center py-4">
        <p>&copy; 2025 Hekkensluiter. Alle rechten voorbehouden.</p>
        <p>Heeft u hulp nodig? <a href="{{ route('contact') }}" class="text-blue-400">Neem contact met ons op</a>.</p>
    </footer>

</body>
</html>
