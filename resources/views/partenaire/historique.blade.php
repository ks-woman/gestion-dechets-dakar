@extends('layouts.partenaire')

@section('title', 'Historique des commandes')

@section('content')
    <div class="space-y-6">
        <div class="bg-white rounded-xl shadow-soft p-6">
            <h1 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
                <i class="fas fa-history text-emerald-500"></i> Historique des commandes
            </h1>
        </div>

        <!-- Statistiques -->
        <div class="grid grid-cols-4 gap-4">
            <div class="card">
                <div>
                    <p class="text-gray-500 text-sm">Total</p>
                    <p class="text-2xl font-bold">{{ $stats['total'] }}</p>
                </div>
            </div>
            <div class="card-secondary">
                <div>
                    <p class="text-gray-500 text-sm">En attente</p>
                    <p class="text-2xl font-bold text-yellow-600">{{ $stats['en_attente'] }}</p>
                </div>
            </div>
            <div class="card-info">
                <div>
                    <p class="text-gray-500 text-sm">Validées</p>
                    <p class="text-2xl font-bold text-blue-600">{{ $stats['validees'] }}</p>
                </div>
            </div>
            <div class="card-success">
                <div>
                    <p class="text-gray-500 text-sm">Livrées</p>
                    <p class="text-2xl font-bold text-emerald-600">{{ $stats['livrees'] }}</p>
                </div>
            </div>
        </div>

        <!-- Liste -->
        <div class="bg-white rounded-xl shadow-soft overflow-hidden">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">ID</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Collecte</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Quantité</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Montant</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Statut</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($commandes as $commande)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="px-4 py-3">#{{ $commande->id }}</td>
                            <td class="px-4 py-3">{{ $commande->collecte->user->prenom ?? '' }}</td>
                            <td class="px-4 py-3">{{ number_format($commande->quantite, 1) }} kg</td>
                            <td class="px-4 py-3 font-semibold">{{ number_format($commande->montant_total, 0, ',', ' ') }}
                                FCFA</td>
                            <td class="px-4 py-3">
                                @if ($commande->statut == 'en_attente')
                                    <span class="badge-warning"> En attente</span>
                                @elseif($commande->statut == 'validee')
                                    <span class="badge-info"> Validée</span>
                                @elseif($commande->statut == 'livree')
                                    <span class="badge-success"> Livrée</span>
                                @else
                                    <span class="badge-danger"> Annulée</span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                @if ($commande->statut == 'livree')
                                    @php $certificat = $commande->certificat; @endphp
                                    @if ($certificat)
                                        <a href="{{ route('partenaire.certificat.show', $certificat->id) }}"
                                            class="text-emerald-600 hover:underline text-sm">
                                            Certificat
                                        </a>
                                    @else
                                        <a href="{{ route('partenaire.certificat.generer', $commande->id) }}"
                                            class="text-blue-500 hover:underline text-sm">
                                            Générer
                                        </a>
                                    @endif
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-8 text-center text-gray-500">Aucune commande passée.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="px-4 py-3 border-t">{{ $commandes->links() }}</div>
        </div>
    </div>
@endsection
