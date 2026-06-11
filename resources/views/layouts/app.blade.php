<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion Déchets Dakar</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">
    <nav class="bg-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between items-center h-16">
                <div class="text-xl font-bold">Gestion Déchets Dakar</div>

                @auth
                    <div class="flex items-center gap-6">
                        <!-- Menu selon le rôle -->
                        @if (auth()->user()->isAdmin())
                            <a href="{{ route('admin.dashboard') }}" class="text-gray-600 hover:text-gray-900">Admin</a>
                        @elseif(auth()->user()->isCollecteur())
                            <a href="{{ route('collecteur.dashboard') }}" class="text-gray-600 hover:text-gray-900">Mes
                                tournées</a>
                        @elseif(auth()->user()->isPartenaire())
                            <a href="{{ route('partenaire.dashboard') }}" class="text-gray-600 hover:text-gray-900">Espace
                                partenaire</a>
                        @else
                            <a href="{{ route('menage.dashboard') }}" class="text-gray-600 hover:text-gray-900">Mon
                                espace</a>
                        @endif

                        <span class="text-gray-600">Bonjour {{ auth()->user()->prenom }}</span>

                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="text-red-500 hover:text-red-700">Déconnexion</button>
                        </form>
                    </div>
                @else
                    <div class="flex gap-4">
                        <a href="{{ route('login') }}" class="text-blue-500 hover:text-blue-700">Connexion</a>
                        <a href="{{ route('register') }}" class="text-green-500 hover:text-green-700">Inscription</a>
                    </div>
                @endauth
            </div>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto px-4 py-8">
        @if (session('success'))
            <div class="bg-green-500 text-white p-3 rounded mb-4">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="bg-red-500 text-white p-3 rounded mb-4">{{ session('error') }}</div>
        @endif

        @yield('content')
    </main>
</body>

</html>
