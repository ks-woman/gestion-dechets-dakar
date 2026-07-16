<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Partenaire - Gestion Déchets Dakar</title>
    @vite('resources/css/app.css')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>

<body class="bg-gray-100">
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <div class="w-72 bg-gradient-to-b from-emerald-800 to-emerald-950 text-white flex flex-col shadow-2xl h-screen">
            <div class="p-5 border-b border-emerald-700 flex-shrink-0">
                <div class="flex items-center gap-2">
                    <div class="text-2xl"></div>
                    <div>
                        <h1 class="text-xl font-bold text-white">Gestion Déchets</h1>
                        <p class="text-xs text-white/70">Espace Partenaire</p>
                    </div>
                </div>
            </div>

            <div class="p-4 mx-3 mt-4 bg-emerald-700/50 rounded-lg flex-shrink-0">
                <div class="flex items-center gap-3">
                    <div
                        class="w-10 h-10 bg-emerald-600 rounded-full flex items-center justify-center text-lg font-bold text-white">
                        {{ substr(auth()->user()->prenom, 0, 1) }}{{ substr(auth()->user()->nom, 0, 1) }}
                    </div>
                    <div>
                        <p class="font-semibold text-white">{{ auth()->user()->prenom }} {{ auth()->user()->nom }}</p>
                        <p class="text-xs text-white/70">{{ auth()->user()->email }}</p>
                    </div>
                </div>
            </div>

            <nav class="flex-1 overflow-y-auto mt-4 px-2">
                <a href="{{ route('partenaire.dashboard') }}"
                    class="flex items-center px-4 py-2.5 text-white hover:bg-emerald-700 rounded-lg transition
                    {{ request()->routeIs('partenaire.dashboard') ? 'bg-emerald-700' : '' }}">
                    <i class="fas fa-tachometer-alt w-5 mr-3"></i> Tableau de bord
                </a>
                <a href="{{ route('partenaire.offres') }}"
                    class="flex items-center px-4 py-2.5 text-white hover:bg-emerald-700 rounded-lg transition
                    {{ request()->routeIs('partenaire.offres') ? 'bg-emerald-700' : '' }}">
                    <i class="fas fa-boxes w-5 mr-3"></i> Offres
                </a>


                <a href="{{ route('partenaire.statistiques') }}"
                    class="flex items-center px-4 py-2.5 text-white hover:bg-emerald-700 rounded-lg transition
                    {{ request()->routeIs('partenaire.statistiques') ? 'bg-emerald-700' : '' }}">
                    <i class="fas fa-chart-bar w-5 mr-3"></i> Statistiques
                </a>

                <a href="{{ route('partenaire.historique') }}"
                    class="flex items-center px-4 py-2.5 text-white hover:bg-emerald-700 rounded-lg transition relative">
                    <i class="fas fa-history w-5 mr-3"></i> Historique
                    @php
                        $aConfirmer = \App\Models\Commande::where('partenaire_id', auth()->id())
                            ->where('statut', 'livree')
                            ->count();
                    @endphp
                    @if ($aConfirmer > 0)
                        <span
                            class="absolute right-4 top-2 bg-yellow-500 text-white text-xs font-bold px-2 py-0.5 rounded-full">
                            {{ $aConfirmer }}
                        </span>
                    @endif
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

            <div class="p-4 border-t border-emerald-700 mt-auto flex-shrink-0">
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
                    <h2 class="text-xl font-semibold text-white">@yield('title', 'Espace Partenaire')</h2>
                    <span class="text-sm text-white/80">{{ now()->format('d/m/Y H:i') }}</span>
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
