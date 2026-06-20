@extends('layouts.partenaire')

@section('title', 'Tableau de bord')

@section('content')
    <div class="space-y-6">
        <!-- En-tête -->
        <div class="bg-gradient-to-r from-purple-600 to-purple-800 rounded-xl shadow-lg p-6 text-white">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-bold">Bonjour {{ auth()->user()->prenom }} !</h1>
                    <p class="text-purple-100 mt-1">Centre de valorisation - Tableau de bord</p>
                </div>
                <div class="text-5xl">♻️</div>
            </div>
        </div>

        <!-- Cartes statistiques -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="bg-white rounded-xl shadow p-4 border-l-4 border-blue-500">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-gray-500 text-sm">Collectes reçues</p>
                        <p class="text-2xl font-bold">{{ $totalCollectes ?? 0 }}</p>
                    </div>
                    <i class="fas fa-truck text-2xl text-blue-500"></i>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow p-4 border-l-4 border-green-500">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-gray-500 text-sm">Total valorisé</p>
                        <p class="text-2xl font-bold">{{ number_format($totalPoids, 0) }} kg</p>
                    </div>
                    <i class="fas fa-recycle text-2xl text-green-500"></i>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow p-4 border-l-4 border-yellow-500">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-gray-500 text-sm">Points générés</p>
                        <p class="text-2xl font-bold">{{ $totalPoints ?? 0 }}</p>
                    </div>
                    <i class="fas fa-star text-2xl text-yellow-500"></i>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow p-4 border-l-4 border-purple-500">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-gray-500 text-sm">En attente</p>
                        <p class="text-2xl font-bold">{{ $enAttente ?? 0 }}</p>
                    </div>
                    <i class="fas fa-clock text-2xl text-purple-500"></i>
                </div>
            </div>
        </div>

        <!-- Actions rapides -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <a href="{{ route('partenaire.dechets') }}"
                class="bg-blue-500 hover:bg-blue-600 text-white p-4 rounded-lg text-center transition">
                <i class="fas fa-boxes mr-2"></i> Voir les déchets reçus
            </a>
            <a href="{{ route('partenaire.statistiques') }}"
                class="bg-purple-500 hover:bg-purple-600 text-white p-4 rounded-lg text-center transition">
                <i class="fas fa-chart-line mr-2"></i> Voir les statistiques
            </a>
        </div>

        <!-- Dernières collectes reçues -->
        <div class="bg-white rounded-xl shadow">
            <div class="p-5 border-b">
                <h2 class="text-lg font-semibold flex items-center gap-2">
                    <i class="fas fa-history text-gray-500"></i> Dernières collectes reçues
                </h2>
            </div>
            <div class="p-5">
                @if (isset($dernieresCollectes) && $dernieresCollectes->count() > 0)
                    <div class="space-y-3">
                        @foreach ($dernieresCollectes as $collecte)
                            <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                                <div>
                                    <p class="font-medium">{{ $collecte->user->prenom }} {{ $collecte->user->nom }}</p>
                                    <p class="text-sm text-gray-500">{{ $collecte->adresse }}</p>
                                    <p class="text-xs text-gray-400">
                                        {{ \Carbon\Carbon::parse($collecte->date_collecte)->format('d/m/Y') }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="font-semibold text-green-600">
                                        {{ number_format($collecte->poids_recyclable + $collecte->poids_organique + $collecte->poids_residuel, 1) }}
                                        kg</p>
                                    <p class="text-xs text-gray-500">♻️ {{ $collecte->poids_recyclable }} kg | 🍎
                                        {{ $collecte->poids_organique }} kg</p>
                                    @if ($collecte->statut == 'valorisee')
                                        <span class="text-xs text-green-600">✅ Valorisee</span>
                                    @else
                                        <span class="text-xs text-yellow-600">⏳ En attente</span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8 text-gray-500">
                        <i class="fas fa-box-open text-4xl mb-2 block"></i>
                        <p>Aucune collecte reçue pour le moment</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
