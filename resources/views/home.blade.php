@extends('layouts.app')

@section('content')
    <div class="text-center">
        <h1 class="text-4xl font-bold text-gray-800 mb-4">
            Gestion des Déchets à Dakar
        </h1>
        <p class="text-xl text-gray-600 mb-8">
            Solution innovante pour la collecte et la valorisation des déchets dans les quartiers enclavés
        </p>

        <div class="flex justify-center gap-4">
            @guest
                <a href="{{ route('register') }}" class="bg-green-500 text-white px-6 py-3 rounded-lg hover:bg-green-600">
                    Créer un compte
                </a>
                <a href="{{ route('login') }}" class="bg-blue-500 text-white px-6 py-3 rounded-lg hover:bg-blue-600">
                    Se connecter
                </a>
            @endguest

            @auth
                @if (auth()->user()->isAdmin())
                    <a href="{{ route('admin.dashboard') }}"
                        class="bg-blue-500 text-white px-6 py-3 rounded-lg hover:bg-blue-600">
                        Accéder à mon espace (Admin)
                    </a>
                @elseif(auth()->user()->isCollecteur())
                    <a href="{{ route('collecteur.dashboard') }}"
                        class="bg-blue-500 text-white px-6 py-3 rounded-lg hover:bg-blue-600">
                        Accéder à mon espace (Collecteur)
                    </a>
                @else
                    <a href="{{ route('menage.dashboard') }}" class="bg-blue-500...">
                        Accéder à mon espace
                    </a>
                @endif
            @endauth
        </div>
    </div>

    <div class="grid md:grid-cols-3 gap-6 mt-16">
        <div class="bg-white p-6 rounded-lg shadow text-center">
            <div class="text-4xl mb-3"></div>
            <h3 class="text-xl font-bold mb-2">Pour les ménages</h3>
            <p class="text-gray-600">Collecte à domicile, tri simplifié, points de fidélité</p>
        </div>

        <div class="bg-white p-6 rounded-lg shadow text-center">
            <div class="text-4xl mb-3"></div>
            <h3 class="text-xl font-bold mb-2">Pour les entreprises</h3>
            <p class="text-gray-600">Collecte professionnelle, volumes adaptés</p>
        </div>

        <div class="bg-white p-6 rounded-lg shadow text-center">
            <div class="text-4xl mb-3"></div>
            <h3 class="text-xl font-bold mb-2">Économie circulaire</h3>
            <p class="text-gray-600">Valorisation des déchets, récompenses à la clé</p>
        </div>
    </div>

    <div class="mt-16 bg-green-100 p-6 rounded-lg text-center">
        <h3 class="text-2xl font-bold text-green-800 mb-2">15 jours d'essai gratuit</h3>
        <p class="text-green-700">Kit de tri offert. Collecte à domicile. Puis 5000 FCFA/mois.</p>
    </div>
@endsection
