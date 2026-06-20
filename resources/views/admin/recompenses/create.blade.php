@extends('layouts.admin')

@section('title', 'Ajouter une récompense')

@section('content')
    <div class="bg-white rounded-xl shadow-md p-6">
        <div class="flex items-center gap-3 mb-6">
            <i class="fas fa-gift text-2xl text-purple-500"></i>
            <h1 class="text-2xl font-bold text-gray-800">Ajouter une récompense</h1>
        </div>

        <form method="POST" action="{{ route('admin.recompenses.store') }}">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Nom -->
                <div class="md:col-span-2">
                    <label class="block text-gray-700 mb-2">Nom de la récompense <span class="text-red-500">*</span></label>
                    <input type="text" name="nom_recompense" value="{{ old('nom_recompense') }}"
                        class="w-full border rounded-lg p-2 focus:ring-2 focus:ring-purple-500" required>
                    @error('nom_recompense')
                        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Points requis -->
                <div>
                    <label class="block text-gray-700 mb-2">Points requis <span class="text-red-500">*</span></label>
                    <input type="number" name="points_requis" value="{{ old('points_requis') }}"
                        class="w-full border rounded-lg p-2 focus:ring-2 focus:ring-purple-500" min="1" required>
                    @error('points_requis')
                        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Type de récompense -->
                <div>
                    <label class="block text-gray-700 mb-2">Type <span class="text-red-500">*</span></label>
                    <select name="type_recompense" class="w-full border rounded-lg p-2 focus:ring-2 focus:ring-purple-500"
                        required>
                        <option value="">Sélectionner</option>
                        <option value="bon_achat" {{ old('type_recompense') == 'bon_achat' ? 'selected' : '' }}>🛒 Bon
                            d'achat</option>
                        <option value="article_physique"
                            {{ old('type_recompense') == 'article_physique' ? 'selected' : '' }}>📦 Article physique
                        </option>
                        <option value="reduction" {{ old('type_recompense') == 'reduction' ? 'selected' : '' }}>💰 Réduction
                        </option>
                        <option value="cadeau" {{ old('type_recompense') == 'cadeau' ? 'selected' : '' }}>🎁 Cadeau</option>
                    </select>
                    @error('type_recompense')
                        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Quantité disponible -->
                <div>
                    <label class="block text-gray-700 mb-2">Quantité disponible <span class="text-red-500">*</span></label>
                    <input type="number" name="quantite_disponible" value="{{ old('quantite_disponible', 0) }}"
                        class="w-full border rounded-lg p-2 focus:ring-2 focus:ring-purple-500" min="0" required>
                    @error('quantite_disponible')
                        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Valeur -->
                <div>
                    <label class="block text-gray-700 mb-2">Valeur (optionnel)</label>
                    <input type="number" step="0.01" name="valeur" value="{{ old('valeur') }}"
                        class="w-full border rounded-lg p-2 focus:ring-2 focus:ring-purple-500" min="0">
                    @error('valeur')
                        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Date d'expiration -->
                <div>
                    <label class="block text-gray-700 mb-2">Date d'expiration (optionnel)</label>
                    <input type="date" name="date_expiration" value="{{ old('date_expiration') }}"
                        class="w-full border rounded-lg p-2 focus:ring-2 focus:ring-purple-500">
                    @error('date_expiration')
                        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Description -->
                <div class="md:col-span-2">
                    <label class="block text-gray-700 mb-2">Description (optionnel)</label>
                    <textarea name="description" rows="3" class="w-full border rounded-lg p-2 focus:ring-2 focus:ring-purple-500">{{ old('description') }}</textarea>
                    @error('description')
                        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="mt-6 flex gap-3">
                <button type="submit" class="bg-purple-500 hover:bg-purple-600 text-white px-6 py-2 rounded-lg transition">
                    <i class="fas fa-save mr-2"></i> Enregistrer
                </button>
                <a href="{{ route('admin.recompenses.index') }}"
                    class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg transition">
                    <i class="fas fa-times mr-2"></i> Annuler
                </a>
            </div>
        </form>
    </div>
@endsection
