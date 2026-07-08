<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Gestion Déchets Dakar</title>
    @vite('resources/css/app.css')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>

<body class="bg-gray-100">
    <div class="flex h-screen">
        <!-- Sidebar -->
        <div class="w-72 bg-gradient-to-b from-emerald-800 to-emerald-950 text-white flex flex-col shadow-2xl">
            <div class="p-5 border-b border-emerald-700">
                <div class="flex items-center gap-2">
                    <div class="text-2xl"></div>
                    <div>
                        <h1 class="text-xl font-bold text-white">Gestion Déchets</h1>
                        <p class="text-xs text-white/70">Administration</p>
                    </div>
                </div>
            </div>

            <div class="p-4 mx-3 mt-4 bg-emerald-700/50 rounded-lg">
                <div class="flex items-center gap-3">
                    <div
                        class="w-10 h-10 bg-emerald-600 rounded-full flex items-center justify-center text-lg font-bold text-white">
                        {{ substr(auth()->user()->prenom, 0, 1) }}{{ substr(auth()->user()->nom, 0, 1) }}
                    </div>
                    <div>
                        <p class="font-semibold text-white">{{ auth()->user()->prenom }} {{ auth()->user()->nom }}</p>
                        <p class="text-xs text-white/70">Administrateur</p>
                    </div>
                </div>
            </div>

            <nav class="flex-1 mt-6">
                <a href="{{ route('admin.dashboard') }}"
                    class="flex items-center px-5 py-3 text-white hover:bg-emerald-700 transition
                    {{ request()->routeIs('admin.dashboard') ? 'sidebar-link-active' : '' }}">
                    <i class="fas fa-tachometer-alt w-5 mr-3"></i> Tableau de bord
                </a>
                <a href="{{ route('admin.utilisateurs') }}"
                    class="flex items-center px-5 py-3 text-white hover:bg-emerald-700 transition
                    {{ request()->routeIs('admin.utilisateurs*') ? 'sidebar-link-active' : '' }}">
                    <i class="fas fa-users w-5 mr-3"></i> Utilisateurs
                </a>
                <a href="{{ route('admin.collectes') }}"
                    class="flex items-center px-5 py-3 text-white hover:bg-emerald-700 transition
                    {{ request()->routeIs('admin.collectes*') ? 'sidebar-link-active' : '' }}">
                    <i class="fas fa-truck w-5 mr-3"></i> Collectes
                </a>
                <a href="{{ route('admin.kits') }}"
                    class="flex items-center px-5 py-3 text-white hover:bg-emerald-700 transition
                    {{ request()->routeIs('admin.kits*') ? 'sidebar-link-active' : '' }}">
                    <i class="fas fa-box w-5 mr-3"></i> Kits commandés
                </a>
                <a href="{{ route('admin.statistiques') }}"
                    class="flex items-center px-5 py-3 text-white hover:bg-emerald-700 transition
                    {{ request()->routeIs('admin.statistiques*') ? 'sidebar-link-active' : '' }}">
                    <i class="fas fa-chart-line w-5 mr-3"></i> Statistiques
                </a>
                <a href="{{ route('admin.recompenses.index') }}"
                    class="flex items-center px-5 py-3 text-white hover:bg-emerald-700 transition
                    {{ request()->routeIs('admin.recompenses*') ? 'sidebar-link-active' : '' }}">
                    <i class="fas fa-gift w-5 mr-3"></i> Récompenses
                </a>
                <a href="{{ route('admin.zones.index') }}"
                    class="flex items-center px-5 py-3 text-white hover:bg-emerald-700 transition
                    {{ request()->routeIs('admin.zones*') ? 'sidebar-link-active' : '' }}">
                    <i class="fas fa-map-marked-alt w-5 mr-3"></i> Zones
                </a>

                <a href="{{ route('admin.reclamations.index') }}"
                    class="flex items-center px-5 py-3 text-white hover:bg-emerald-700 transition
   {{ request()->routeIs('admin.reclamations*') ? 'bg-emerald-700' : '' }}">
                    <i class="fas fa-exclamation-triangle w-5 mr-3"></i> Réclamations
                </a>

                <!-- Stocks -->
                <a href="{{ route('admin.stocks.index') }}"
                    class="flex items-center px-5 py-3 text-white hover:bg-emerald-700 transition
   {{ request()->routeIs('admin.stocks*') ? 'bg-emerald-700' : '' }}">
                    <i class="fas fa-warehouse w-5 mr-3"></i> Stocks
                </a>

                <!-- Commandes -->
                <a href="{{ route('admin.commandes.index') }}"
                    class="flex items-center px-5 py-3 text-white hover:bg-emerald-700 transition
   {{ request()->routeIs('admin.commandes*') ? 'bg-emerald-700' : '' }}">
                    <i class="fas fa-shopping-cart w-5 mr-3"></i> Commandes
                </a>
            </nav>

            <div class="p-4 border-t border-emerald-700">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        class="flex items-center w-full px-3 py-2 text-white/70 hover:text-white hover:bg-emerald-700 rounded-lg transition">
                        <i class="fas fa-sign-out-alt w-5 mr-3"></i> Déconnexion
                    </button>
                </form>
            </div>
        </div>

        <!-- Contenu principal -->
        <div class="flex-1 overflow-y-auto">
            <div class="header-primary">
                <div class="flex justify-between items-center">
                    <h2 class="text-xl font-semibold text-white">@yield('title', 'Administration')</h2>
                    <span class="text-sm text-white/80"><i class="far fa-calendar-alt mr-1"></i>
                        {{ now()->format('d/m/Y H:i') }}</span>
                </div>
            </div>

            <div class="p-6">
                @if (session('success'))
                    <div class="alert-success">{{ session('success') }}</div>
                @endif
                @if (session('error'))
                    <div class="alert-danger">{{ session('error') }}</div>
                @endif
                @yield('content')
            </div>
        </div>
    </div>
</body>

</html>
