@extends('layouts.collecteur')

@section('title', 'Signaler une anomalie')

@section('content')
    <div class="bg-white rounded-xl shadow p-6 max-w-2xl mx-auto">
        <h1 class="text-2xl font-bold text-gray-800 mb-4"> Signaler une anomalie</h1>
        <p class="text-gray-500 mb-6">Client : <strong>{{ $client->prenom }} {{ $client->nom }}</strong></p>

        <form method="POST" action="{{ route('collecteur.anomalie.store') }}" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="user_id" value="{{ $client->id }}">

            <div class="mb-4">
                <label class="block text-gray-700 mb-2">Type d'anomalie</label>
                <select name="type_anomalie" class="w-full border rounded-lg p-2" required>
                    <option value="">Sélectionner</option>
                    <option value="acces_impossible"> Accès impossible</option>
                    <option value="absence_tri">Absence de tri</option>
                    <option value="dechet_dangereux">Déchet dangereux</option>
                    <option value="client_absent"> Client absent</option>
                    <option value="autre"> Autre</option>
                </select>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 mb-2">Description</label>
                <textarea name="description" rows="4" class="w-full border rounded-lg p-2" required
                    placeholder="Décrivez l'anomalie..."></textarea>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 mb-2">Photo (optionnel)</label>
                <input type="file" name="photo" accept="image/*" class="w-full border rounded-lg p-2">
            </div>

            <div class="flex gap-3">
                <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-6 py-2 rounded-lg">
                    Signaler</button>
                <a href="{{ route('collecteur.tournee') }}"
                    class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg">Annuler</a>
            </div>
        </form>
    </div>
@endsection
