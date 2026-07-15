@extends('layouts.menage')

@section('title', 'Tableau de bord')

@section('content')
    <div class="space-y-6">
        <div class="bg-gradient-to-r from-emerald-600 to-emerald-800 rounded-xl shadow-soft p-6 text-white">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-bold">Bonjour {{ auth()->user()->prenom }} !</h1>
                    <p class="text-emerald-100 mt-1">Bienvenue sur votre espace de gestion des déchets</p>
                </div>
                <div class="text-5xl"></div>
            </div>
        </div>

        <!-- Kit de tri -->
        @if (auth()->user()->kitTri)
            @php
                $kit = auth()->user()->kitTri;
                // 🔧 Si url_qr est nul, on construit l'URL à partir du code
$qrUrl = $kit->url_qr ?? route('collecteur.activer-kit.par-scan', ['code' => $kit->code_qr]);
                $qrCode = \SimpleSoftwareIO\QrCode\Facades\QrCode::size(200)->generate($qrUrl);
            @endphp
            <div
                class="bg-white rounded-xl shadow-soft p-6 border-l-4 @if ($kit->statut == 'en_attente') border-yellow-500 @elseif($kit->statut == 'actif') border-emerald-500 @else border-gray-400 @endif">
                <div class="flex justify-between items-start">
                    <div>
                        <h3 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                            Mon kit de tri
                            <span
                                class="text-xs font-normal px-3 py-1 rounded-full @if ($kit->statut == 'en_attente') bg-yellow-100 text-yellow-800 @elseif($kit->statut == 'actif') bg-emerald-100 text-emerald-700 @else bg-gray-100 text-gray-600 @endif">
                                @if ($kit->statut == 'en_attente')
                                    En attente
                                @elseif($kit->statut == 'actif')
                                    Actif
                                @else
                                    {{ $kit->statut }}
                                @endif
                            </span>
                        </h3>
                        <p class="text-sm text-gray-600 mt-1"><strong>Type :</strong>
                            {{ ucfirst($kit->type_kit ?? 'Standard') }}</p>
                        <p class="text-sm text-gray-600"><strong>Code :</strong> <span
                                class="font-mono text-xs bg-gray-100 px-2 py-1 rounded">{{ $kit->code_qr }}</span></p>
                        @if ($kit->date_distribution)
                            <p class="text-sm text-gray-600"><strong>Livré le :</strong>
                                {{ \Carbon\Carbon::parse($kit->date_distribution)->format('d/m/Y') }}</p>
                        @endif
                    </div>
                    @if ($qrCode)
                        <div class="bg-white p-2 rounded-lg shadow-sm border">{!! $qrCode !!}</div>
                    @endif
                </div>
                @if ($kit->statut == 'en_attente')
                    <div class="mt-3 bg-yellow-50 border border-yellow-200 rounded-lg p-3 flex items-start gap-2">
                        <span class="text-yellow-600"></span>
                        <div>
                            <p class="text-sm text-yellow-800 font-medium">Votre kit est en préparation</p>
                            <p class="text-xs text-yellow-600">Un collecteur vous contactera pour la livraison.</p>
                        </div>
                    </div>
                @elseif($kit->statut == 'actif')
                    <div class="mt-3 bg-emerald-50 border border-emerald-200 rounded-lg p-3 flex items-start gap-2">
                        <span class="text-emerald-600"></span>
                        <div>
                            <p class="text-sm text-emerald-800 font-medium">Votre kit est actif !</p>
                            <p class="text-xs text-emerald-600">Vous pouvez maintenant trier vos déchets et demander des
                                collectes.</p>
                        </div>
                    </div>
                @endif
            </div>
        @else
            <!-- ... message si pas de kit ... -->
        @endif

        <!-- Mode d'emploi -->
        @if (auth()->user()->kitTri && auth()->user()->kitTri->statut == 'actif')
            <div class="bg-white rounded-xl shadow-soft p-6 border-l-4 border-emerald-500">
                <div class="flex justify-between items-start">
                    <div>
                        <h3 class="text-lg font-bold text-gray-800 flex items-center gap-2">📖 Mode d'emploi <span
                                class="badge-success">Guide de tri</span></h3>
                        <p class="text-sm text-gray-500">Déposez vos déchets dans le bon compartiment.</p>
                    </div>
                    <button onclick="toggleModeEmploi()"
                        class="text-emerald-600 hover:text-emerald-800 text-sm font-medium">Voir / Cacher</button>
                </div>
                <div id="modeEmploiContent" class="mt-4 hidden">
                    <div class="flex flex-col md:flex-row gap-6 items-center">
                        <div class="flex-1"><img src="{{ asset('images/prototypePoub.jpeg') }}" alt="Prototype"
                                class="w-full max-w-sm rounded-lg shadow-md border"></div>
                        <div class="flex-1 space-y-3">
                            <div class="flex items-center gap-3 p-2 bg-blue-50 rounded-lg border-l-4 border-blue-500">
                                <span class="text-2xl"></span>
                                <div>
                                    <p class="font-semibold text-blue-800">Plastiques & Métaux</p>
                                    <p class="text-sm text-gray-600">Bouteilles, canettes</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-3 p-2 bg-green-50 rounded-lg border-l-4 border-green-500">
                                <span class="text-2xl"></span>
                                <div>
                                    <p class="font-semibold text-green-800">Organiques</p>
                                    <p class="text-sm text-gray-600">Restes alimentaires</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-3 p-2 bg-yellow-50 rounded-lg border-l-4 border-yellow-500">
                                <span class="text-2xl"></span>
                                <div>
                                    <p class="font-semibold text-yellow-800">Papiers & Cartons</p>
                                    <p class="text-sm text-gray-600">Journaux, cartons</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-3 p-2 bg-gray-50 rounded-lg border-l-4 border-gray-500">
                                <span class="text-2xl"></span>
                                <div>
                                    <p class="font-semibold text-gray-800">Autres déchets</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mt-4 bg-emerald-50 border border-emerald-200 rounded-lg p-3 text-sm text-emerald-700">
                        Astuce : Rincez les emballages avant de les jeter.
                    </div>
                </div>
            </div>
            <script>
                function toggleModeEmploi() {
                    document.getElementById('modeEmploiContent').classList.toggle('hidden');
                }
            </script>
        @endif


        <!-- Notifications -->
        <div class="bg-white rounded-xl shadow-soft p-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-lg font-semibold text-gray-800 flex items-center gap-2">
                    <i class="fas fa-bell text-emerald-500"></i> Notifications
                    @if (isset($nonLues) && $nonLues > 0)
                        <span class="bg-red-500 text-white text-xs font-medium px-2.5 py-0.5 rounded-full">
                            {{ $nonLues }} non lue(s)
                        </span>
                    @endif
                </h2>
                <a href="{{ route('notifications.index') }}"
                    class="text-emerald-600 hover:text-emerald-700 text-sm font-medium">
                    Voir toutes →
                </a>
            </div>
            @if (isset($notifications) && $notifications->count() > 0)
                <div class="space-y-2">
                    @foreach ($notifications->take(5) as $notification)
                        <a href="{{ $notification->lien ?? route('notifications.index') }}"
                            class="block hover:bg-gray-100 transition rounded-lg">
                            <div
                                class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg {{ $notification->est_lu ? '' : 'border-l-4 border-emerald-500' }}">
                                <div class="flex-1">
                                    <p class="text-sm font-medium">{{ $notification->titre }}</p>
                                    <p class="text-xs text-gray-500">{{ $notification->message }}</p>
                                    <p class="text-xs text-gray-400">{{ $notification->created_at->diffForHumans() }}</p>
                                </div>
                                @if (!$notification->est_lu)
                                    <span
                                        class="bg-emerald-100 text-emerald-800 text-xs px-2 py-0.5 rounded-full">Nouveau</span>
                                @endif
                            </div>
                        </a>
                    @endforeach
                </div>
            @else
                <div class="text-center py-4 text-gray-500">
                    <p>Aucune notification</p>
                </div>
            @endif
        </div>

        <!-- Stats -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="card">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-gray-500 text-sm">Points</p>
                        <p class="text-2xl font-bold text-emerald-600">{{ auth()->user()->score_total }}</p>
                        <p class="text-xs text-gray-400">Niveau {{ auth()->user()->niveau }}</p>
                    </div>
                    <i class="fas fa-star text-3xl text-emerald-500"></i>
                </div>
            </div>
            <div class="card">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-gray-500 text-sm">Statut</p>
                        <p class="text-xl font-bold text-emerald-600">
                            @if (auth()->user()->estEnPeriodeEssai())
                                Essai gratuit
                            @elseif(auth()->user()->estAbonneActif())
                                Abonné
                            @else
                                {{ auth()->user()->statut_compte }}
                            @endif
                        </p>
                    </div>
                    <i class="fas fa-chart-line text-3xl text-emerald-500"></i>
                </div>
            </div>
            <div class="card">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-gray-500 text-sm">Collectes</p>
                        <p class="text-2xl font-bold text-emerald-600">{{ $totalCollectes ?? 0 }}</p>
                    </div>
                    <i class="fas fa-truck text-3xl text-emerald-500"></i>
                </div>
            </div>
            <div class="card">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-gray-500 text-sm">Prochaine</p>
                        <p class="text-sm font-medium text-emerald-600">
                            @if (isset($prochaineCollecte))
                                {{ \Carbon\Carbon::parse($prochaineCollecte->date_collecte)->format('d/m/Y') }}
                            @else
                                Aucune
                            @endif
                        </p>
                    </div>
                    <i class="fas fa-calendar text-3xl text-emerald-500"></i>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
            @if (auth()->user()->kitTri && auth()->user()->kitTri->statut == 'actif')
                <a href="{{ route('collecte.demander') }}" class="btn-primary text-center"><i
                        class="fas fa-truck mr-2"></i> Demander</a>
            @else
                <a href="{{ route('kit.demander') }}" class="btn-primary text-center"><i class="fas fa-box mr-2"></i>
                    Activer</a>
            @endif
            <a href="{{ route('collectes') }}" class="btn-primary text-center"><i class="fas fa-history mr-2"></i>
                Historique</a>
            <a href="{{ route('statistiques') }}" class="btn-primary text-center"><i class="fas fa-chart-bar mr-2"></i>
                Statistiques</a>
            <a href="{{ route('menage.preferences') }}" class="btn-primary text-center"><i
                    class="fas fa-sliders-h mr-2"></i> Préférences</a>
        </div>

        <!-- Dernières collectes -->
        <div class="bg-white rounded-xl shadow-soft p-6">
            <h2 class="text-lg font-semibold text-gray-800 flex items-center gap-2">
                <i class="fas fa-clock text-emerald-500"></i> Dernières collectes
            </h2>
            @if (isset($collectes) && $collectes->count() > 0)
                @foreach ($collectes as $collecte)
                    <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg mb-2">
                        <div>
                            <p class="font-medium">{{ \Carbon\Carbon::parse($collecte->date_collecte)->format('d/m/Y') }}
                            </p>
                            <p class="text-sm text-gray-500">
                                {{ $collecte->poids_recyclable + $collecte->poids_organique + $collecte->poids_residuel }}
                                kg</p>
                        </div>
                        <div class="text-right">
                            <span class="badge-success">+{{ $collecte->points_obtenus }} pts</span>
                            @if ($collecte->statut == 'realisee')
                                <p class="text-xs text-emerald-600"> Réalisée</p>
                            @endif
                        </div>
                    </div>
                @endforeach
            @else
                <div class="text-center py-8 text-gray-500">
                    <i class="fas fa-box-open text-4xl mb-2 block"></i>
                    <p>Aucune collecte</p>
                </div>
            @endif
        </div>
    </div>
@endsection
