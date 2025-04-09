<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Klantenportaal - Hekkensluiter</title>

    <!-- Voeg hier de link naar app.css toe -->
    @vite('resources/css/app.css')

    <!-- Voeg Font Awesome toe voor de pijlpictogram -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>

<header>
    <div class="header-container">
        <!-- Links: Logo -->
        <a href="{{ url('/') }}">
            <img src="{{ asset('images/logohoornhack.png') }}" alt="Hekkensluiter Logo" class="logo">
        </a>

        <!-- Midden: Navigatieknoppen -->
        <nav class="nav-center">
            <a href="#content" class="nav-button">Informatie</a>
            <a href="#faciliteiten" class="nav-button">Faciliteiten</a>
            <a href="#bijzonderheden" class="nav-button">Kenmerken</a>
            <a href="#geschiedenis" class="nav-button">Geschiedenis</a>
        </nav>

        <!-- Rechts: Inloggen -->
        <div class="nav-right">
            <a href="{{ route('login') }}" class="login-button">Inloggen</a>
        </div>
    </div>
</header>



<!-- Heldere intro met achtergrondafbeelding (geen afgeronde randen, tegen de header aan) -->
<div class="relative w-full h-[800px] mt-[88px]">
    <img src="{{ asset('images/cellencomplex.jpg') }}" alt="Cellencomplex" class="w-full h-full object-cover brightness-75">
    <div class="absolute inset-0 flex flex-col items-center justify-center text-center px-6 text-white">
        <h1 class="text-5xl font-bold mb-6 drop-shadow-lg">Informatie over het Cellencomplex</h1>
        <p class="text-xl max-w-4xl drop-shadow-md">
            Lees hier meer over de achtergronden, faciliteiten en geschiedenis van het arrestantencomplex.
        </p>

        <!-- Lees Meer Button met pijl naar beneden -->
        <div class="mt-6 flex space-x-4">
            <a href="#content" class="read-more-btn inline-flex items-center px-6 py-3 text-white font-semibold text-lg rounded-full hover:bg-brown-700 transition-all">
                Lees Meer
                <i class="fas fa-chevron-down ml-2"></i>
            </a>
            <!-- Contact Button -->
            <a href="{{ route('contact') }}" class="contact-btn inline-flex items-center px-6 py-3 text-white font-semibold text-lg rounded-full hover:bg-brown-700 transition-all">
                Neem Contact Op
            </a>
        </div>
    </div>
</div>

<div id="content" class="content flex flex-col items-center justify-center px-4 pb-16">
    <div class="flex flex-col gap-6 w-full max-w-4xl">
        <div class="info-box p-6 bg-white border border-gray-200 rounded-lg shadow-lg" >
            <div class="info-container">
                <!-- Tekst aan de linkerkant -->
                <div class="info-text">
                    <h2 class="text-2xl font-semibold text-gray-800 mb-2">Over het Arrestantencomplex</h2>
                    <p class="text-gray-600">
                        Het Arrestantencomplex in Houten is ontworpen voor de politie en biedt plaats aan 105 cellen, naast intake- en verhoorfaciliteiten. Het gebouw is bekroond als "Plan van de Maand" (2006) door Welstand en Monumenten Midden Nederland vanwege de rustgevende vormgeving en hoogwaardige architectuur.
                    </p>
                </div>

                <!-- Afbeelding rechts van de tekst -->
                <div class="info-image">
                    <img src="{{ asset('images/cel.jpg') }}" alt="Cel in het Arrestantencomplex" class="w-full h-64 object-cover rounded-lg shadow">
                </div>
            </div>
        </div>

        <!-- Voor de andere secties kun je hetzelfde doen als hierboven -->
        <div class="info-box p-6 bg-white border border-gray-200 rounded-lg shadow-lg" id="faciliteiten">
            <div class="info-container">
                <!-- Tekst -->
                <div class="info-text">
                    <h2 class="text-2xl font-semibold text-gray-800 mb-2">Faciliteiten en Indeling</h2>
                    <ul class="list-disc list-inside text-gray-600">
                        <li>105 cellen en 10 intakecellen</li>
                        <li>20 verhoorkamers en 2 digitale verhoorstudio’s</li>
                        <li>Ruimten voor advocaten en familie</li>
                        <li>Dagverblijven en luchtplaatsen</li>
                        <li>Kantoorruimtes volgens Het Nieuwe Werken</li>
                    </ul>
                </div>

                <!-- Afbeelding rechts van de tekst -->
                <div class="info-image">
                    <img src="{{ asset('images/faciliteiten.jpg') }}" alt="Faciliteiten in het Arrestantencomplex" class="w-full h-64 object-cover rounded-lg shadow">
                </div>
            </div>
        </div>

        <!-- Bijzonderheden sectie -->
        <div class="info-box p-6 bg-white border border-gray-200 rounded-lg shadow-lg" id="bijzonderheden">
            <div class="info-container">
                <!-- Tekst -->
                <div class="info-text">
                    <h2 class="text-2xl font-semibold text-gray-800 mb-2">Bijzondere Kenmerken</h2>
                    <p class="text-gray-600">
                        De routing in het gebouw is complex, ontworpen met veiligheid voorop. De scheiding tussen verzorging en verhoor zorgt voor een duidelijke structuur. Het materiaalgebruik is duurzaam en de vormgeving oogt eenvoudig maar is functioneel en esthetisch doordacht.
                    </p>
                </div>

                <!-- Afbeelding -->
                <div class="info-image">
                    <img src="{{ asset('images/bijzonderheden.jpg') }}" alt="Bijzondere kenmerken van het Arrestantencomplex" class="w-full h-64 object-cover rounded-lg shadow">
                </div>
            </div>
        </div>

        <!-- Geschiedenis sectie -->
        <div class="info-box p-6 bg-white border border-gray-200 rounded-lg shadow-lg" id="geschiedenis">
            <div class="info-container">
                <!-- Tekst -->
                <div class="info-text">
                    <h2 class="text-2xl font-semibold text-gray-800 mb-2">Geschiedenis van Handhaving</h2>
                    <p class="text-gray-600">
                        De rol van ordehandhaving gaat eeuwen terug. Van schouten met hulp van weerbare mannen in de 18e eeuw tot de oprichting van een landelijke politiemacht door Napoleon in 1811. In de Tweede Wereldoorlog werden veldwachters geïntegreerd in de politie. Na 1945 werd het vertrouwen in de politie langzaam hersteld.
                    </p>
                </div>

                <!-- Afbeelding -->
                <div class="info-image">
                    <img src="{{ asset('images/geschiedenis.jpg') }}" alt="Geschiedenis van handhaving" class="w-full h-64 object-cover rounded-lg shadow">
                </div>
            </div>
        </div>

    </div>
</div>


<footer class="text-white text-center py-4">
    <p>&copy; 2025 Hekkensluiter. Alle rechten voorbehouden.</p>
    <p>Heeft u hulp nodig? <a href="{{ route('contact') }}" class="btn-contact text-blue-400">Neem contact met ons op</a>.</p>
</footer>

</body>
</html>
