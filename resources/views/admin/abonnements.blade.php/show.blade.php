@extends('layouts.admin')

@section('title', 'Détail de l\'abonnement #' . $abonnement->id)

@section('content')
    <div class="bg-white rounded-xl shadow-soft p-6 max-w-4xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">
                Abonnement #{{ $abonnement->id }}
            </h1>
            <a href="{{ route('admin.abonnements.index') }}" class="text-emerald-600 hover:underline">
                ← Retour
            </a>
        </div>

        <!-- Informations utilisateur -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
            <div class="bg-gray-50 p-4 rounded-lg">
                <p class="text-gray-500 text-sm">Utilisateur</p>
                <p class="font-semibold text-lg">{{ $abonnement->user->prenom ?? '' }} {{ $abonnement->user->nom ?? '' }}</p>
                <p class="text-sm text-gray-500">{{ $abonnement->user->email ?? '' }}</p>
                <p class="text-sm text-gray-500">{{ $abonnement->user->telephone ?? '' }}</p>
            </div>
            <div class="bg-gray-50 p-4 rounded-lg">
                <p class="text-gray-500 text-sm">Statut</p>
                <p class="font-semibold text-lg">
                    @if ($abonnement->statut == 'actif')
                        <span class="badge-success"> Actif</span>
                    @elseif($abonnement->statut == 'essai')
                        <span class="badge-info"> Essai</span>
                    @elseif($abonnement->statut == 'expire')
                        <span class="badge-danger"> Expiré</span>
                    @else
                        <span class="badge-gray"> Résilié</span>
                    @endif
                </p>
                <p class="text-sm text-gray-500">Montant mensuel :
                    <strong>{{ number_format($abonnement->montant_mensuel, 0) }} FCFA</strong>
                </p>
                <p class="text-sm text-gray-500">Créé le : {{ $abonnement->created_at->format('d/m/Y H:i') }}</p>
            </div>
        </div>

        <!-- Dates -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
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
            <div class="bg-gray-50 p-3 rounded-lg text-center">
                <p class="text-gray-500 text-sm">Début abonnement</p>
                <p class="font-semibold">
                    {{ $abonnement->date_debut_abonnement ? $abonnement->date_debut_abonnement->format('d/m/Y') : '-' }}
                </p>
            </div>
        </div>

        <!-- Formulaire de mise à jour -->
        <div class="border-t pt-6 mb-6">
            <h3 class="font-semibold text-gray-700 mb-4">Modifier le statut</h3>
            <form method="POST" action="{{ route('admin.abonnements.update', $abonnement->id) }}">
                @csrf
                @method('PUT')
                <div class="flex flex-wrap gap-4 items-end">
                    <div class="flex-1 min-w-[200px]">
                        <select name="statut" class="w-full border rounded-lg p-2 focus:ring-2 focus:ring-emerald-500">
                            <option value="essai" {{ $abonnement->statut == 'essai' ? 'selected' : '' }}> Essai</option>
                            <option value="actif" {{ $abonnement->statut == 'actif' ? 'selected' : '' }}> Actif</option>
                            <option value="expire" {{ $abonnement->statut == 'expire' ? 'selected' : '' }}> Expiré</option>
                            <option value="resilie" {{ $abonnement->statut == 'resilie' ? 'selected' : '' }}> Résilié
                            </option>
                        </select>
                    </div>
                    <button type="submit" class="btn-primary">
                        <i class="fas fa-save mr-1"></i> Mettre à jour
                    </button>
                </div>
            </form>
        </div>

        <!-- Historique des paiements -->
        <div class="border-t pt-6">
            <h3 class="font-semibold text-gray-700 mb-4"> Historique des paiements</h3>
            @if ($abonnement->paiements->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b">
                                <th class="text-left py-2 text-sm">Date</th>
                                <th class="text-left py-2 text-sm">Montant</th>
                                <th class="text-left py-2 text-sm">Mode</th>
                                <th class="text-left py-2 text-sm">Référence</th>
                                <th class="text-left py-2 text-sm">Statut</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($abonnement->paiements as $paiement)
                                <tr class="border-b hover:bg-gray-50">
                                    <td class="py-2 text-sm">{{ $paiement->date_paiement->format('d/m/Y H:i') }}</td>
                                    <td class="py-2 text-sm font-semibold text-emerald-600">
                                        {{ number_format($paiement->montant, 0) }} FCFA</td>
                                    <td class="py-2 text-sm capitalize">{{ $paiement->mode_paiement }}</td>
                                    <td class="py-2 text-sm font-mono">{{ $paiement->reference }}</td>
                                    <td class="py-2 text-sm">
                                        @if ($paiement->statut == 'valide')
                                            <span class="badge-success"> Validé</span>
                                        @elseif($paiement->statut == 'en_attente')
                                            <span class="badge-warning"> En attente</span>
                                        @else
                                            <span class="badge-danger"> Échoué</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-4 text-gray-500">
                    <p>Aucun paiement enregistré pour cet abonnement.</p>
                </div>
            @endif
        </div>
    </div>
@endsection
