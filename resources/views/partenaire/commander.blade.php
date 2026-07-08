@extends('layouts.partenaire')

@section('title', 'Passer commande')

@section('content')
    <div class="bg-white rounded-xl shadow-soft p-6 max-w-2xl mx-auto">
        <h1 class="text-2xl font-bold text-gray-800 mb-4"> Passer commande</h1>

        <div class="bg-gray-50 p-4 rounded-lg mb-6">
            <p><strong>Collecte #{{ $collecte->id }}</strong></p>
            <p>Client : {{ $collecte->user->prenom }} {{ $collecte->user->nom }}</p>
            <p>Date : {{ $collecte->date_collecte->format('d/m/Y') }}</p>
            <p>Poids total :
                {{ number_format($collecte->poids_recyclable + $collecte->poids_organique + $collecte->poids_residuel, 1) }}
                kg</p>
            <div class="mt-2 text-sm">
                <span class="badge-success">Recyclable : {{ number_format($collecte->poids_recyclable, 1) }} kg</span>
                <span class="badge-warning">Organique : {{ number_format($collecte->poids_organique, 1) }} kg</span>
            </div>
        </div>

        <form method="POST" action="{{ route('partenaire.commander.store') }}">
            @csrf
            <input type="hidden" name="collecte_id" value="{{ $collecte->id }}">

            <div class="mb-4">
                <label class="block text-gray-700">Quantité (kg)</label>
                <input type="number" step="0.01" name="quantite"
                    class="w-full border rounded-lg p-2 focus:ring-2 focus:ring-emerald-500" required>
                <p class="text-xs text-gray-500 mt-1">Maximum :
                    {{ number_format($collecte->poids_recyclable + $collecte->poids_organique + $collecte->poids_residuel, 1) }}
                    kg</p>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700">Prix unitaire (FCFA/kg)</label>
                <input type="number" step="0.01" name="prix_unitaire"
                    class="w-full border rounded-lg p-2 focus:ring-2 focus:ring-emerald-500" required>
                <p class="text-xs text-gray-500 mt-1">Proposez un prix pour ce lot.</p>
            </div>

            <div class="flex gap-3">
                <button type="submit" class="btn-primary"> Confirmer la commande</button>
                <a href="{{ route('partenaire.offres') }}" class="btn-gray">Annuler</a>
            </div>
        </form>
    </div>
@endsection
