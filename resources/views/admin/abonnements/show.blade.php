@extends('layouts.admin')

@section('title', "Détail de l'abonnement #{$abonnement->id}")

@section('content')
    <div class="bg-white rounded-xl shadow-soft p-6 max-w-4xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">
                <i class="fas fa-crown text-emerald-500"></i> Abonnement #{{ $abonnement->id }}
            </h1>
            <a href="{{ route('admin.abonnements.index') }}" class="text-emerald-600 hover:underline">← Retour</a>
        </div>

        <div class="grid grid-cols-2 gap-6 mb-6">
            <div>
                <p class="text-gray-500 text-sm">Utilisateur</p>
                <p class="font-semibold">{{ $abonnement->user->prenom ?? '' }} {{ $abonnement->user->nom ?? '' }}</p>
                <p class="text-sm text-gray-500">{{ $abonnement->user->email ?? '' }}</p>
            </div>
            <div class="text-right">
                <p class="text-gray-500 text-sm">Statut</p>
                <p class="font-semibold">
                    @if ($abonnement->estActif())
                        <span class="badge-success">✅ Actif</span>
                    @elseif($abonnement->estEnEssai())
                        <span class="badge-info">🆓 Essai</span>
                    @elseif($abonnement->statut === 'expire')
                        <span class="badge-danger">⏰ Expiré</span>
                    @else
                        <span class="badge-gray">⛔ Résilié</span>
                    @endif
                </p>
            </div>
        </div>

        <div class="grid grid-cols-3 gap-4 mb-6">
            <div class="bg-gray-50 p-3 rounded-lg text-center">
                <p class="text-gray-500 text-sm">Montant mensuel</p>
                <p class="font-bold text-emerald-600">{{ number_format($abonnement->montant_mensuel, 0, ',', ' ') }} FCFA
                </p>
            </div>
            <div class="bg-gray-50 p-3 rounded-lg text-center">
                <p class="text-gray-500 text-sm">Début essai</p>
                <p class="font-semibold">
                    {{ $abonnement->date_debut_essai ? $abonnement->date_debut_essai->format('d/m/Y') : '-' }}</p>
            </div>
            <div class="bg-gray-50 p-3 rounded-lg text-center">
                <p class="text-gray-500 text-sm">Fin essai</p>
                <p class="font-semibold">
                    {{ $abonnement->date_fin_essai ? $abonnement->date_fin_essai->format('d/m/Y') : '-' }}</p>
            </div>
        </div>

        <!-- Actions -->
        <div class="grid grid-cols-2 gap-4 mb-6">
            <!-- Activer / Résilier -->
            <div class="bg-emerald-50 p-4 rounded-lg">
                <h3 class="font-semibold text-gray-700 mb-2">🔧 Modifier le statut</h3>
                <div class="flex gap-2">
                    @if ($abonnement->statut !== 'actif' && $abonnement->statut !== 'resilie')
                        <form method="POST" action="{{ route('admin.abonnements.activer', $abonnement->id) }}">
                            @csrf
                            <button type="submit" class="btn-primary text-sm">✅ Activer</button>
                        </form>
                    @endif
                    @if ($abonnement->statut !== 'resilie' && $abonnement->statut !== 'expire')
                        <form method="POST" action="{{ route('admin.abonnements.resilier', $abonnement->id) }}"
                            onsubmit="return confirm('Confirmer ?')">
                            @csrf
                            <button type="submit" class="btn-danger text-sm">⛔ Résilier</button>
                        </form>
                    @endif
                </div>
            </div>

            <!-- Prolonger l'essai -->
            <div class="bg-blue-50 p-4 rounded-lg">
                <h3 class="font-semibold text-gray-700 mb-2">⏰ Prolonger l'essai</h3>
                <form method="POST" action="{{ route('admin.abonnements.prolonger', $abonnement->id) }}"
                    class="flex gap-2">
                    @csrf
                    <input type="number" name="jours" min="1" max="90" value="15"
                        class="border rounded-lg p-1 w-20 text-center focus:ring-2 focus:ring-emerald-500" required>
                    <button type="submit" class="btn-info text-sm">📅 Prolonger</button>
                </form>
            </div>
        </div>

        <!-- Modifier le montant -->
        <div class="bg-yellow-50 p-4 rounded-lg mb-6">
            <h3 class="font-semibold text-gray-700 mb-2">💰 Modifier le montant mensuel</h3>
            <form method="POST" action="{{ route('admin.abonnements.update', $abonnement->id) }}" class="flex gap-2">
                @csrf @method('PUT')
                <input type="number" name="montant_mensuel" min="1000" step="100"
                    value="{{ $abonnement->montant_mensuel }}"
                    class="border rounded-lg p-2 w-40 focus:ring-2 focus:ring-emerald-500" required>
                <button type="submit" class="btn-primary text-sm">💾 Mettre à jour</button>
            </form>
        </div>

        <!-- Historique des paiements -->
        <div class="border-t pt-4">
            <h3 class="font-semibold text-gray-700 mb-2"><i class="fas fa-history mr-2"></i> Historique des paiements</h3>
            <a href="{{ route('admin.abonnements.paiements', $abonnement->id) }}"
                class="text-emerald-600 hover:underline text-sm">
                Voir tous les paiements →
            </a>
        </div>
    </div>
@endsection
