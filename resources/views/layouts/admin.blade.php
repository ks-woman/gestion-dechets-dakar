<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Gestion Déchets Dakar</title>
    @vite('resources/css/app.css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        /* ---------- RESET & BASE ---------- */
        * {
            box-sizing: border-box;
        }

        html,
        body {
            margin: 0;
            padding: 0;
            height: 100%;
            overflow: hidden;
            font-family: 'Inter', system-ui, sans-serif;
        }

        /* ---------- LAYOUT ---------- */
        .app {
            display: flex;
            height: 100vh;
            overflow: hidden;
        }

        /* ---------- SIDEBAR ---------- */
        .sidebar {
            width: 280px;
            min-width: 280px;
            max-width: 280px;
            flex-shrink: 0;
            height: 100vh;
            overflow-y: auto;
            background: linear-gradient(180deg, #065f46 0%, #064e3b 100%);
            color: white;
            display: flex;
            flex-direction: column;
            z-index: 30;
            transition: transform 0.3s ease;
            box-shadow: 2px 0 8px rgba(0, 0, 0, 0.15);
        }

        .sidebar-header {
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            flex-shrink: 0;
        }

        .sidebar-profile {
            margin: 1rem 1.25rem;
            padding: 0.75rem 1rem;
            background: rgba(255, 255, 255, 0.08);
            border-radius: 0.75rem;
            flex-shrink: 0;
        }

        .sidebar-nav {
            flex: 1;
            overflow-y: auto;
            padding: 0.5rem 0.75rem 1rem;
        }

        .sidebar-nav a {
            display: flex;
            align-items: center;
            padding: 0.6rem 1rem;
            margin-bottom: 0.125rem;
            border-radius: 0.5rem;
            color: rgba(255, 255, 255, 0.8);
            transition: all 0.15s;
            font-size: 0.9rem;
            text-decoration: none;
            position: relative;
        }

        .sidebar-nav a i {
            width: 1.5rem;
            margin-right: 0.75rem;
            font-size: 1rem;
            text-align: center;
        }

        .sidebar-nav a:hover {
            background: rgba(255, 255, 255, 0.1);
            color: white;
        }

        .sidebar-nav a.active {
            background: #10b981;
            color: white;
        }

        .badge-notif {
            position: absolute;
            right: 0.75rem;
            top: 50%;
            transform: translateY(-50%);
            background: #ef4444;
            color: white;
            font-size: 0.7rem;
            font-weight: bold;
            padding: 0.1rem 0.5rem;
            border-radius: 9999px;
            line-height: 1.4;
        }

        .sidebar-footer {
            padding: 1rem 1.5rem;
            border-top: 1px solid rgba(255, 255, 255, 0.08);
            flex-shrink: 0;
        }

        /* ---------- MAIN CONTENT ---------- */
        .main {
            flex: 1;
            min-width: 0;
            display: flex;
            flex-direction: column;
            height: 100vh;
            overflow: hidden;
            background: #f3f4f6;
        }

        .main-header {
            flex-shrink: 0;
            padding: 0.75rem 1.5rem;
            background: linear-gradient(135deg, #059669, #065f46);
            color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        .main-body {
            flex: 1;
            overflow-y: auto;
            padding: 1.5rem;
        }

        /* ---------- SCROLLBAR STYLING ---------- */
        .sidebar::-webkit-scrollbar {
            width: 4px;
        }

        .sidebar::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.25);
            border-radius: 4px;
        }

        .main-body::-webkit-scrollbar {
            width: 6px;
        }

        .main-body::-webkit-scrollbar-thumb {
            background: #10b981;
            border-radius: 4px;
        }

        /* ---------- RESPONSIVE ---------- */
        .hamburger {
            display: none;
            background: none;
            border: none;
            color: white;
            font-size: 1.5rem;
            cursor: pointer;
            padding: 0.25rem 0.5rem;
            border-radius: 0.375rem;
        }

        .hamburger:hover {
            background: rgba(255, 255, 255, 0.1);
        }

        .overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.4);
            z-index: 20;
        }

        @media (max-width: 768px) {
            .hamburger {
                display: block;
            }

            .sidebar {
                position: fixed;
                top: 0;
                left: 0;
                transform: translateX(-100%);
                height: 100vh;
                z-index: 40;
                box-shadow: 2px 0 12px rgba(0, 0, 0, 0.3);
            }

            .sidebar-open .sidebar {
                transform: translateX(0);
            }

            .sidebar-open .overlay {
                display: block;
            }

            .main-header {
                padding: 0.5rem 1rem;
            }

            .main-body {
                padding: 1rem;
            }

            .sidebar-nav a {
                font-size: 0.85rem;
                padding: 0.5rem 0.75rem;
            }
        }

        /* ---------- ALERTES ---------- */
        .alert-success {
            background: #d1fae5;
            color: #065f46;
            padding: 0.75rem 1rem;
            border-radius: 0.5rem;
            margin-bottom: 1rem;
            border-left: 4px solid #10b981;
        }

        .alert-danger {
            background: #fee2e2;
            color: #991b1b;
            padding: 0.75rem 1rem;
            border-radius: 0.5rem;
            margin-bottom: 1rem;
            border-left: 4px solid #ef4444;
        }

        .alert-info {
            background: #dbeafe;
            color: #1e40af;
            padding: 0.75rem 1rem;
            border-radius: 0.5rem;
            margin-bottom: 1rem;
            border-left: 4px solid #3b82f6;
        }
    </style>
</head>

