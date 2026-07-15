<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Collecteur - Gestion Déchets Dakar</title>
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
                        <h1 class="text-xl font-bold">Gestion Déchets</h1>
                        <p class="text-xs text-emerald-300">Espace Collecteur</p>
                    </div>
                </div>
            </div>

            <div class="p-4 mx-3 mt-4 bg-emerald-700/50 rounded-lg">
                <div class="flex items-center gap-3">
                    <div
                        class="w-10 h-10 bg-emerald-600 rounded-full flex items-center justify-center text-lg font-bold">
                        {{ substr(auth()->user()->prenom, 0, 1) }}{{ substr(auth()->user()->nom, 0, 1) }}
                    </div>
                    <div>
                        <p class="font-semibold">{{ auth()->user()->prenom }} {{ auth()->user()->nom }}</p>
                        <p class="text-xs text-emerald-300">{{ auth()->user()->email }}</p>
                    </div>
                </div>
            </div>

            <nav class="flex-1 mt-6">
                <a href="{{ route('collecteur.dashboard') }}"
                    class="flex items-center px-5 py-3 text-emerald-100 hover:bg-emerald-700 transition
                    {{ request()->routeIs('collecteur.dashboard') ? 'sidebar-link-active' : '' }}">
                    <i class="fas fa-tachometer-alt w-5 mr-3"></i> Tableau de bord
                </a>
                <a href="{{ route('collecteur.tournee') }}"
                    class="flex items-center px-5 py-3 text-emerald-100 hover:bg-emerald-700 transition
                    {{ request()->routeIs('collecteur.tournee') ? 'sidebar-link-active' : '' }}">
                    <i class="fas fa-map-marked-alt w-5 mr-3"></i> Ma tournée
                </a>
                <a href="{{ route('collecteur.activer-kit') }}"
                    class="flex items-center px-5 py-3 text-emerald-100 hover:bg-emerald-700 transition
                    {{ request()->routeIs('collecteur.activer-kit') ? 'sidebar-link-active' : '' }}">
                    <i class="fas fa-qrcode w-5 mr-3"></i> Activer un kit
                </a>
                <a href="{{ route('collecteur.enregistrer-collecte') }}"
                    class="flex items-center px-5 py-3 text-emerald-100 hover:bg-emerald-700 transition
                    {{ request()->routeIs('collecteur.enregistrer-collecte') ? 'sidebar-link-active' : '' }}">
                    <i class="fas fa-clipboard-list w-5 mr-3"></i> Enregistrer collecte
                </a>
                <a href="{{ route('collecteur.historique') }}"
                    class="flex items-center px-5 py-3 text-emerald-100 hover:bg-emerald-700 transition
                    {{ request()->routeIs('collecteur.historique') ? 'sidebar-link-active' : '' }}">
                    <i class="fas fa-history w-5 mr-3"></i> Historique
                </a>
                <a href="{{ route('collecteur.statistiques') }}"
                    class="flex items-center px-5 py-3 text-emerald-100 hover:bg-emerald-700 transition
                    {{ request()->routeIs('collecteur.statistiques') ? 'sidebar-link-active' : '' }}">
                    <i class="fas fa-chart-bar w-5 mr-3"></i> Statistiques
                </a>
                <a href="{{ route('collecteur.primes') }}"
                    class="flex items-center px-5 py-3 text-emerald-100 hover:bg-emerald-700 transition
                    {{ request()->routeIs('collecteur.primes') ? 'sidebar-link-active' : '' }}">
                    <i class="fas fa-coins w-5 mr-3"></i> Mes primes
                </a>

                <a href="{{ route('notifications.index') }}"
                    class="flex items-center px-4 py-2.5 text-white hover:bg-emerald-700 rounded-lg transition
    {{ request()->routeIs('notifications*') ? 'bg-emerald-700' : '' }}">
                    <i class="fas fa-bell w-5 mr-3"></i> Notifications
                    @php
                        $nonLues = App\Models\Notification::where('user_id', auth()->id())
                            ->where('est_lu', false)
                            ->count();
                    @endphp
                    @if ($nonLues > 0)
                        <span
                            class="ml-auto bg-red-500 text-white text-xs font-bold px-2 py-0.5 rounded-full">{{ $nonLues }}</span>
                    @endif
                </a>
            </nav>

            <div class="p-4 border-t border-emerald-700">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        class="flex items-center w-full px-3 py-2 text-emerald-200 hover:text-white hover:bg-emerald-700 rounded-lg transition">
                        <i class="fas fa-sign-out-alt w-5 mr-3"></i> Déconnexion
                    </button>
                </form>
            </div>
        </div>

        <!-- Contenu principal -->
        <div class="flex-1 overflow-y-auto">
            <div class="header-primary">
                <div class="flex justify-between items-center">
                    <h2 class="text-xl font-semibold">@yield('title', 'Espace Collecteur')</h2>
                    <span class="text-sm text-emerald-100">{{ now()->format('d/m/Y H:i') }}</span>
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
