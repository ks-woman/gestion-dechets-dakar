@extends('layouts.collecteur')

@section('title', 'Mes primes')

@section('content')
    <div class="space-y-6">
        <!-- En-tête -->
        <div class="bg-white rounded-xl shadow p-6">
            <h1 class="text-2xl font-bold text-gray-800"> Mes primes</h1>
            <p class="text-gray-500 mt-1">Retrouvez ici le détail de vos primes mensuelles.</p>
        </div>

        <!-- Total des primes -->
        <div class="bg-gradient-to-r from-green-500 to-green-700 rounded-xl shadow p-6 text-white">
            <p class="text-sm opacity-80">Total des primes validées</p>
            <p class="text-3xl font-bold">{{ number_format($totalPrimes ?? 0, 0, ',', ' ') }} FCFA</p>
        </div>

        <!-- Liste des primes -->
        <div class="bg-white rounded-xl shadow overflow-hidden">
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
                            <td class="px-4 py-3 font-bold text-green-600">
                                {{ number_format($prime->montant_total, 0, ',', ' ') }} FCFA</td>
                            <td class="px-4 py-3">
                                @if ($prime->statut == 'calcule')
                                    <span class="px-2 py-1 rounded text-xs bg-yellow-100 text-yellow-800"> En
                                        attente</span>
                                @elseif($prime->statut == 'valide')
                                    <span class="px-2 py-1 rounded text-xs bg-green-100 text-green-800"> Validée</span>
                                @else
                                    <span class="px-2 py-1 rounded text-xs bg-blue-100 text-blue-800"> Payée</span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <button onclick="afficherDetails({{ json_encode($prime->details) }})"
                                    class="text-blue-500 hover:underline text-sm">
                                    Voir détails
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-4 py-8 text-center text-gray-500">
                                Aucune prime calculée pour le moment.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="px-4 py-3 border-t">
                {{ $primes->links() }}
            </div>
        </div>
    </div>

    <!-- Modal pour afficher les détails -->
    <script>
        function afficherDetails(details) {
            if (!details) {
                alert('Aucun détail disponible.');
                return;
            }

            let html = '<div style="text-align:left; padding:10px; font-size:14px;">';
            for (let [key, value] of Object.entries(details)) {
                let label = key.replace(/_/g, ' ').toUpperCase();
                html += `<p><strong>${label}</strong> : ${value}</p>`;
            }
            html += '</div>';

            // Affichage simple (alerte) - vous pouvez remplacer par une modale plus jolie
            alert(html);
        }
    </script>
@endsection