<body>
    <div class="app" id="app">
        <!-- Overlay (mobile) -->
        <div class="overlay" onclick="closeSidebar()"></div>

        <!-- SIDEBAR -->
        <aside class="sidebar">
            <div class="sidebar-header">
                <div class="flex items-center gap-2">
                    <span class="text-2xl"></span>
                    <div>
                        <div class="font-bold text-lg">Gestion Déchets</div>
                        <div class="text-xs text-white/60">Administration</div>
                    </div>
                </div>
            </div>

            <div class="sidebar-profile">
                <div class="flex items-center gap-3">
                    <div
                        class="w-10 h-10 rounded-full bg-emerald-600 flex items-center justify-center font-bold text-lg">
                        {{ substr(auth()->user()->prenom, 0, 1) }}{{ substr(auth()->user()->nom, 0, 1) }}
                    </div>
                    <div>
                        <div class="font-semibold text-sm">{{ auth()->user()->prenom }} {{ auth()->user()->nom }}</div>
                        <div class="text-xs text-white/60">Administrateur</div>
                    </div>
                </div>
            </div>

            <nav class="sidebar-nav">
                @php
                    $menu = [
                        ['route' => 'admin.dashboard', 'icon' => 'fa-tachometer-alt', 'label' => 'Tableau de bord'],
                        ['route' => 'admin.utilisateurs', 'icon' => 'fa-users', 'label' => 'Utilisateurs'],
                        ['route' => 'admin.collectes', 'icon' => 'fa-truck', 'label' => 'Collectes'],
                        ['route' => 'admin.kits', 'icon' => 'fa-box', 'label' => 'Kits commandés'],
                        ['route' => 'admin.statistiques', 'icon' => 'fa-chart-line', 'label' => 'Statistiques'],
                        [
                            'route' => 'admin.statistiques.collecteurs',
                            'icon' => 'fa-chart-bar',
                            'label' => 'Stats collecteurs',
                        ],
                        ['route' => 'admin.recompenses.index', 'icon' => 'fa-gift', 'label' => 'Récompenses'],
                        ['route' => 'admin.zones.index', 'icon' => 'fa-map-marked-alt', 'label' => 'Zones'],
                        [
                            'route' => 'admin.reclamations.index',
                            'icon' => 'fa-exclamation-triangle',
                            'label' => 'Réclamations',
                        ],
                        ['route' => 'admin.stocks.index', 'icon' => 'fa-warehouse', 'label' => 'Stocks'],
                        ['route' => 'admin.commandes.index', 'icon' => 'fa-shopping-cart', 'label' => 'Commandes'],
                        ['route' => 'admin.collecteurs', 'icon' => 'fa-users', 'label' => 'Collecteurs'],
                        ['route' => 'admin.abonnements.index', 'icon' => 'fa-credit-card', 'label' => 'Abonnements'],
                    ];
                @endphp

                @foreach ($menu as $item)
                    <a href="{{ route($item['route']) }}"
                        class="{{ request()->routeIs($item['route'] . '*') ? 'active' : '' }}">
                        <i class="fas {{ $item['icon'] }}"></i>
                        {{ $item['label'] }}
                    </a>
                @endforeach

                <!-- Notifications (avec badge) -->
                <a href="{{ route('notifications.index') }}"
                    class="{{ request()->routeIs('notifications*') ? 'active' : '' }}">
                    <i class="fas fa-bell"></i>
                    Notifications
                    @php
                        $nonLues = \App\Models\Notification::where('user_id', auth()->id())
                            ->where('est_lu', false)
                            ->count();
                    @endphp
                    @if ($nonLues > 0)
                        <span class="badge-notif">{{ $nonLues }}</span>
                    @endif
                </a>
            </nav>

            <div class="sidebar-footer">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="flex items-center w-full text-white/70 hover:text-white transition">
                        <i class="fas fa-sign-out-alt w-5 mr-3"></i> Déconnexion
                    </button>
                </form>
            </div>
        </aside>

        <!-- MAIN -->
        <main class="main">
            <header class="main-header">
                <div class="flex items-center gap-3">
                    <button class="hamburger" onclick="toggleSidebar()" aria-label="Ouvrir le menu">
                        <i class="fas fa-bars"></i>
                    </button>
                    <h2 class="text-lg font-semibold">@yield('title', 'Administration')</h2>
                </div>
                <span class="text-sm text-white/80">
                    <i class="far fa-calendar-alt mr-1"></i> {{ now()->format('d/m/Y H:i') }}
                </span>
            </header>

            <div class="main-body">
                @if (session('success'))
                    <div class="alert-success">{{ session('success') }}</div>
                @endif
                @if (session('error'))
                    <div class="alert-danger">{{ session('error') }}</div>
                @endif
                @if (session('info'))
                    <div class="alert-info">{{ session('info') }}</div>
                @endif
                @yield('content')
            </div>
        </main>
    </div>

    <script>
        function toggleSidebar() {
            document.getElementById('app').classList.toggle('sidebar-open');
        }

        function closeSidebar() {
            document.getElementById('app').classList.remove('sidebar-open');
        }

        // Fermer la sidebar lors du clic sur un lien en mobile
        document.querySelectorAll('.sidebar-nav a').forEach(link => {
            link.addEventListener('click', () => {
                if (window.innerWidth <= 768) {
                    closeSidebar();
                }
            });
        });

        // Fermer la sidebar avec la touche Échap
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                closeSidebar();
            }
        });
    </script>
</body>

</html>
