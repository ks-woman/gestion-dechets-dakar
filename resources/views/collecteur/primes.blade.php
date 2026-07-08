@extends('layouts.collecteur')

@section('title', 'Mes primes')

@section('content')
    <div class="space-y-6">
        <div class="bg-white rounded-xl shadow-soft p-6">
            <h1 class="text-2xl font-bold text-gray-800"> Mes primes</h1>
            <p class="text-gray-500 mt-1">Retrouvez ici le détail de vos primes mensuelles.</p>
        </div>

        <div class="bg-gradient-to-r from-emerald-500 to-emerald-700 rounded-xl shadow-soft p-6 text-white">
            <p class="text-sm opacity-80">Total des primes validées</p>
            <p class="text-3xl font-bold">{{ number_format($totalPrimes, 0, ',', ' ') }} FCFA</p>
        </div>

        <div class="bg-white rounded-xl shadow-soft overflow-hidden">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Mois</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Montant</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Statut</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Détails</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($primes as $prime)
                        <tr class="border-b">
                            <td class="px-4 py-3">{{ \Carbon\Carbon::create($prime->annee, $prime->mois)->format('F Y') }}
                            </td>
                            <td class="px-4 py-3 font-bold text-emerald-600">
                                {{ number_format($prime->montant_total, 0, ',', ' ') }} FCFA</td>
                            <td class="px-4 py-3">
                                @if ($prime->statut == 'calcule')
                                    <span class="badge-warning"> En attente</span>
                                @elseif($prime->statut == 'valide')
                                    <span class="badge-success">Validée</span>
                                @else
                                    <span class="badge-info"> Payée</span>
                                @endif
                            </td>
                            <td class="px-4 py-3"><button onclick="afficherDetails({{ json_encode($prime->details) }})"
                                    class="text-blue-500 hover:underline text-sm">Voir détails</button></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-4 py-8 text-center text-gray-500">Aucune prime calculée.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="px-4 py-3 border-t">{{ $primes->links() }}</div>
        </div>
    </div>

    <script>
        function afficherDetails(details) {
            if (!details) return alert('Aucun détail.');
            let msg = '';
            for (let [key, value] of Object.entries(details)) msg += `${key.replace(/_/g,' ').toUpperCase()} : ${value}\n`;
            alert(msg);
        }
    </script>
@endsection
