<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Collecteur - Gestion Déchets Dakar</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">
    <div class="flex h-screen">
        <!-- Sidebar -->
        <div class="w-72 bg-gradient-to-b from-blue-700 to-blue-900 text-white flex flex-col">
            <div class="p-5 border-b border-blue-600">
                <h1 class="text-xl font-bold"> Gestion Déchets</h1>
                <p class="text-xs text-blue-300 mt-1">Espace Collecteur</p>
            </div>

            <div class="p-4 mx-3 mt-4 bg-blue-800/50 rounded-lg">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-blue-600 rounded-full flex items-center justify-center">
                        {{ substr(auth()->user()->prenom, 0, 1) }}{{ substr(auth()->user()->nom, 0, 1) }}
                    </div>
                    <div>
                        <p class="font-semibold">{{ auth()->user()->prenom }} {{ auth()->user()->nom }}</p>
                        <p class="text-xs text-blue-300">{{ auth()->user()->email }}</p>
                    </div>
                </div>
            </div>

            <nav class="flex-1 mt-6">
                <a href="{{ route('collecteur.dashboard') }}"
                    class="flex items-center px-5 py-3 hover:bg-blue-800 transition">
                    <span class="text-xl mr-3"></span> Tableau de bord
                </a>
                <a href="{{ route('collecteur.tournee') }}"
                    class="flex items-center px-5 py-3 hover:bg-blue-800 transition">
                    <span class="text-xl mr-3"></span> Ma tournée
                </a>
                <a href="{{ route('collecteur.activer-kit') }}"
                    class="flex items-center px-5 py-3 hover:bg-blue-800 transition">
                    <span class="text-xl mr-3"></span> Activer un kit
                </a>
                <a href="{{ route('collecteur.enregistrer-collecte') }}"
                    class="flex items-center px-5 py-3 hover:bg-blue-800 transition">
                    <span class="text-xl mr-3"></span> Enregistrer collecte
                </a>
            </nav>

            <div class="p-4 border-t border-blue-600">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        class="flex items-center w-full px-3 py-2 text-blue-200 hover:text-white hover:bg-blue-800 rounded-lg transition">
                        <span class="text-xl mr-3"></span> Déconnexion
                    </button>
                </form>
            </div>
        </div>

        <!-- Contenu principal -->
        <div class="flex-1 overflow-y-auto">
            <div class="bg-white shadow-sm px-6 py-4 sticky top-0 z-10">
                <div class="flex justify-between items-center">
                    <h2 class="text-xl font-semibold text-gray-800">@yield('title', 'Espace Collecteur')</h2>
                    <span class="text-sm text-gray-500">{{ now()->format('d/m/Y H:i') }}</span>
                </div>
            </div>

            <div class="p-6">
                @if (session('success'))
                    <div class="bg-green-500 text-white p-3 rounded mb-4">{{ session('success') }}</div>
                @endif
                @if (session('error'))
                    <div class="bg-red-500 text-white p-3 rounded mb-4">{{ session('error') }}</div>
                @endif
                @yield('content')
            </div>
        </div>
    </div>
</body>

</html>
