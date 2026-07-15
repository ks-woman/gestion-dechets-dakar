@extends('layouts.menage')

@section('title', 'Mode d\'emploi du kit')

@section('content')
    <div class="bg-white rounded-xl shadow p-6">
        <div class="text-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800"> Mode d’emploi du kit de tri</h1>
            <p class="text-gray-500 mt-1">Apprenez à utiliser votre kit pour trier efficacement vos déchets.</p>
        </div>

        <!-- Image du prototype -->
        <div class="flex justify-center mb-6">
            <img src="{{ asset('images/prototypePoub.jpeg') }}" alt="Prototype du kit de tri"
                class="w-full max-w-md rounded-lg shadow-md border">
        </div>

        <!-- Les 4 compartiments -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Plastiques & Métaux -->
            <div class="bg-blue-50 p-4 rounded-lg border-l-4 border-blue-500 shadow-sm">
                <div class="flex items-center gap-3 mb-2">
                    <span class="text-3xl"></span>
                    <h3 class="text-lg font-bold text-blue-800">Plastiques & Métaux</h3>
                </div>
                <p class="text-gray-700 text-sm">Déposez ici tous les emballages en plastique (bouteilles, flacons, sachets)
                    et les métaux (canettes, boîtes de conserve).</p>
                <ul class="mt-2 text-xs text-gray-600 list-disc list-inside">
                    <li>Rincez les emballages avant de les jeter</li>
                    <li>Enlevez les bouchons et les étiquettes si possible</li>
                    <li>Écrasez les bouteilles pour gagner de la place</li>
                </ul>
            </div>

            <!-- Déchets organiques -->
            <div class="bg-green-50 p-4 rounded-lg border-l-4 border-green-500 shadow-sm">
                <div class="flex items-center gap-3 mb-2">
                    <span class="text-3xl"></span>
                    <h3 class="text-lg font-bold text-green-800">Déchets organiques</h3>
                </div>
                <p class="text-gray-700 text-sm">Déposez ici les restes alimentaires : épluchures de fruits et légumes, marc
                    de café, sachets de thé, coquilles d’œufs, etc.</p>
                <ul class="mt-2 text-xs text-gray-600 list-disc list-inside">
                    <li>Évitez les viandes et les poissons (ils attirent les nuisibles)</li>
                    <li>Utilisez un bio-seau ou un sac compostable pour faciliter le transport</li>
                    <li>Videz régulièrement pour éviter les odeurs</li>
                </ul>
            </div>

            <!-- Papiers & Cartons -->
            <div class="bg-yellow-50 p-4 rounded-lg border-l-4 border-yellow-500 shadow-sm">
                <div class="flex items-center gap-3 mb-2">
                    <span class="text-3xl"></span>
                    <h3 class="text-lg font-bold text-yellow-800">Papiers & Cartons</h3>
                </div>
                <p class="text-gray-700 text-sm">Déposez ici les journaux, magazines, cahiers, feuilles, cartons, emballages
                    en papier.</p>
                <ul class="mt-2 text-xs text-gray-600 list-disc list-inside">
                    <li>Enlevez les agrafes et les spirales métalliques</li>
                    <li>Aplatissez les cartons pour gagner de la place</li>
                    <li>Ne déposez pas de papier souillé (serviettes en papier, mouchoirs)</li>
                </ul>
            </div>

            <!-- Autres déchets -->
            <div class="bg-gray-50 p-4 rounded-lg border-l-4 border-gray-500 shadow-sm">
                <div class="flex items-center gap-3 mb-2">
                    <span class="text-3xl"></span>
                    <h3 class="text-lg font-bold text-gray-800">Autres déchets</h3>
                </div>
                <p class="text-gray-700 text-sm">Déposez ici les déchets non recyclables : couches, cotons-tiges, objets en
                    plastique non recyclable, etc.</p>
                <ul class="mt-2 text-xs text-gray-600 list-disc list-inside">
                    <li>Ces déchets seront collectés et éliminés proprement</li>
                    <li>Réduisez leur volume en les compactant</li>
                    <li>Ne déposez pas de déchets dangereux (piles, produits chimiques)</li>
                </ul>
            </div>
        </div>

        <!-- Conseils généraux -->
        <div class="mt-6 bg-emerald-50 border border-emerald-200 rounded-lg p-4">
            <h3 class="font-bold text-emerald-800 flex items-center gap-2">
                Astuces pour un tri efficace
            </h3>
            <ul class="mt-2 text-sm text-emerald-700 list-disc list-inside space-y-1">
                <li><strong>Rincez</strong> les emballages pour éviter les mauvaises odeurs.</li>
                <li><strong>Compactez</strong> les déchets pour réduire le volume et faciliter le transport.</li>
                <li><strong>Utilisez</strong> le bon compartiment pour chaque type de déchet.</li>
                <li><strong>Videz</strong> régulièrement votre kit pour éviter les nuisibles.</li>
                <li><strong>Participez</strong> à la préservation de l’environnement et gagnez des points de fidélité !</li>
            </ul>
        </div>

        <!-- Lien vers la demande de collecte -->
        <div class="mt-6 text-center">
            <a href="{{ route('collecte.demander') }}"
                class="bg-emerald-500 hover:bg-emerald-600 text-white px-6 py-2 rounded-lg inline-block transition">
                Demander une collecte
            </a>
            <p class="text-xs text-gray-400 mt-2">N’attendez pas que votre kit déborde, demandez une collecte !</p>
        </div>
    </div>
@endsection
