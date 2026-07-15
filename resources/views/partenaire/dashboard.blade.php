@extends('layouts.partenaire')

@section('title', 'Tableau de bord')

@section('content')
    <div class="space-y-6">
        <!-- Bannière -->
        <div class="bg-gradient-to-r from-emerald-600 to-emerald-800 rounded-xl shadow-soft p-6 text-white">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-bold">Bonjour {{ auth()->user()->prenom }} !</h1>
                    <p class="text-emerald-100 mt-1">Centre de valorisation - Tableau de bord</p>
                </div>
                <div class="text-5xl"></div>
            </div>
        </div>

        <!-- Cartes statistiques -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="card">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-gray-500 text-sm">Collectes reçues</p>
                        <p class="text-2xl font-bold text-emerald-600">{{ $totalCollectes ?? 0 }}</p>
                    </div>
                    <i class="fas fa-truck text-3xl text-emerald-500"></i>
                </div>
            </div>
            <div class="card">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-gray-500 text-sm">Total valorisé</p>
                        <p class="text-2xl font-bold text-emerald-600">{{ number_format($totalPoids ?? 0, 0) }} kg</p>
                    </div>
                    <i class="fas fa-recycle text-3xl text-emerald-500"></i>
                </div>
            </div>
            <div class="card">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-gray-500 text-sm">Points générés</p>
                        <p class="text-2xl font-bold text-emerald-600">{{ $totalPoints ?? 0 }}</p>
                    </div>
                    <i class="fas fa-star text-3xl text-emerald-500"></i>
                </div>
            </div>
            <div class="card">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-gray-500 text-sm">En attente</p>
                        <p class="text-2xl font-bold text-emerald-600">{{ $enAttente ?? 0 }}</p>
                    </div>
                    <i class="fas fa-clock text-3xl text-emerald-500"></i>
                </div>
            </div>
        </div>

        <!-- Offres disponibles (rappel) -->
        <div class="bg-emerald-50 border border-emerald-200 rounded-xl p-4 text-center">
            <p class="text-emerald-800">
                <span class="font-bold">{{ $offresDisponibles ?? 0 }}</span> offre(s) disponible(s)
                <a href="{{ route('partenaire.offres') }}"
                    class="text-emerald-600 font-medium hover:underline ml-2">Consulter →</a>
            </p>
        </div>

        <!-- Actions rapides -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
            <a href="{{ route('partenaire.offres') }}" class="btn-primary text-center">
                <i class="fas fa-boxes mr-2"></i> Voir les offres
            </a>
            <a href="{{ route('partenaire.dechets') }}" class="btn-primary text-center">
                <i class="fas fa-boxes mr-2"></i> Déchets reçus
            </a>
            <a href="{{ route('partenaire.historique') }}" class="btn-primary text-center">
                <i class="fas fa-history mr-2"></i> Historique
            </a>
        </div>

        <!-- Dernières commandes -->
        <div class="bg-white rounded-xl shadow-soft p-6">
            <h2 class="text-lg font-semibold text-gray-800 flex items-center gap-2 mb-4">
                <i class="fas fa-shopping-cart text-emerald-500"></i> Dernières commandes
            </h2>
            @if (isset($dernieresCommandes) && $dernieresCommandes->count() > 0)
                <div class="space-y-3">
                    @foreach ($dernieresCommandes as $commande)
                        <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                            <div>
                                <p class="font-medium">Commande #{{ $commande->id }}</p>
                                <p class="text-sm text-gray-500">{{ $commande->collecte->user->prenom ?? '' }}
                                    {{ $commande->collecte->user->nom ?? '' }}</p>
                                <p class="text-xs text-gray-400">{{ $commande->created_at->format('d/m/Y') }}</p>
                            </div>
                            <div class="text-right">
                                <p class="font-semibold text-emerald-600">{{ number_format($commande->quantite, 1) }} kg
                                </p>
                                <p class="text-sm text-gray-500">{{ number_format($commande->montant_total, 0, ',', ' ') }}
                                    FCFA</p>
                                @if ($commande->statut == 'en_attente')
                                    <span class="badge-warning"> En attente</span>
                                @elseif($commande->statut == 'validee')
                                    <span class="badge-info"> Validée</span>
                                @elseif($commande->statut == 'livree')
                                    <span class="badge-success"> Livrée</span>
                                @else
                                    <span class="badge-danger"> Annulée</span>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8 text-gray-500">
                    <i class="fas fa-box-open text-4xl mb-2 block"></i>
                    <p>Aucune commande passée pour le moment.</p>
                    <a href="{{ route('partenaire.offres') }}" class="text-emerald-600 hover:underline">Voir les offres
                        →</a>
                </div>
            @endif
        </div>

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

    </div>
@endsection
