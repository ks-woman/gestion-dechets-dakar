@extends('layouts.admin')

@section('title', 'Kits commandés')

@section('content')
    <div class="bg-white rounded-xl shadow">
        <div class="p-6 border-b">
            <h1 class="text-2xl font-bold"> Kits commandés</h1>
            <p class="text-gray-500 mt-1">Liste de tous les kits de tri commandés</p>
        </div>

        <div class="overflow-x-auto p-6">
            <table class="w-full">
                <thead>
                    <tr class="text-left border-b">
                        <th class="pb-3">ID</th>
                        <th class="pb-3">Client</th>
                        <th class="pb-3">Type</th>
                        <th class="pb-3">Date commande</th>
                        <th class="pb-3">Date livraison</th>
                        <th class="pb-3">Statut</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($kits as $kit)
                        <tr class="border-b">
                            <td class="py-3">{{ $kit->id }}</td>
                            <td class="py-3">{{ $kit->user->prenom }} {{ $kit->user->nom }}</td>
                            <td class="py-3">{{ $kit->type_kit }}</td>
                            <td class="py-3">
                                {{ $kit->date_demande ? \Carbon\Carbon::parse($kit->date_demande)->format('d/m/Y') : '-' }}
                            </td>
                            <td class="py-3">
                                {{ $kit->date_distribution ? \Carbon\Carbon::parse($kit->date_distribution)->format('d/m/Y') : '-' }}
                            </td>
                            <td class="py-3">
                                @if ($kit->statut == 'en_attente')
                                    <span class="bg-yellow-100 text-yellow-800 px-2 py-1 rounded text-sm"> En
                                        attente</span>
                                @elseif($kit->statut == 'actif')
                                    <span class="bg-green-100 text-green-800 px-2 py-1 rounded text-sm"> Actif</span>
                                @else
                                    <span
                                        class="bg-gray-100 text-gray-800 px-2 py-1 rounded text-sm">{{ $kit->statut_kit }}</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-6 text-center text-gray-500">Aucun kit commandé</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
