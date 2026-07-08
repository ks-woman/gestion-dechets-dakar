@extends('layouts.admin')

@section('title', 'Modifier une catégorie')

@section('content')
    <div class="bg-white rounded-xl shadow-soft p-6 max-w-2xl mx-auto">
        <h1 class="text-2xl font-bold text-gray-800 mb-4">Modifier la catégorie</h1>

        <form method="POST" action="{{ route('admin.categories.update', $categorie->id) }}">
            @csrf @method('PUT')

            <div class="mb-4">
                <label class="block text-gray-700">Nom de la catégorie</label>
                <input type="text" name="nom" value="{{ old('nom', $categorie->nom) }}"
                    class="w-full border rounded-lg p-2 focus:ring-2 focus:ring-emerald-500" required>
            </div>

            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-gray-700">Icône (emoji)</label>
                    <input type="text" name="icone" value="{{ old('icone', $categorie->icone) }}"
                        class="w-full border rounded-lg p-2 focus:ring-2 focus:ring-emerald-500" placeholder="Ex: ">
                </div>
                <div>
                    <label class="block text-gray-700">Couleur (classe Tailwind)</label>
                    <input type="text" name="couleur" value="{{ old('couleur', $categorie->couleur) }}"
                        class="w-full border rounded-lg p-2 focus:ring-2 focus:ring-emerald-500" placeholder="Ex: blue-500">
                </div>
            </div>

            <div class="mb-4">
                <label class="flex items-center gap-2">
                    <input type="checkbox" name="est_actif" value="1" {{ $categorie->est_actif ? 'checked' : '' }}>
                    <span>Actif</span>
                </label>
            </div>

            <div class="flex gap-3">
                <button type="submit" class="btn-primary">Mettre à jour</button>
                <a href="{{ route('admin.categories.index') }}" class="btn-gray">Annuler</a>
            </div>
        </form>
    </div>
@endsection
