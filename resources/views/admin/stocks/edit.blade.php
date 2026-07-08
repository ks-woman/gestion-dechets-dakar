@extends('layouts.admin')

@section('title', 'Modifier le stock')

@section('content')
    <div class="bg-white rounded-xl shadow-soft p-6 max-w-2xl mx-auto">
        <h1 class="text-2xl font-bold text-gray-800 mb-4">Modifier le stock - {{ $stock->type }}</h1>

        <form method="POST" action="{{ route('admin.stocks.update', $stock->id) }}">
            @csrf @method('PUT')

            <div class="mb-4">
                <label class="block text-gray-700">Type</label>
                <p class="text-lg font-semibold capitalize text-emerald-600">{{ $stock->type }}</p>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700">Quantité (kg)</label>
                <input type="number" step="0.01" name="quantite" value="{{ old('quantite', $stock->quantite) }}"
                    class="w-full border rounded-lg p-2 focus:ring-2 focus:ring-emerald-500" required>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700">Prix unitaire (FCFA/kg)</label>
                <input type="number" step="0.01" name="prix_unitaire"
                    value="{{ old('prix_unitaire', $stock->prix_unitaire) }}"
                    class="w-full border rounded-lg p-2 focus:ring-2 focus:ring-emerald-500" required>
            </div>

            <div class="flex gap-3">
                <button type="submit" class="btn-primary">Mettre à jour</button>
                <a href="{{ route('admin.stocks.index') }}" class="btn-gray">Annuler</a>
            </div>
        </form>
    </div>
@endsection
