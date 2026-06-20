<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Partenaire - Gestion Déchets Dakar</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>

<body class="bg-gray-100">
    <div class="flex h-screen">
        <!-- Sidebar -->
        <div class="w-72 bg-gradient-to-b from-purple-700 to-purple-900 text-white flex flex-col shadow-xl">
            <div class="p-5 border-b border-purple-600">
                <div class="flex items-center gap-2">
                    <div class="text-2xl">♻️</div>
                    <div>
                        <h1 class="text-xl font-bold">Gestion Déchets</h1>
                        <p class="text-xs text-purple-300">Espace Partenaire</p>
                    </div>
                </div>
            </div>

            <div class="p-4 mx-3 mt-4 bg-purple-800/50 rounded-lg">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-purple-600 rounded-full flex items-center justify-center">
                        {{ substr(auth()->user()->prenom, 0, 1) }}{{ substr(auth()->user()->nom, 0, 1) }}
                    </div>
                    <div>
                        <p class="font-semibold">{{ auth()->user()->prenom }} {{ auth()->user()->nom }}</p>
                        <p class="text-xs text-purple-300">{{ auth()->user()->email }}</p>
                    </div>
                </div>
            </div>

            <nav class="flex-1 mt-6">
                <a href="{{ route('partenaire.dashboard') }}"
                    class="flex items-center px-5 py-3 text-purple-100 hover:bg-purple-800 transition {{ request()->routeIs('partenaire.dashboard') ? 'bg-purple-800 border-r-4 border-white' : '' }}">
                    <i class="fas fa-tachometer-alt w-5 mr-3"></i> Tableau de bord
                </a>
                <a href="{{ route('partenaire.dechets') }}"
                    class="flex items-center px-5 py-3 text-purple-100 hover:bg-purple-800 transition {{ request()->routeIs('partenaire.dechets*') ? 'bg-purple-800 border-r-4 border-white' : '' }}">
                    <i class="fas fa-boxes w-5 mr-3"></i> Déchets reçus
                </a>
                <a href="{{ route('partenaire.statistiques') }}"
                    class="flex items-center px-5 py-3 text-purple-100 hover:bg-purple-800 transition {{ request()->routeIs('partenaire.statistiques') ? 'bg-purple-800 border-r-4 border-white' : '' }}">
                    <i class="fas fa-chart-line w-5 mr-3"></i> Statistiques
                </a>
            </nav>

            <div class="p-4 border-t border-purple-600">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        class="flex items-center w-full px-3 py-2 text-purple-200 hover:text-white hover:bg-purple-800 rounded-lg transition">
                        <i class="fas fa-sign-out-alt w-5 mr-3"></i> Déconnexion
                    </button>
                </form>
            </div>
        </div>

        <!-- Contenu principal -->
        <div class="flex-1 overflow-y-auto">
            <div class="bg-white shadow-sm px-6 py-4 sticky top-0 z-10">
                <div class="flex justify-between items-center">
                    <h2 class="text-xl font-semibold text-gray-800">@yield('title', 'Espace Partenaire')</h2>
                    <span class="text-sm text-gray-500"><i
                            class="far fa-calendar-alt mr-1"></i>{{ now()->format('d/m/Y H:i') }}</span>
                </div>
            </div>

            <div class="p-6">
                @if (session('success'))
                    <div class="bg-green-500 text-white p-3 rounded mb-4 shadow">{{ session('success') }}</div>
                @endif
                @if (session('error'))
                    <div class="bg-red-500 text-white p-3 rounded mb-4 shadow">{{ session('error') }}</div>
                @endif
                @yield('content')
            </div>
        </div>
    </div>
</body>

</html>
