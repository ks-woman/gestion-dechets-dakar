@extends('layouts.collecteur')

@section('title', 'Kit activé avec succès')

@section('content')
    <div class="bg-white rounded-lg shadow p-6 text-center">
        <div class="text-6xl mb-4"></div>
        <h1 class="text-2xl font-bold text-green-600 mb-4">Kit activé avec succès !</h1>

        <div class="bg-gray-50 p-4 rounded-lg mb-6 text-left">
            <h2 class="font-bold mb-3"> Récapitulatif de l'activation</h2>
            <p><strong>Client :</strong> {{ $client->prenom }} {{ $client->nom }}</p>
            <p><strong>Adresse :</strong> {{ $client->adresse }}</p>
            <p><strong>Quartier :</strong> {{ $client->quartier }}</p>
            <p><strong>Type de kit :</strong> {{ $kit->type_kit ?? 'Standard' }}</p>
            <p><strong>Date d'activation :</strong> {{ now()->format('d/m/Y H:i') }}</p>
            <p><strong>Période d'essai :</strong> 15 jours</p>
        </div>

        <div class="flex gap-3 justify-center">
            <a href="{{ route('collecteur.dashboard') }}" class="bg-blue-600 text-white px-6 py-2 rounded-lg">
                Retour tableau de bord
            </a>
            <a href="{{ route('collecteur.tournee') }}" class="bg-green-600 text-white px-6 py-2 rounded-lg">
                Voir ma tournée
            </a>
        </div>
    </div>
@endsection
