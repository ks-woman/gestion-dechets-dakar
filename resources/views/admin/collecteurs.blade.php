@extends('layouts.admin')

@section('title', 'Collecteurs et disponibilité')

@section('content')
    <div class="space-y-6">
        <!-- En-tête -->
        <div class="bg-white rounded-xl shadow-soft p-6 flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
                    <i class="fas fa-users text-emerald-500"></i> Gestion des collecteurs
                </h1>
                <p class="text-gray-500 mt-1">Visualisez en temps réel la disponibilité des collecteurs.</p>
            </div>
            <span class="text-sm text-gray-500">{{ now()->format('d/m/Y H:i') }}</span>
        </div>

        <!-- Légende -->
        <div class="flex gap-6 bg-white rounded-xl shadow-soft p-4">
            <div class="flex items-center gap-2">
                <span class="w-3 h-3 rounded-full bg-emerald-500"></span>
                <span class="text-sm">Disponible</span>
            </div>
            <div class="flex items-center gap-2">
                <span class="w-3 h-3 rounded-full bg-yellow-500"></span>
                <span class="text-sm">Partiellement disponible</span>
            </div>
            <div class="flex items-center gap-2">
                <span class="w-3 h-3 rounded-full bg-red-500"></span>
                <span class="text-sm">Occupé</span>
            </div>
        </div>

        <!-- Liste des collecteurs -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @forelse($collecteurs as $collecteur)
                @php
                    // Calculer les statistiques si elles ne sont pas déjà définies
                    if (!isset($collecteur->collectes_aujourdhui)) {
                        $collecteur->collectes_aujourdhui = \App\Models\Collecte::where(
                            'collecteur_id',
                            $collecteur->id,
                        )
                            ->whereDate('date_collecte', today())
                            ->count();
                    }
                    if (!isset($collecteur->commandes_en_cours)) {
                        $collecteur->commandes_en_cours = \App\Models\Commande::where('collecteur_id', $collecteur->id)
                            ->whereIn('statut', ['affectee', 'en_livraison'])
                            ->count();
                    }
                    if (!isset($collecteur->disponible)) {
                        $total = $collecteur->collectes_aujourdhui + $collecteur->commandes_en_cours;
                        $collecteur->disponible = $total == 0;
                        $collecteur->partiellement_disponible = $total > 0 && $total <= 3;
                    }
                @endphp

                <div
                    class="bg-white rounded-xl shadow-soft border p-4 hover:shadow-lg transition
                @if ($collecteur->disponible) border-emerald-300
                @elseif($collecteur->partiellement_disponible) border-yellow-300
                @else border-red-300 @endif">

                    <!-- En-tête de la carte -->
                    <div class="flex justify-between items-start">
                        <div>
                            <div class="flex items-center gap-2">
                                <div
                                    class="w-10 h-10 bg-emerald-100 rounded-full flex items-center justify-center text-emerald-600 font-bold">
                                    {{ isset($collecteur->user->prenom) ? substr($collecteur->user->prenom, 0, 1) : '?' }}{{ isset($collecteur->user->nom) ? substr($collecteur->user->nom, 0, 1) : '?' }}
                                </div>
                                <div>
                                    <p class="font-bold text-gray-800">{{ $collecteur->user->prenom ?? 'N/A' }}
                                        {{ $collecteur->user->nom ?? '' }}</p>
                                    <p class="text-xs text-gray-500">{{ $collecteur->user->email ?? '' }}</p>
                                </div>
                            </div>
                            <p class="text-sm text-gray-500 mt-1">
                                <i class="fas fa-phone mr-1"></i> {{ $collecteur->user->telephone ?? 'Non renseigné' }}
                            </p>
                            <p class="text-xs text-gray-400">
                                <i class="fas fa-id-card mr-1"></i> Matricule : {{ $collecteur->matricule ?? 'N/A' }}
                            </p>
                            @if (!empty($collecteur->zone_couverture))
                                <p class="text-xs text-gray-400">
                                    <i class="fas fa-map-marker-alt mr-1"></i> Zone : {{ $collecteur->zone_couverture }}
                                </p>
                            @endif
                        </div>
                        <span
                            class="px-2 py-1 rounded text-xs font-medium
                        @if ($collecteur->disponible) bg-emerald-100 text-emerald-800
                        @elseif($collecteur->partiellement_disponible) bg-yellow-100 text-yellow-800
                        @else bg-red-100 text-red-800 @endif">
                            @if ($collecteur->disponible)
                                <i class="fas fa-check-circle mr-1"></i> Disponible
                            @elseif($collecteur->partiellement_disponible)
                                <i class="fas fa-clock mr-1"></i> Partiel
                            @else
                                <i class="fas fa-times-circle mr-1"></i> Occupé
                            @endif
                        </span>
                    </div>

                    <!-- Statistiques -->
                    <div class="mt-3 grid grid-cols-2 gap-2 border-t pt-3">
                        <div class="bg-gray-50 p-2 rounded-lg text-center">
                            <p class="text-xs text-gray-500">Collectes aujourd'hui</p>
                            <p class="text-lg font-bold text-emerald-600">{{ $collecteur->collectes_aujourdhui ?? 0 }}</p>
                        </div>
                        <div class="bg-gray-50 p-2 rounded-lg text-center">
                            <p class="text-xs text-gray-500">Livraisons en cours</p>
                            <p class="text-lg font-bold text-blue-600">{{ $collecteur->commandes_en_cours ?? 0 }}</p>
                        </div>
                    </div>

                    <!-- Boutons d'action -->
                    <div class="mt-3 space-y-2">
                        @if (isset($collecteur->disponible) && $collecteur->disponible)
                            <a href="{{ route('admin.commandes.index', ['collecteur_id' => $collecteur->id]) }}"
                                class="btn-primary text-sm w-full text-center block">
                                <i class="fas fa-tasks mr-1"></i> Voir les commandes disponibles
                            </a>
                        @else
                            <a href="{{ route('admin.commandes.index') }}"
                                class="btn-gray text-sm w-full text-center block">
                                <i class="fas fa-arrow-right mr-1"></i> Voir toutes les commandes
                            </a>
                        @endif
                    </div>
                </div>
            @empty
                <div class="col-span-3 text-center py-12 text-gray-500">
                    <i class="fas fa-users-slash text-4xl mb-2 block"></i>
                    <p>Aucun collecteur enregistré.</p>
                </div>
            @endforelse
        </div>

        <!-- Statistiques globales -->
        <div class="bg-white rounded-xl shadow-soft p-6">
            <h3 class="font-semibold text-gray-800 mb-4"> Résumé</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                @php
                    $disponibles = $collecteurs
                        ->filter(function ($c) {
                            return isset($c->disponible) && $c->disponible;
                        })
                        ->count();
                    $partiels = $collecteurs
                        ->filter(function ($c) {
                            return isset($c->partiellement_disponible) && $c->partiellement_disponible;
                        })
                        ->count();
                    $occupes = $collecteurs
                        ->filter(function ($c) {
                            return (!isset($c->disponible) || !$c->disponible) &&
                                (!isset($c->partiellement_disponible) || !$c->partiellement_disponible);
                        })
                        ->count();
                @endphp
                <div class="flex items-center gap-3 p-3 bg-emerald-50 rounded-lg">
                    <span class="w-3 h-3 rounded-full bg-emerald-500"></span>
                    <div>
                        <p class="text-sm text-gray-500">Disponibles</p>
                        <p class="text-2xl font-bold text-emerald-600">{{ $disponibles }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-3 p-3 bg-yellow-50 rounded-lg">
                    <span class="w-3 h-3 rounded-full bg-yellow-500"></span>
                    <div>
                        <p class="text-sm text-gray-500">Partiellement disponibles</p>
                        <p class="text-2xl font-bold text-yellow-600">{{ $partiels }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-3 p-3 bg-red-50 rounded-lg">
                    <span class="w-3 h-3 rounded-full bg-red-500"></span>
                    <div>
                        <p class="text-sm text-gray-500">Occupés</p>
                        <p class="text-2xl font-bold text-red-600">{{ $occupes }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
