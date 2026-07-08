@extends('layouts.admin')

@section('title', 'Détail de la commande')

@section('content')
    <div class="bg-white rounded-xl shadow-soft p-6 max-w-3xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800"> Commande #{{ $commande->id }}</h1>
            <a href="{{ route('admin.commandes.index') }}" class="text-emerald-600 hover:underline">← Retour</a>
        </div>

        <div class="grid grid-cols-2 gap-4 mb-6">
            <div>
                <p class="text-gray-500 text-sm">Partenaire</p>
                <p class="font-semibold">{{ $commande->partenaire->nom }} {{ $commande->partenaire->prenom }}</p>
                <p class="text-sm text-gray-500">{{ $commande->partenaire->email }}</p>
            </div>
            <div class="text-right">
                <p class="text-gray-500 text-sm">Statut</p>
                <p class="font-semibold">
                    @if ($commande->statut == 'en_attente')
                        <span class="badge-warning"> En attente</span>
                    @elseif($commande->statut == 'validee')
                        <span class="badge-info"> Validée</span>
                    @elseif($commande->statut == 'livree')
                        <span class="badge-success"> Livrée</span>
                    @else
                        <span class="badge-danger"> Annulée</span>
                    @endif
                </p>
                <p class="text-sm text-gray-500">Créée le {{ $commande->created_at->format('d/m/Y H:i') }}</p>
                @if ($commande->date_livraison)
                    <p class="text-sm text-gray-500">Livrée le {{ $commande->date_livraison->format('d/m/Y') }}</p>
                @endif
            </div>
        </div>

        <div class="grid grid-cols-3 gap-4 mb-6">
            <div class="bg-gray-50 p-3 rounded-lg text-center">
                <p class="text-gray-500 text-sm">Type</p>
                <p class="font-semibold capitalize">{{ $commande->type_dechet }}</p>
            </div>
            <div class="bg-gray-50 p-3 rounded-lg text-center">
                <p class="text-gray-500 text-sm">Quantité</p>
                <p class="font-semibold">{{ number_format($commande->quantite, 1) }} kg</p>
            </div>
            <div class="bg-gray-50 p-3 rounded-lg text-center">
                <p class="text-gray-500 text-sm">Montant total</p>
                <p class="font-semibold text-emerald-600">{{ number_format($commande->montant_total, 0, ',', ' ') }} FCFA
                </p>
            </div>
        </div>

        <!-- Formulaire de mise à jour -->
        <form method="POST" action="{{ route('admin.commandes.update', $commande->id) }}">
            @csrf @method('PUT')

            <div class="flex gap-4 items-end">
                <div class="flex-1">
                    <label class="block text-gray-700 text-sm mb-1">Modifier le statut</label>
                    <select name="statut" class="w-full border rounded-lg p-2 focus:ring-2 focus:ring-emerald-500">
                        <option value="en_attente" {{ $commande->statut == 'en_attente' ? 'selected' : '' }}>En attente
                        </option>
                        <option value="validee" {{ $commande->statut == 'validee' ? 'selected' : '' }}>Validée</option>
                        <option value="livree" {{ $commande->statut == 'livree' ? 'selected' : '' }}>Livrée</option>
                        <option value="annulee" {{ $commande->statut == 'annulee' ? 'selected' : '' }}>Annulée</option>
                    </select>
                </div>
                <button type="submit" class="btn-primary">Mettre à jour</button>
            </div>
        </form>
    </div>
@endsection
