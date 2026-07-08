@extends('layouts.admin')

@section('title', 'Modifier la zone')

@section('content')
    <div class="bg-white rounded-xl shadow-soft p-6 max-w-2xl mx-auto">
        <h1 class="text-2xl font-bold text-gray-800 mb-4">Modifier la zone : {{ $zone->nom }}</h1>

        <form method="POST" action="{{ route('admin.zones.update', $zone->id) }}">
            @csrf @method('PUT')
            <div class="mb-4">
                <label class="block text-gray-700">Nom de la zone</label>
                <input type="text" name="nom" value="{{ old('nom', $zone->nom) }}"
                    class="w-full border rounded-lg p-2 focus:ring-2 focus:ring-emerald-500" required>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700">Description (optionnelle)</label>
                <textarea name="description" rows="2" class="w-full border rounded-lg p-2 focus:ring-2 focus:ring-emerald-500">{{ old('description', $zone->description) }}</textarea>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700">Quartiers</label>
                <select name="quartiers[]" multiple class="w-full border rounded-lg p-2 focus:ring-2 focus:ring-emerald-500"
                    required>
                    @foreach ($quartiersDisponibles as $quartier)
                        <option value="{{ $quartier }}"
                            {{ in_array($quartier, old('quartiers', $zone->quartiers ?? [])) ? 'selected' : '' }}>
                            {{ $quartier }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700">Collecteurs affectés</label>
                <select name="collecteurs[]" multiple
                    class="w-full border rounded-lg p-2 focus:ring-2 focus:ring-emerald-500">
                    @foreach ($collecteurs as $collecteur)
                        <option value="{{ $collecteur->id }}"
                            {{ $zone->collecteurs->contains($collecteur->id) ? 'selected' : '' }}>
                            {{ $collecteur->user->prenom ?? '' }} {{ $collecteur->user->nom ?? '' }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="flex gap-3">
                <button type="submit" class="btn-primary">Mettre à jour</button>
                <a href="{{ route('admin.zones.index') }}" class="btn-gray">Annuler</a>
            </div>
        </form>
    </div>
@endsection
