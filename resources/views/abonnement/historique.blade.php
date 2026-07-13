@extends('layouts.menage')

@section('title', 'Historique des paiements')

@section('content')
    <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
        <div class="bg-gradient-to-r from-emerald-600 to-emerald-800 px-6 py-6 text-white">
            <div class="flex items-center gap-4">
                <div class="text-4xl"></div>
                <div>
                    <h1 class="text-2xl font-bold">Historique des paiements</h1>
                    <p class="text-emerald-100 text-sm">Retrouvez tous vos paiements effectués</p>
                </div>
            </div>
        </div>

        <div class="p-6">
            @if ($paiements->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b">
                                <th class="text-left py-3">Date</th>
                                <th class="text-left py-3">Montant</th>
                                <th class="text-left py-3">Mode</th>
                                <th class="text-left py-3">Référence</th>
                                <th class="text-left py-3">Statut</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($paiements as $paiement)
                                <tr class="border-b hover:bg-gray-50">
                                    <td class="py-3">{{ $paiement->date_paiement->format('d/m/Y H:i') }}</td>
                                    <td class="py-3 font-semibold text-emerald-600">
                                        {{ number_format($paiement->montant, 0) }} FCFA</td>
                                    <td class="py-3 capitalize">{{ $paiement->mode_paiement }}</td>
                                    <td class="py-3 text-sm font-mono">{{ $paiement->reference }}</td>
                                    <td class="py-3">
                                        @if ($paiement->statut == 'valide')
                                            <span class="badge-success"> Validé</span>
                                        @elseif($paiement->statut == 'en_attente')
                                            <span class="badge-warning"> En attente</span>
                                        @else
                                            <span class="badge-danger"> Échoué</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">
                    {{ $paiements->links() }}
                </div>
            @else
                <div class="text-center py-12 text-gray-500">
                    <i class="fas fa-receipt text-4xl mb-2 block"></i>
                    <p>Aucun paiement enregistré.</p>
                </div>
            @endif
        </div>
    </div>
@endsection
