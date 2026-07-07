@extends('layouts.admin')

@section('title', 'Détail de la zone')

@section('content')
    <div class="bg-white rounded-xl shadow-md p-6">
        <div class="flex justify-between items-center mb-4">
            <h1 class="text-2xl font-bold text-gray-800">{{ $zone->nom }}</h1>
            <a href="{{ route('admin.zones.index') }}" class="text-blue-500 hover:underline">← Retour</a>
        </div>

        <div class="grid grid-cols-2 gap-4 mb-6">
            <div>
                <p><strong>Description :</strong> {{ $zone->description ?? 'Aucune' }}</p>
                <p><strong>Quartiers :</strong> {{ implode(', ', $zone->quartiers ?? []) }}</p>
                <p><strong>Nombre de clients :</strong> {{ $zone->nombre_clients }}</p>
                <p><strong>Collectes totales :</strong> {{ $zone->nombre_collectes }}</p>
            </div>
            <div>
                <p><strong>Collecteurs affectés :</strong></p>
                <ul class="list-disc pl-5">
                    @forelse($zone->collecteurs as $collecteur)
                        <li>{{ $collecteur->user->prenom ?? '' }} {{ $collecteur->user->nom ?? '' }}</li>
                    @empty
                        <li class="text-gray-400">Aucun collecteur</li>
                    @endforelse
                </ul>
            </div>
        </div>

        <h3 class="font-semibold text-gray-700 mb-3">Clients dans cette zone</h3>
        <table class="w-full">
            <thead>
                <tr class="border-b">
                    <th class="text-left py-2">ID</th>
                    <th class="text-left py-2">Nom</th>
                    <th class="text-left py-2">Quartier</th>
                    <th class="text-left py-2">Adresse</th>
                </tr>
            </thead>
            <tbody>
                @forelse($clients as $client)
                    <tr class="border-b">
                        <td class="py-2">{{ $client->id }}</td>
                        <td>{{ $client->prenom }} {{ $client->nom }}</td>
                        <td>{{ $client->quartier }}</td>
                        <td>{{ $client->adresse }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="py-4 text-center text-gray-500">Aucun client dans cette zone.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="mt-4">{{ $clients->links() }}</div>
    </div>
@endsection
