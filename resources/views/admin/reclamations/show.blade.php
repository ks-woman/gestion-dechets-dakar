@extends('layouts.admin')

@section('title', 'Détail de la réclamation')

@section('content')
    <div class="bg-white rounded-xl shadow-soft p-6 max-w-3xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800"> Détail de la réclamation #{{ $reclamation->id }}</h1>
            <a href="{{ route('admin.reclamations.index') }}" class="text-emerald-600 hover:underline">← Retour</a>
        </div>

        <!-- Infos utilisateur -->
        <div class="grid grid-cols-2 gap-4 mb-6">
            <div>
                <p class="text-gray-500 text-sm">Utilisateur</p>
                <p class="font-semibold">{{ $reclamation->user->prenom }} {{ $reclamation->user->nom }}</p>
                <p class="text-sm text-gray-500">{{ $reclamation->user->email }}</p>
                <p class="text-sm text-gray-500"> {{ $reclamation->user->telephone }}</p>
            </div>
            <div class="text-right">
                <p class="text-gray-500 text-sm">Statut</p>
                <p class="font-semibold">
                    @if ($reclamation->statut == 'ouverte')
                        <span class="badge-danger"> Ouverte</span>
                    @elseif($reclamation->statut == 'en_cours')
                        <span class="badge-warning"> En cours</span>
                    @elseif($reclamation->statut == 'resolue')
                        <span class="badge-success"> Résolue</span>
                    @else
                        <span class="badge-gray"> Fermée</span>
                    @endif
                </p>
                <p class="text-sm text-gray-500">Créée le {{ $reclamation->created_at->format('d/m/Y H:i') }}</p>
                @if ($reclamation->date_resolution)
                    <p class="text-sm text-gray-500">Résolue le {{ $reclamation->date_resolution->format('d/m/Y H:i') }}</p>
                @endif
            </div>
        </div>

        <!-- Sujet -->
        <div class="mb-4">
            <p class="text-gray-500 text-sm">Sujet</p>
            <p class="font-semibold text-lg">{{ $reclamation->sujet }}</p>
        </div>

        <!-- Description -->
        <div class="mb-6">
            <p class="text-gray-500 text-sm">Description</p>
            <div class="bg-gray-50 p-4 rounded-lg">{{ $reclamation->description }}</div>
        </div>

        <!-- Réponse -->
        <div class="mb-6">
            <p class="text-gray-500 text-sm">Réponse (admin)</p>
            <div class="bg-gray-50 p-4 rounded-lg {{ $reclamation->reponse ? '' : 'text-gray-400 italic' }}">
                {{ $reclamation->reponse ?? 'Aucune réponse pour le moment.' }}
            </div>
        </div>

        <!-- Formulaire de traitement -->
        <form method="POST" action="{{ route('admin.reclamations.update', $reclamation->id) }}">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-gray-700 text-sm mb-1">Statut</label>
                    <select name="statut" class="w-full border rounded-lg p-2 focus:ring-2 focus:ring-emerald-500">
                        <option value="ouverte" {{ $reclamation->statut == 'ouverte' ? 'selected' : '' }}>Ouverte</option>
                        <option value="en_cours" {{ $reclamation->statut == 'en_cours' ? 'selected' : '' }}>En cours
                        </option>
                        <option value="resolue" {{ $reclamation->statut == 'resolue' ? 'selected' : '' }}>Résolue</option>
                        <option value="fermee" {{ $reclamation->statut == 'fermee' ? 'selected' : '' }}>Fermée</option>
                    </select>
                </div>
                <div>
                    <label class="block text-gray-700 text-sm mb-1">Réponse</label>
                    <textarea name="reponse" rows="2" class="w-full border rounded-lg p-2 focus:ring-2 focus:ring-emerald-500">{{ old('reponse', $reclamation->reponse) }}</textarea>
                </div>
            </div>

            <div class="mt-4 flex gap-3">
                <button type="submit" class="btn-primary">Mettre à jour</button>
                <a href="{{ route('admin.reclamations.index') }}" class="btn-gray">Annuler</a>
            </div>
        </form>
    </div>
@endsection
