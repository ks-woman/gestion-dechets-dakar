@extends('layouts.admin')

@section('title', 'Modifier la zone')

@section('content')
    <div class="bg-white rounded-xl shadow-md p-6 max-w-2xl mx-auto">
        <h1 class="text-2xl font-bold text-gray-800 mb-4">Modifier la zone : {{ $zone->nom }}</h1>

        <form method="POST" action="{{ route('admin.zones.update', $zone->id) }}">
            @csrf @method('PUT')

            <div class="mb-4">
                <label class="block text-gray-700">Nom de la zone</label>
                <input type="text" name="nom" value="{{ old('nom', $zone->nom) }}" class="w-full border rounded-lg p-2"
                    required>
                @error('nom')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-4">
                <label class="block text-gray-700">Description (optionnelle)</label>
                <textarea name="description" rows="2" class="w-full border rounded-lg p-2">{{ old('description', $zone->description) }}</textarea>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700">Quartiers</label>
                <select name="quartiers[]" multiple class="w-full border rounded-lg p-2" required>
                    @foreach ($quartiersDisponibles as $quartier)
                        <option value="{{ $quartier }}"
                            {{ in_array($quartier, old('quartiers', $zone->quartiers ?? [])) ? 'selected' : '' }}>
                            {{ $quartier }}
                        </option>
                    @endforeach
                </select>
                <p class="text-xs text-gray-500 mt-1">Maintenez Ctrl (ou Cmd) pour sélectionner plusieurs quartiers.</p>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700">Collecteurs affectés</label>
                <select name="collecteurs[]" multiple class="w-full border rounded-lg p-2">
                    @foreach ($collecteurs as $collecteur)
                        @php
                            $selected = $zone->collecteurs->contains($collecteur->id) ? 'selected' : '';
                        @endphp
                        <option value="{{ $collecteur->id }}" {{ $selected }}>
                            {{ $collecteur->user->prenom ?? '' }} {{ $collecteur->user->nom ?? '' }}
                            ({{ $collecteur->matricule ?? '' }})
                        </option>
                    @endforeach
                </select>
                <p class="text-xs text-gray-500 mt-1">Laissez vide pour ne pas affecter de collecteur.</p>
            </div>

            <div class="flex gap-3">
                <button type="submit" class="bg-blue-500 text-white px-6 py-2 rounded-lg hover:bg-blue-600">Mettre à
                    jour</button>
                <a href="{{ route('admin.zones.index') }}"
                    class="bg-gray-500 text-white px-6 py-2 rounded-lg hover:bg-gray-600">Annuler</a>
            </div>
        </form>
    </div>
@endsection
