@extends('layouts.menage')

@section('title', 'Historique des collectes')

@section('content')
    <div class="bg-white rounded-xl shadow-lg p-6">
        <div class="flex items-center gap-3 mb-6">
            <div class="text-3xl">📋</div>
            <h1 class="text-2xl font-bold text-gray-800">Historique des collectes</h1>
        </div>

        @if (session('success'))
            <div class="bg-green-500 text-white p-3 rounded-lg mb-4">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="bg-red-500 text-white p-3 rounded-lg mb-4">{{ session('error') }}</div>
        @endif

        @if ($collectes->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b">
                            <th class="text-left py-3">Date</th>
                            <th class="text-left py-3">Adresse</th>
                            <th class="text-left py-3">Recyclable</th>
                            <th class="text-left py-3">Organique</th>
                            <th class="text-left py-3">Résiduel</th>
                            <th class="text-left py-3">Points</th>
                            <th class="text-left py-3">Statut</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($collectes as $collecte)
                            <tr class="border-b">
                                <td class="py-3">{{ \Carbon\Carbon::parse($collecte->date_collecte)->format('d/m/Y') }}
                                </td>
                                <td class="py-3">{{ Str::limit($collecte->adresse ?? '-', 30) }}</td>
                                <td class="py-3">{{ $collecte->poids_recyclable }} kg</td>
                                <td class="py-3">{{ $collecte->poids_organique }} kg</td>
                                <td class="py-3">{{ $collecte->poids_residuel }} kg</td>
                                <td class="py-3">
                                    <span class="bg-yellow-100 text-yellow-800 px-2 py-1 rounded text-sm">
                                        +{{ $collecte->points_obtenus }} pts
                                    </span>
                                </td>
                                <td class="py-3">
                                    @if ($collecte->statut == 'planifiee')
                                        <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded text-sm">⏳ Planifiée</span>
                                    @elseif($collecte->statut == 'realisee')
                                        <span class="bg-green-100 text-green-800 px-2 py-1 rounded text-sm">✅
                                            Réalisée</span>
                                    @elseif($collecte->statut == 'en_cours')
                                        <span class="bg-yellow-100 text-yellow-800 px-2 py-1 rounded text-sm">🔄 En
                                            cours</span>
                                    @else
                                        <span
                                            class="bg-gray-100 text-gray-800 px-2 py-1 rounded text-sm">{{ $collecte->statut }}</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-6">
                {{ $collectes->links() }}
            </div>
        @else
            <div class="text-center py-12 text-gray-500">
                <div class="text-5xl mb-3">📭</div>
                <p>Aucune collecte pour le moment</p>
                <a href="{{ route('collecte.demander') }}"
                    class="text-emerald-600 hover:text-emerald-700 mt-3 inline-block font-medium">
                    → Demander une collecte
                </a>
            </div>
        @endif
    </div>
@endsection
