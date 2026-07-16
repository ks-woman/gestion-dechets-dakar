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
        <div class="grid grid-cols-5 gap-4">
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
            <div class="card-success">
                <div>
                    <p class="text-gray-500 text-sm">Réceptionnées</p>
                    <p class="text-2xl font-bold text-purple-600">{{ $stats['recues'] ?? 0 }}</p>
                </div>
            </div>
        </div>

        <!-- Liste -->
        <div class="bg-white rounded-xl shadow-soft overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full min-w-[700px]">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">ID</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600 hidden sm:table-cell">
                                Collecte</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600 hidden md:table-cell">
                                Catégorie</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Quantité</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600 hidden lg:table-cell">Montant
                            </th>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Statut</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($commandes as $commande)
                            <tr class="border-b hover:bg-gray-50">
                                <td class="px-4 py-3 font-medium">#{{ $commande->id }}</td>
                                <td class="px-4 py-3 hidden sm:table-cell">{{ $commande->collecte->user->prenom ?? '' }}
                                </td>
                                <td class="px-4 py-3 hidden md:table-cell">{{ $commande->categorie->nom ?? 'Non définie' }}
                                </td>
                                <td class="px-4 py-3">{{ number_format($commande->quantite, 1) }} kg</td>
                                <td class="px-4 py-3 font-semibold hidden lg:table-cell">
                                    {{ number_format($commande->montant_total, 0, ',', ' ') }} FCFA</td>
                                <td class="px-4 py-3">
                                    @if ($commande->statut == 'en_attente')
                                        <span class="badge-warning">En attente</span>
                                    @elseif($commande->statut == 'validee')
                                        <span class="badge-info">Validée</span>
                                    @elseif($commande->statut == 'affectee')
                                        <span class="badge-info">Affectée</span>
                                    @elseif($commande->statut == 'livree')
                                        <span class="badge-success">Livrée</span>
                                    @elseif($commande->statut == 'recue')
                                        <span class="badge-success">
                                            <i class="fas fa-check-circle mr-1"></i> Réceptionnée
                                        </span>
                                    @else
                                        <span class="badge-danger">Annulée</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex flex-wrap items-center gap-2">
                                        <!--  Confirmer réception (pour commandes livrées) -->
                                        @if ($commande->statut == 'livree')
                                            <form method="POST"
                                                action="{{ route('partenaire.commande.confirmer-reception', $commande->id) }}"
                                                class="inline"
                                                onsubmit="return confirm('Confirmer la réception de cette commande ?')">
                                                @csrf
                                                <button type="submit"
                                                    class="bg-emerald-500 hover:bg-emerald-600 text-white px-2 md:px-3 py-1 rounded text-[10px] md:text-xs transition">
                                                    <i class="fas fa-check mr-1"></i> Confirmer
                                                </button>
                                            </form>
                                        @elseif($commande->statut == 'recue')
                                            <span class="badge-success text-xs">
                                                <i class="fas fa-check-circle mr-1"></i> Réceptionnée
                                            </span>
                                        @endif

                                        <!-- Certificat -->
                                        @if ($commande->statut == 'livree' || $commande->statut == 'recue')
                                            @php $certificat = $commande->certificat; @endphp
                                            @if ($certificat)
                                                <a href="{{ route('partenaire.certificat.show', $certificat->id) }}"
                                                    class="text-purple-500 hover:text-purple-700 text-sm"
                                                    title="Certificat">
                                                    <i class="fas fa-file-pdf"></i>
                                                </a>
                                            @else
                                                <a href="{{ route('partenaire.certificat.generer', $commande->id) }}"
                                                    class="text-blue-500 hover:text-blue-700 text-sm"
                                                    title="Générer certificat">
                                                    <i class="fas fa-file-pdf"></i>
                                                </a>
                                            @endif
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-4 py-8 text-center text-gray-500">Aucune commande passée.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="px-4 py-3 border-t">{{ $commandes->links() }}</div>
        </div>
    </div>
@endsection
