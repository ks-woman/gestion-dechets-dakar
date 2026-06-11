@extends('layouts.collecteur')

@section('title', 'Sélectionner une collecte')

@section('content')
    <div class="bg-white rounded-lg shadow p-6">
        <h1 class="text-2xl font-bold mb-6">Sélectionner un client</h1>

        @if ($clients->isEmpty())
            <div class="bg-yellow-100 text-yellow-700 p-4 rounded-lg">
                Aucune collecte planifiée pour aujourd'hui dans votre quartier.
            </div>
        @else
            <div class="space-y-4">
                @foreach ($clients as $client)
                    <div class="border rounded-lg p-4 flex justify-between items-center">
                        <div>
                            <p class="font-semibold">{{ $client->prenom }} {{ $client->nom }}</p>
                            <p class="text-sm text-gray-500">{{ $client->adresse }}</p>
                            <p class="text-sm text-gray-500">Quartier : {{ $client->quartier }}</p>
                        </div>
                        <a href="{{ route('collecteur.collecte.form', $client->id) }}"
                            class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700">
                            Enregistrer collecte
                        </a>
                    </div>
                @endforeach
            </div>
        @endif

        <a href="{{ route('collecteur.dashboard') }}" class="inline-block mt-4 text-gray-500">← Retour</a>
    </div>
@endsection
