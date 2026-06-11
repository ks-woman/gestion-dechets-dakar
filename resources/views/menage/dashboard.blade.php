@extends('layouts.menage')

@section('title', 'Tableau de bord')

@section('content')
    <!-- Cartes statistiques -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <!-- Carte points -->
        <div
            class="bg-gradient-to-r from-yellow-400 to-yellow-500 rounded-xl shadow-lg p-6 text-white hover:shadow-xl transition">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-sm opacity-90">Mes points</p>
                    <p class="text-3xl font-bold">{{ auth()->user()->score_total }}</p>
                    <p class="text-xs opacity-75 mt-1">Niveau {{ auth()->user()->niveau }}</p>
                </div>
                <div class="text-3xl"></div>
            </div>
            <div class="mt-4 h-2 bg-white/30 rounded-full overflow-hidden">
                <div class="h-full bg-white rounded-full" style="width: {{ min(100, auth()->user()->score_total / 6) }}%">
                </div>
            </div>
        </div>

        <!-- Carte statut compte -->
        <div
            class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl shadow-lg p-6 text-white hover:shadow-xl transition">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-sm opacity-90">Statut du compte</p>
                    <p class="text-xl font-bold">{{ auth()->user()->statut_compte }}</p>
                    @if (auth()->user()->estEnPeriodeEssai())
                        <p class="text-xs opacity-75 mt-1">Période d'essai</p>
                    @endif
                </div>
                <div class="text-3xl"></div>
            </div>
        </div>

        <!-- Carte total collectes -->
        <div
            class="bg-gradient-to-r from-emerald-500 to-emerald-600 rounded-xl shadow-lg p-6 text-white hover:shadow-xl transition">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-sm opacity-90">Total collectes</p>
                    <p class="text-3xl font-bold">{{ $totalCollectes ?? 0 }}</p>
                    <p class="text-xs opacity-75 mt-1">Depuis le début</p>
                </div>
                <div class="text-3xl"></div>
            </div>
        </div>
    </div>

    <!-- Dernières collectes -->
    <div class="bg-white rounded-xl shadow-lg">
        <div class="p-5 border-b">
            <h2 class="text-lg font-bold text-gray-800"> Dernières collectes</h2>
        </div>
        <div class="p-5">
            @if (isset($collectes) && $collectes->count() > 0)
                <div class="space-y-3">
                    @foreach ($collectes as $collecte)
                        <div
                            class="flex justify-between items-center p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                            <div>
                                <p class="font-medium text-gray-800">
                                    {{ \Carbon\Carbon::parse($collecte->date_collecte)->format('d/m/Y') }}</p>
                                <p class="text-sm text-gray-500">
                                    {{ $collecte->poids_recyclable + $collecte->poids_organique + $collecte->poids_residuel }}
                                    kg au total</p>
                            </div>
                            <div class="text-right">
                                <span class="bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full text-sm font-medium">
                                    +{{ $collecte->points_obtenus }} points
                                </span>
                                @if ($collecte->statut == 'realisee')
                                    <p class="text-xs text-green-600 mt-1">✅ Réalisée</p>
                                @elseif($collecte->statut == 'planifiee')
                                    <p class="text-xs text-blue-600 mt-1">⏳ Planifiée</p>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12 text-gray-500">
                    <div class="text-5xl mb-3"></div>
                    <p>Aucune collecte pour le moment</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Conseils -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-6">
        <div class="bg-white rounded-xl shadow p-4 text-center hover:shadow-md transition">
            <div class="text-3xl mb-2"></div>
            <p class="font-semibold text-gray-800">Kit offert</p>
            <p class="text-xs text-gray-500">À la première demande</p>
        </div>
        <div class="bg-white rounded-xl shadow p-4 text-center hover:shadow-md transition">
            <div class="text-3xl mb-2"></div>
            <p class="font-semibold text-gray-800">Tri à la source</p>
            <p class="text-xs text-gray-500">Recyclable & organique</p>
        </div>
        <div class="bg-white rounded-xl shadow p-4 text-center hover:shadow-md transition">
            <div class="text-3xl mb-2"></div>
            <p class="font-semibold text-gray-800">5000 FCFA/mois</p>
            <p class="text-xs text-gray-500">Après essai gratuit</p>
        </div>
        <div class="bg-white rounded-xl shadow p-4 text-center hover:shadow-md transition">
            <div class="text-3xl mb-2"></div>
            <p class="font-semibold text-gray-800">Récompenses</p>
            <p class="text-xs text-gray-500">Échangez vos points</p>
        </div>
    </div>

    <!-- Conseil écologique -->
    <div class="bg-gradient-to-r from-emerald-50 to-teal-50 rounded-xl shadow-lg mt-6 p-5 border border-emerald-200">
        <div class="flex items-start gap-4">
            <div class="text-3xl"></div>
            <div>
                <h3 class="font-semibold text-emerald-800">Conseil du jour</h3>
                <p class="text-sm text-emerald-700">Triez vos déchets recyclables (plastique, verre, papier) et organiques
                    (épluchures, restes) pour gagner plus de points. Chaque kg recyclé = 1 point !</p>
            </div>
        </div>
    </div>
@endsection
