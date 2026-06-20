@extends('layouts.menage')

@section('title', 'Tableau de bord')

@section('content')
    <div class="space-y-6">
        <!-- Cartes de bienvenue -->
        <div class="bg-gradient-to-r from-emerald-600 to-emerald-800 rounded-xl shadow-lg p-6 text-white">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-bold">Bonjour {{ auth()->user()->prenom }} !</h1>
                    <p class="text-emerald-100 mt-1">Bienvenue sur votre espace de gestion des déchets</p>
                </div>
                <div class="text-5xl">♻️</div>
            </div>
        </div>

        <!-- ============================================ -->
        <!-- MON KIT DE TRI                               -->
        <!-- ============================================ -->
        @if (auth()->user()->kitTri)
            @php
                $kit = auth()->user()->kitTri;
                // Vérifier que le kit a un code QR avant de le générer
                $qrCode = $kit->code_qr
                    ? \SimpleSoftwareIO\QrCode\Facades\QrCode::size(200)->generate($kit->code_qr)
                    : null;
            @endphp

            <div
                class="bg-white rounded-xl shadow p-6 border-l-4
                @if ($kit->statut == 'en_attente') border-yellow-500
                @elseif($kit->statut == 'actif') border-green-500
                @else border-gray-400 @endif">

                <div class="flex justify-between items-start">
                    <div>
                        <h3 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                            📦 Mon kit de tri
                            <span
                                class="text-xs font-normal px-2 py-1 rounded-full
                                @if ($kit->statut == 'en_attente') bg-yellow-100 text-yellow-800
                                @elseif($kit->statut == 'actif') bg-green-100 text-green-800
                                @else bg-gray-100 text-gray-800 @endif">
                                @if ($kit->statut == 'en_attente')
                                    ⏳ En attente de livraison
                                @elseif($kit->statut == 'actif')
                                    ✅ Actif
                                @else
                                    {{ $kit->statut }}
                                @endif
                            </span>
                        </h3>
                        <p class="text-sm text-gray-500 mt-1">
                            <strong>Type :</strong> {{ ucfirst($kit->type_kit ?? 'Standard') }}
                        </p>
                        <p class="text-sm text-gray-500">
                            <strong>Code :</strong> <span
                                class="font-mono text-xs bg-gray-100 px-2 py-1 rounded">{{ $kit->code_qr }}</span>
                        </p>
                        @if ($kit->date_distribution)
                            <p class="text-sm text-gray-500">
                                <strong>Livré le :</strong>
                                {{ \Carbon\Carbon::parse($kit->date_distribution)->format('d/m/Y') }}
                            </p>
                        @endif
                    </div>

                    <!-- QR Code -->
                    @if ($qrCode)
                        <div class="flex flex-col items-center">
                            <div class="bg-white p-2 rounded-lg shadow-sm border">
                                {!! $qrCode !!}
                            </div>
                            @if ($kit->statut == 'en_attente')
                                <p class="text-xs text-gray-500 mt-1">📱 À scanner par le collecteur</p>
                            @endif
                        </div>
                    @endif
                </div>

                @if ($kit->statut == 'en_attente')
                    <div class="mt-3 bg-yellow-50 border border-yellow-200 rounded-lg p-3 flex items-start gap-2">
                        <span class="text-yellow-600 text-lg">⏳</span>
                        <div>
                            <p class="text-sm text-yellow-800 font-medium">Votre kit est en préparation</p>
                            <p class="text-xs text-yellow-600">Un collecteur vous contactera pour la livraison.
                                Montrez-lui le QR code ci-dessus pour activer votre kit.</p>
                        </div>
                    </div>
                @elseif($kit->statut == 'actif')
                    <div class="mt-3 bg-green-50 border border-green-200 rounded-lg p-3 flex items-start gap-2">
                        <span class="text-green-600 text-lg">✅</span>
                        <div>
                            <p class="text-sm text-green-800 font-medium">Votre kit est actif !</p>
                            <p class="text-xs text-green-600">Vous pouvez maintenant trier vos déchets et demander des
                                collectes.</p>
                        </div>
                    </div>
                @endif
            </div>
        @else
            <!-- Si l'utilisateur n'a pas encore de kit -->
            <div class="bg-white rounded-xl shadow p-6 border-l-4 border-gray-300">
                <div class="flex justify-between items-center">
                    <div>
                        <h3 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                            📦 Mon kit de tri
                            <span class="text-xs font-normal bg-gray-100 text-gray-600 px-2 py-1 rounded-full">Non
                                demandé</span>
                        </h3>
                        <p class="text-sm text-gray-500 mt-1">Vous n'avez pas encore demandé votre kit de tri.</p>
                    </div>
                    <a href="{{ route('kit.demander') }}"
                        class="bg-emerald-500 hover:bg-emerald-600 text-white px-4 py-2 rounded-lg text-sm transition flex items-center gap-2">
                        <i class="fas fa-plus"></i> Demander mon kit
                    </a>
                </div>
            </div>
        @endif

        <!-- ============================================ -->
        <!-- MODE D'EMPLOI DU KIT                         -->
        <!-- ============================================ -->
        @if (auth()->user()->kitTri && auth()->user()->kitTri->statut == 'actif')
            <div class="bg-white rounded-xl shadow p-6 border-l-4 border-emerald-500">
                <div class="flex justify-between items-start">
                    <div>
                        <h3 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                            📖 Mode d'emploi du kit
                            <span class="text-xs font-normal bg-emerald-100 text-emerald-800 px-2 py-1 rounded-full">Guide
                                de tri</span>
                        </h3>
                        <p class="text-sm text-gray-500 mt-1">Déposez vos déchets dans le bon compartiment.</p>
                    </div>
                    <button onclick="toggleModeEmploi()"
                        class="text-emerald-600 hover:text-emerald-800 text-sm font-medium">
                        Voir / Cacher
                    </button>
                </div>

                <div id="modeEmploiContent" class="mt-4 hidden">
                    <div class="flex flex-col md:flex-row gap-6 items-center">
                        <!-- Image du prototype -->
                        <div class="flex-1">
                            <img src="{{ asset('images/prototypePoub.jpeg') }}" alt="Prototype du kit de tri"
                                class="w-full max-w-sm rounded-lg shadow-md border">
                        </div>
                        <!-- Légendes -->
                        <div class="flex-1 space-y-3">
                            <div class="flex items-center gap-3 p-2 bg-blue-50 rounded-lg border-l-4 border-blue-500">
                                <span class="text-2xl">🥫</span>
                                <div>
                                    <p class="font-semibold text-blue-800">Plastiques & Métaux</p>
                                    <p class="text-sm text-gray-600">Bouteilles, canettes, emballages</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-3 p-2 bg-green-50 rounded-lg border-l-4 border-green-500">
                                <span class="text-2xl">🍎</span>
                                <div>
                                    <p class="font-semibold text-green-800">Déchets organiques</p>
                                    <p class="text-sm text-gray-600">Restes alimentaires, épluchures</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-3 p-2 bg-yellow-50 rounded-lg border-l-4 border-yellow-500">
                                <span class="text-2xl">📄</span>
                                <div>
                                    <p class="font-semibold text-yellow-800">Papiers & Cartons</p>
                                    <p class="text-sm text-gray-600">Journaux, cartons, cahiers</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-3 p-2 bg-gray-50 rounded-lg border-l-4 border-gray-500">
                                <span class="text-2xl">🗑️</span>
                                <div>
                                    <p class="font-semibold text-gray-800">Autres déchets</p>
                                    <p class="text-sm text-gray-600">Déchets non recyclables</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4 bg-emerald-50 border border-emerald-200 rounded-lg p-3 text-sm text-emerald-800">
                        💡 Astuce : Rincez les emballages avant de les jeter pour faciliter le recyclage.
                    </div>
                </div>
            </div>

            <script>
                function toggleModeEmploi() {
                    const content = document.getElementById('modeEmploiContent');
                    content.classList.toggle('hidden');
                }
            </script>
        @endif

        <!-- ============================================ -->
        <!-- CARTES STATISTIQUES                          -->
        <!-- ============================================ -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="bg-white rounded-xl shadow p-4 border-l-4 border-yellow-500">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-gray-500 text-sm">Mes points</p>
                        <p class="text-2xl font-bold">{{ auth()->user()->score_total }}</p>
                        <p class="text-xs text-gray-400">Niveau {{ auth()->user()->niveau }}</p>
                    </div>
                    <i class="fas fa-star text-2xl text-yellow-500"></i>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow p-4 border-l-4 border-blue-500">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-gray-500 text-sm">Statut</p>
                        <p class="text-xl font-bold">
                            @if (auth()->user()->estEnPeriodeEssai())
                                <span class="text-blue-600">Essai gratuit</span>
                            @elseif(auth()->user()->estAbonneActif())
                                <span class="text-green-600">Abonné actif</span>
                            @else
                                <span class="text-gray-600">{{ auth()->user()->statut_compte }}</span>
                            @endif
                        </p>
                    </div>
                    <i class="fas fa-chart-line text-2xl text-blue-500"></i>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow p-4 border-l-4 border-green-500">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-gray-500 text-sm">Collectes</p>
                        <p class="text-2xl font-bold">{{ $totalCollectes ?? 0 }}</p>
                    </div>
                    <i class="fas fa-truck text-2xl text-green-500"></i>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow p-4 border-l-4 border-purple-500">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-gray-500 text-sm">Prochaine collecte</p>
                        <p class="text-sm font-medium">
                            @if (isset($prochaineCollecte))
                                {{ \Carbon\Carbon::parse($prochaineCollecte->date_collecte)->format('d/m/Y') }}
                            @else
                                Aucune planifiée
                            @endif
                        </p>
                    </div>
                    <i class="fas fa-calendar text-2xl text-purple-500"></i>
                </div>
            </div>
        </div>

        <!-- ============================================ -->
        <!-- ACTIONS RAPIDES                             -->
        <!-- ============================================ -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
            @if (auth()->user()->kitTri && auth()->user()->kitTri->statut == 'actif')
                <a href="{{ route('collecte.demander') }}"
                    class="bg-blue-500 hover:bg-blue-600 text-white p-3 rounded-lg text-center transition">
                    <i class="fas fa-truck mr-2"></i> Demander collecte
                </a>
            @else
                <a href="{{ route('kit.demander') }}"
                    class="bg-emerald-500 hover:bg-emerald-600 text-white p-3 rounded-lg text-center transition">
                    <i class="fas fa-box mr-2"></i> Activer mon kit
                </a>
            @endif
            <a href="{{ route('collectes') }}"
                class="bg-gray-500 hover:bg-gray-600 text-white p-3 rounded-lg text-center transition">
                <i class="fas fa-history mr-2"></i> Historique
            </a>
            <a href="{{ route('statistiques') }}"
                class="bg-purple-500 hover:bg-purple-600 text-white p-3 rounded-lg text-center transition">
                <i class="fas fa-chart-bar mr-2"></i> Statistiques
            </a>
            <a href="{{ route('menage.preferences') }}"
                class="bg-gray-500 hover:bg-gray-600 text-white p-3 rounded-lg text-center transition">
                <i class="fas fa-sliders-h mr-2"></i> Préférences
            </a>
        </div>

        <!-- ============================================ -->
        <!-- DERNIÈRES COLLECTES                         -->
        <!-- ============================================ -->
        <div class="bg-white rounded-xl shadow">
            <div class="p-5 border-b">
                <h2 class="text-lg font-semibold flex items-center gap-2">
                    <i class="fas fa-clock text-gray-500"></i> Dernières collectes
                </h2>
            </div>
            <div class="p-5">
                @if (isset($collectes) && $collectes->count() > 0)
                    <div class="space-y-3">
                        @foreach ($collectes as $collecte)
                            <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                                <div>
                                    <p class="font-medium">
                                        {{ \Carbon\Carbon::parse($collecte->date_collecte)->format('d/m/Y') }}</p>
                                    <p class="text-sm text-gray-500">
                                        {{ $collecte->poids_recyclable + $collecte->poids_organique + $collecte->poids_residuel }}
                                        kg</p>
                                </div>
                                <div class="text-right">
                                    <span
                                        class="bg-yellow-100 text-yellow-800 px-2 py-1 rounded text-sm">+{{ $collecte->points_obtenus }}
                                        pts</span>
                                    @if ($collecte->statut == 'realisee')
                                        <p class="text-xs text-green-600 mt-1">✅ Réalisée</p>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8 text-gray-500">
                        <i class="fas fa-box-open text-4xl mb-2 block"></i>
                        <p>Aucune collecte pour le moment</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
