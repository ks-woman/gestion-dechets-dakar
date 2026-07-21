@extends('layouts.admin')

@section('title', 'Primes de ' . $collecteur->user->prenom . ' ' . $collecteur->user->nom)

@section('content')
    <div class="space-y-6">
        <!-- En-tête -->
        <div class="bg-white rounded-xl shadow-soft p-6">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
                        <i class="fas fa-coins text-yellow-500"></i>
                        Primes de {{ $collecteur->user->prenom }} {{ $collecteur->user->nom }}
                    </h1>
                    <p class="text-gray-500 mt-1">Matricule : {{ $collecteur->matricule }}</p>
                </div>
                <a href="{{ route('admin.statistiques.collecteurs') }}" class="btn-gray text-sm">
                    <i class="fas fa-arrow-left mr-2"></i> Retour
                </a>
            </div>
        </div>

        <!-- Résumé des primes -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="card p-4">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-gray-500 text-sm">Total primes validées</p>
                        <p class="text-2xl font-bold text-emerald-600">{{ number_format($totalPrimes ?? 0, 0, ',', ' ') }}
                            FCFA</p>
                    </div>
                    <i class="fas fa-check-circle text-3xl text-emerald-500"></i>
                </div>
            </div>
            <div class="card p-4">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-gray-500 text-sm">Total payé</p>
                        <p class="text-2xl font-bold text-blue-600">{{ number_format($totalPaye ?? 0, 0, ',', ' ') }} FCFA
                        </p>
                    </div>
                    <i class="fas fa-money-bill-wave text-3xl text-blue-500"></i>
                </div>
            </div>
            <div class="card p-4">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-gray-500 text-sm">En attente</p>
                        <p class="text-2xl font-bold text-yellow-600">
                            {{ number_format(($totalPrimes ?? 0) - ($totalPaye ?? 0), 0, ',', ' ') }} FCFA</p>
                    </div>
                    <i class="fas fa-clock text-3xl text-yellow-500"></i>
                </div>
            </div>
        </div>

        <!-- Liste des primes -->
        <div class="bg-white rounded-xl shadow-soft overflow-hidden">
            @if ($primes->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full min-w-[600px]">
                        <thead class="bg-gray-50 border-b">
                            <tr>
                                <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Mois</th>
                                <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Année</th>
                                <th class="px-4 py-3 text-right text-sm font-semibold text-gray-600">Montant</th>
                                <th class="px-4 py-3 text-center text-sm font-semibold text-gray-600">Statut</th>
                                <th class="px-4 py-3 text-center text-sm font-semibold text-gray-600">Détails</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($primes as $prime)
                                <tr class="border-b hover:bg-gray-50">
                                    <td class="px-4 py-3">
                                        {{ \Carbon\Carbon::create()->month($prime->mois)->locale('fr')->monthName }}
                                    </td>
                                    <td class="px-4 py-3">{{ $prime->annee }}</td>
                                    <td class="px-4 py-3 text-right font-semibold text-emerald-600">
                                        {{ number_format($prime->montant_total, 0, ',', ' ') }} FCFA
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        @if ($prime->statut == 'calcule')
                                            <span class="badge-warning text-xs">Calculé</span>
                                        @elseif($prime->statut == 'valide')
                                            <span class="badge-success text-xs">Validé</span>
                                        @elseif($prime->statut == 'paye')
                                            <span class="badge-info text-xs">Payé</span>
                                        @else
                                            <span class="badge-gray text-xs">{{ $prime->statut }}</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <button onclick="showDetails({{ json_encode($prime->details) }})"
                                            class="text-blue-500 hover:text-blue-700 text-sm">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="px-4 py-3 border-t">{{ $primes->links() }}</div>
            @else
                <div class="text-center py-12 text-gray-500">
                    <i class="fas fa-coins text-4xl mb-2 block"></i>
                    <p>Aucune prime enregistrée pour ce collecteur.</p>
                </div>
            @endif
        </div>
    </div>

    <script>
        function showDetails(details) {
            if (!details) {
                alert('Aucun détail disponible.');
                return;
            }
            let msg = '';
            for (let [key, value] of Object.entries(details)) {
                msg += key.replace(/_/g, ' ').toUpperCase() + ' : ' + value + '\n';
            }
            alert(msg);
        }
    </script>
@endsection
