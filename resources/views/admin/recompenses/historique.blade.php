@extends('layouts.menage')

@section('title', 'Historique des échanges')

@section('content')
    <div class="bg-white rounded-xl shadow p-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">📜 Historique des échanges</h1>
            <a href="{{ route('recompenses.catalogue') }}" class="text-blue-500 hover:text-blue-700">
                ← Retour au catalogue
            </a>
        </div>

        @if ($echanges->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b">
                            <th class="text-left py-3">Date</th>
                            <th class="text-left py-3">Récompense</th>
                            <th class="text-left py-3">Points</th>
                            <th class="text-left py-3">Statut</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($echanges as $echange)
                            <tr class="border-b hover:bg-gray-50">
                                <td class="py-3">{{ \Carbon\Carbon::parse($echange->date_echange)->format('d/m/Y') }}</td>
                                <td class="py-3">{{ $echange->recompense->nom_recompense }}</td>
                                <td class="py-3 font-semibold text-yellow-600">-{{ $echange->points_utilises }} pts</td>
                                <td class="py-3">
                                    @if ($echange->statut == 'valide')
                                        <span class="px-2 py-1 rounded text-xs bg-green-100 text-green-800">✅ Validé</span>
                                    @elseif($echange->statut == 'en_attente')
                                        <span class="px-2 py-1 rounded text-xs bg-yellow-100 text-yellow-800">⏳ En
                                            attente</span>
                                    @else
                                        <span class="px-2 py-1 rounded text-xs bg-red-100 text-red-800">❌ Annulé</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-6">
                {{ $echanges->links() }}
            </div>
        @else
            <div class="text-center py-12 text-gray-500">
                <i class="fas fa-history text-4xl mb-2 block"></i>
                <p>Vous n'avez pas encore effectué d'échange.</p>
                <a href="{{ route('recompenses.catalogue') }}" class="text-emerald-600 hover:text-emerald-700">
                    Découvrir le catalogue →
                </a>
            </div>
        @endif
    </div>
@endsection
