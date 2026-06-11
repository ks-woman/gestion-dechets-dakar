@extends('layouts.admin')

@section('title', 'Collectes')

@section('content')
    <div class="bg-white rounded-xl shadow-md">
        <div class="p-5 border-b flex justify-between items-center">
            <h3 class="font-semibold text-gray-800 flex items-center gap-2">
                <i class="fas fa-truck text-emerald-500"></i> Gestion des collectes
            </h3>
            <div class="text-sm text-gray-500">
                Total : {{ $collectes->total() }} collecte(s)
            </div>
        </div>

        <div class="overflow-x-auto p-5">
            <table class="w-full">
                <thead>
                    <tr class="text-left text-gray-500 border-b">
                        <th class="pb-3">ID</th>
                        <th class="pb-3">Client</th>
                        <th class="pb-3">Date</th>
                        <th class="pb-3">Recyclable</th>
                        <th class="pb-3">Organique</th>
                        <th class="pb-3">Résiduel</th>
                        <th class="pb-3">Points</th>
                        <th class="pb-3">Statut</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($collectes as $collecte)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="py-3">{{ $collecte->id }}</td>
                            <td class="py-3 font-medium">{{ $collecte->user->prenom ?? 'N/A' }}
                                {{ $collecte->user->nom ?? '' }}</td>
                            <td class="py-3">{{ \Carbon\Carbon::parse($collecte->date_collecte)->format('d/m/Y') }}</td>
                            <td class="py-3">{{ number_format($collecte->poids_recyclable, 1) }} kg</td>
                            <td class="py-3">{{ number_format($collecte->poids_organique, 1) }} kg</td>
                            <td class="py-3">{{ number_format($collecte->poids_residuel, 1) }} kg</td>
                            <td class="py-3 font-semibold text-yellow-600">{{ $collecte->points_obtenus }} pts</td>
                            <td class="py-3">
                                @if ($collecte->statut == 'realisee')
                                    <span class="px-2 py-1 rounded text-xs bg-green-100 text-green-800"> Réalisée</span>
                                @elseif($collecte->statut == 'planifiee')
                                    <span class="px-2 py-1 rounded text-xs bg-blue-100 text-blue-800"> Planifiée</span>
                                @elseif($collecte->statut == 'valorisee')
                                    <span class="px-2 py-1 rounded text-xs bg-purple-100 text-purple-800">
                                        Valorisee</span>
                                @else
                                    <span
                                        class="px-2 py-1 rounded text-xs bg-gray-100 text-gray-800">{{ $collecte->statut }}</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-6 text-center text-gray-500">Aucune collecte trouvée</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-5 border-t">
            {{ $collectes->links() }}
        </div>
    </div>
@endsection
