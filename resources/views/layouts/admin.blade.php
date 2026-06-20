<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Gestion Déchets Dakar</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>

<body class="bg-gray-100">
    <div class="flex h-screen">
        <!-- Sidebar -->
        <div class="w-72 bg-gradient-to-b from-gray-800 to-gray-900 text-white flex flex-col shadow-xl">
            <div class="p-5 border-b border-gray-700">
                <div class="flex items-center gap-2">
                    <div class="text-2xl"></div>
                    <div>
                        <h1 class="text-xl font-bold">Gestion Déchets</h1>
                        <p class="text-xs text-gray-400">Administration</p>
                    </div>
                </div>
            </div>

            <div class="p-4 mx-3 mt-4 bg-gray-700/50 rounded-lg">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-gray-600 rounded-full flex items-center justify-center text-lg font-bold">
                        {{ substr(auth()->user()->prenom, 0, 1) }}{{ substr(auth()->user()->nom, 0, 1) }}
                    </div>
                    <div>
                        <p class="font-semibold">{{ auth()->user()->prenom }} {{ auth()->user()->nom }}</p>
                        <p class="text-xs text-gray-400">Administrateur</p>
                    </div>
                </div>
            </div>

            <nav class="flex-1 mt-6">
                <a href="{{ route('admin.dashboard') }}"
                    class="flex items-center px-5 py-3 text-gray-300 hover:bg-gray-700 transition {{ request()->routeIs('admin.dashboard') ? 'bg-gray-700 border-l-4 border-emerald-500' : '' }}">
                    <i class="fas fa-tachometer-alt w-5 mr-3"></i> Tableau de bord
                </a>
                <a href="{{ route('admin.utilisateurs') }}"
                    class="flex items-center px-5 py-3 text-gray-300 hover:bg-gray-700 transition {{ request()->routeIs('admin.utilisateurs*') ? 'bg-gray-700 border-l-4 border-emerald-500' : '' }}">
                    <i class="fas fa-users w-5 mr-3"></i> Utilisateurs
                </a>
                <a href="{{ route('admin.collectes') }}"
                    class="flex items-center px-5 py-3 text-gray-300 hover:bg-gray-700 transition {{ request()->routeIs('admin.collectes*') ? 'bg-gray-700 border-l-4 border-emerald-500' : '' }}">
                    <i class="fas fa-truck w-5 mr-3"></i> Collectes
                </a>
                <a href="{{ route('admin.kits') }}"
                    class="flex items-center px-5 py-3 text-gray-300 hover:bg-gray-700 transition {{ request()->routeIs('admin.kits*') ? 'bg-gray-700 border-l-4 border-emerald-500' : '' }}">
                    <i class="fas fa-box w-5 mr-3"></i> Kits commandés
                </a>
                <a href="{{ route('admin.statistiques') }}"
                    class="flex items-center px-5 py-3 text-gray-300 hover:bg-gray-700 transition {{ request()->routeIs('admin.statistiques*') ? 'bg-gray-700 border-l-4 border-emerald-500' : '' }}">
                    <i class="fas fa-chart-line w-5 mr-3"></i> Statistiques
                </a>

                <a href="{{ route('admin.recompenses.index') }}"
                    class="flex items-center px-5 py-3 text-gray-300 hover:bg-gray-700 transition
   {{ request()->routeIs('admin.recompenses*') ? 'bg-gray-700 border-l-4 border-purple-500' : '' }}">
                    <i class="fas fa-gift w-5 mr-3"></i> Récompenses
                </a>
            </nav>

            <div class="p-4 border-t border-gray-700">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        class="flex items-center w-full px-3 py-2 text-gray-400 hover:text-white hover:bg-gray-700 rounded-lg transition">
                        <i class="fas fa-sign-out-alt w-5 mr-3"></i> Déconnexion
                    </button>
                </form>
            </div>
        </div>

        <!-- Contenu principal -->
        <div class="flex-1 overflow-y-auto">
            <div class="bg-white shadow-sm px-6 py-4 sticky top-0 z-10">
                <div class="flex justify-between items-center">
                    <h2 class="text-xl font-semibold text-gray-800">
                        @yield('title', 'Administration')
                    </h2>
                    <div class="flex items-center gap-4">
                        <span class="text-sm text-gray-500">
                            <i class="far fa-calendar-alt mr-1"></i> {{ now()->format('d/m/Y H:i') }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="p-6">
                @if (session('success'))
                    <div class="bg-green-500 text-white p-4 rounded-lg mb-4 shadow flex items-center">
                        <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
                    </div>
                @endif
                @if (session('error'))
                    <div class="bg-red-500 text-white p-4 rounded-lg mb-4 shadow flex items-center">
                        <i class="fas fa-exclamation-circle mr-2"></i> {{ session('error') }}
                    </div>
                @endif
                @yield('content')
            </div>
        </div>
    </div>
</body>

</html>
