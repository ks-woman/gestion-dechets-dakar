@extends('layouts.menage')

@section('title', 'Historique des paiements')

@section('content')
    <div class="bg-white rounded-xl shadow-soft p-6 max-w-4xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
                <i class="fas fa-history text-emerald-500"></i> Historique des paiements
            </h1>
            <a href="{{ route('abonnement.index') }}" class="text-emerald-600 hover:underline">← Retour</a>
        </div>

        @if (isset($paiements) && $paiements->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b">
                            <th class="text-left py-2">Date</th>
                            <th class="text-left py-2">Montant</th>
                            <th class="text-left py-2">Mode</th>
                            <th class="text-left py-2">Référence</th>
                            <th class="text-left py-2">Statut</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($paiements as $paiement)
                            <tr class="border-b hover:bg-gray-50">
                                <td class="py-2">{{ $paiement->date_paiement->format('d/m/Y H:i') }}</td>
                                <td class="py-2 font-semibold">{{ number_format($paiement->montant, 0, ',', ' ') }} FCFA
                                </td>
                                <td class="py-2">{{ $paiement->mode_paiement }}</td>
                                <td class="py-2 text-xs">
                                    {{ $paiement->reference_transaction ?? ($paiement->reference ?? '-') }}</td>
                                <td class="py-2">
                                    @if ($paiement->statut === 'valide')
                                        <span class="badge-success">✅ Validé</span>
                                    @elseif($paiement->statut === 'en_attente')
                                        <span class="badge-warning">⏳ En attente</span>
                                    @else
                                        <span class="badge-danger">{{ ucfirst($paiement->statut) }}</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-4">{{ $paiements->links() }}</div>
        @else
            <div class="text-center py-12 text-gray-500">
                <i class="fas fa-box-open text-4xl mb-2 block"></i>
                <p>Aucun paiement trouvé.</p>
            </div>
        @endif
    </div>
@endsection
