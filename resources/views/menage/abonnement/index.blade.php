@extends('layouts.menage')

@section('title', 'Mon abonnement')

@section('content')
    <div class="space-y-6">
        <!-- En-tête -->
        <div class="bg-white rounded-xl shadow-soft p-6">
            <h1 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
                <i class="fas fa-crown text-emerald-500"></i> Mon abonnement
            </h1>
            <p class="text-gray-500 mt-1">Gérez votre abonnement et suivez vos paiements</p>
        </div>

        @if (session('success'))
            <div class="alert-success">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert-danger">{{ session('error') }}</div>
        @endif

        <!-- Carte principale selon statut -->
        @php
            $statusColor = 'gray';
            $statusBg = 'bg-gray-100';
            $statusText = 'text-gray-600';
            $statusIcon = '';
            $statusLabel = 'Aucun abonnement';

            if ($abonnement) {
                if ($abonnement->estActif()) {
                    $statusColor = 'emerald';
                    $statusBg = 'bg-emerald-50';
                    $statusText = 'text-emerald-700';
                    $statusIcon = '';
                    $statusLabel = 'Actif';
                } elseif ($abonnement->estEnEssai()) {
                    $statusColor = 'blue';
                    $statusBg = 'bg-blue-50';
                    $statusText = 'text-blue-700';
                    $statusIcon = '';
                    $statusLabel = 'Période d\'essai';
                } elseif ($abonnement->statut === 'expire') {
                    $statusColor = 'red';
                    $statusBg = 'bg-red-50';
                    $statusText = 'text-red-700';
                    $statusIcon = '';
                    $statusLabel = 'Expiré';
                } elseif ($abonnement->statut === 'resilie') {
                    $statusColor = 'gray';
                    $statusBg = 'bg-gray-100';
                    $statusText = 'text-gray-600';
                    $statusIcon = '';
                    $statusLabel = 'Résilié';
                }
            }
        @endphp

        <!-- Carte de statut -->
        <div class="rounded-xl shadow-soft overflow-hidden border border-{{ $statusColor }}-200 {{ $statusBg }}">
            <div class="p-6">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                    <div class="flex items-center gap-4">
                        <div class="text-5xl">{{ $statusIcon }}</div>
                        <div>
                            <p class="text-sm text-gray-500">Statut actuel</p>
                            <p class="text-2xl font-bold {{ $statusText }}">
                                {{ $statusLabel }}
                            </p>
                        </div>
                    </div>
                    <div class="text-right">
                        @if ($abonnement)
                            <p class="text-sm text-gray-500">Montant mensuel</p>
                            <p class="text-2xl font-bold text-emerald-600">
                                {{ number_format($abonnement->montant_mensuel, 0, ',', ' ') }} FCFA
                            </p>
                        @endif
                    </div>
                </div>

                @if ($abonnement && $abonnement->estEnEssai())
                    <!-- Barre de progression pour l'essai -->
                    @php
                        $totalJours = $abonnement->date_debut_essai->diffInDays($abonnement->date_fin_essai);
                        $joursRestants = now()->diffInDays($abonnement->date_fin_essai, false);
                        $pourcentage =
                            $totalJours > 0
                                ? max(0, min(100, (($totalJours - max(0, $joursRestants)) / $totalJours) * 100))
                                : 0;
                    @endphp
                    <div class="mt-4">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Progression de l'essai</span>
                            <span class="font-medium text-blue-600">{{ max(0, $joursRestants) }} jours restants</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2.5 mt-1">
                            <div class="bg-blue-600 h-2.5 rounded-full transition-all duration-500"
                                style="width: {{ $pourcentage }}%"></div>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Détails -->
        @if ($abonnement)
            <div class="bg-white rounded-xl shadow-soft p-6">
                <h3 class="font-semibold text-gray-700 mb-4 flex items-center gap-2">
                    <i class="fas fa-info-circle text-emerald-500"></i> Détails de l'abonnement
                </h3>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div class="bg-gray-50 p-3 rounded-lg text-center">
                        <p class="text-gray-500 text-xs">Début essai</p>
                        <p class="font-semibold text-sm">
                            {{ $abonnement->date_debut_essai ? $abonnement->date_debut_essai->format('d/m/Y') : '-' }}</p>
                    </div>
                    <div class="bg-gray-50 p-3 rounded-lg text-center">
                        <p class="text-gray-500 text-xs">Fin essai</p>
                        <p class="font-semibold text-sm">
                            {{ $abonnement->date_fin_essai ? $abonnement->date_fin_essai->format('d/m/Y') : '-' }}</p>
                    </div>
                    @if ($abonnement->date_debut_abonnement)
                        <div class="bg-gray-50 p-3 rounded-lg text-center">
                            <p class="text-gray-500 text-xs">Début abonnement</p>
                            <p class="font-semibold text-sm">{{ $abonnement->date_debut_abonnement->format('d/m/Y') }}</p>
                        </div>
                        <div class="bg-gray-50 p-3 rounded-lg text-center">
                            <p class="text-gray-500 text-xs">Prochain paiement</p>
                            <p class="font-semibold text-sm">
                                {{ $abonnement->date_debut_abonnement->addMonth()->format('d/m/Y') }}</p>
                        </div>
                    @endif
                </div>
            </div>
        @endif

        <!-- Avantages -->
        <div class="bg-white rounded-xl shadow-soft p-6">
            <h3 class="font-semibold text-gray-700 mb-4 flex items-center gap-2">
                <i class="fas fa-gem text-emerald-500"></i> Ce que vous offre votre abonnement
            </h3>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                <div class="bg-emerald-50 p-3 rounded-lg text-center">
                    <div class="text-2xl"></div>
                    <p class="text-sm font-semibold text-emerald-700">Collectes illimitées</p>
                </div>
                <div class="bg-blue-50 p-3 rounded-lg text-center">
                    <div class="text-2xl"></div>
                    <p class="text-sm font-semibold text-blue-700">Points bonus</p>
                </div>
                <div class="bg-yellow-50 p-3 rounded-lg text-center">
                    <div class="text-2xl"></div>
                    <p class="text-sm font-semibold text-yellow-700">Récompenses exclusives</p>
                </div>
                <div class="bg-purple-50 p-3 rounded-lg text-center">
                    <div class="text-2xl"></div>
                    <p class="text-sm font-semibold text-purple-700">Priorité de collecte</p>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="bg-white rounded-xl shadow-soft p-6">
            <div class="flex flex-wrap gap-3">
                @if (!$abonnement || (!$abonnement->estActif() && $abonnement->statut !== 'resilie'))
                    <form method="POST" action="{{ route('abonnement.souscrire') }}">
                        @csrf
                        <button type="submit" class="btn-primary">
                            <i class="fas fa-check mr-2"></i> Souscrire / Activer
                        </button>
                    </form>
                @endif

                @if ($abonnement && $abonnement->estActif())
                    <form method="POST" action="{{ route('abonnement.annuler') }}"
                        onsubmit="return confirm('Confirmer la résiliation ?')">
                        @csrf
                        <button type="submit" class="btn-danger">
                            <i class="fas fa-times mr-2"></i> Résilier
                        </button>
                    </form>
                @endif

                <a href="{{ route('abonnement.historique') }}" class="btn-secondary">
                    <i class="fas fa-history mr-2"></i> Historique des paiements
                </a>
            </div>
        </div>

        <!-- Derniers paiements -->
        @if (isset($paiements) && $paiements->count() > 0)
            <div class="bg-white rounded-xl shadow-soft p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="font-semibold text-gray-700 flex items-center gap-2">
                        <i class="fas fa-credit-card text-emerald-500"></i> Derniers paiements
                    </h3>
                    <a href="{{ route('abonnement.historique') }}" class="text-sm text-emerald-600 hover:underline">Voir
                        tout →</a>
                </div>
                <div class="space-y-2">
                    @foreach ($paiements as $paiement)
                        <div
                            class="flex justify-between items-center bg-gray-50 p-3 rounded-lg hover:bg-gray-100 transition">
                            <div class="flex items-center gap-3">
                                <div class="text-2xl">
                                    @if ($paiement->statut === 'valide')
                                    @elseif($paiement->statut === 'en_attente')
                                    @else
                                    @endif
                                </div>
                                <div>
                                    <p class="text-sm font-medium">{{ $paiement->date_paiement->format('d/m/Y') }}</p>
                                    <p class="text-xs text-gray-500">{{ $paiement->mode_paiement }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="font-bold text-emerald-600">{{ number_format($paiement->montant, 0, ',', ' ') }}
                                    FCFA</p>
                                <p class="text-xs text-gray-400">
                                    @if ($paiement->statut === 'valide')
                                        <span class="badge-success">Validé</span>
                                    @elseif($paiement->statut === 'en_attente')
                                        <span class="badge-warning">En attente</span>
                                    @else
                                        <span class="badge-danger">{{ ucfirst($paiement->statut) }}</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
@endsection
