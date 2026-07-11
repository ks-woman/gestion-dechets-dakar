@extends('layouts.admin')

@section('title', 'Gestion des commandes')

@section('content')
    <div class="space-y-6">
        <div class="bg-white rounded-xl shadow-soft p-6">
            <h1 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
                <i class="fas fa-shopping-cart text-emerald-500"></i> Gestion des commandes
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
            <div class="card-danger">
                <div>
                    <p class="text-gray-500 text-sm">Annulées</p>
                    <p class="text-2xl font-bold text-red-600">{{ $stats['annulees'] }}</p>
                </div>
            </div>
        </div>

        <!-- Filtres -->
        <div class="bg-white rounded-xl shadow-soft p-4">
            <form method="GET" class="flex flex-wrap gap-3 items-center">
                <!-- Filtre par statut -->
                <label class="text-sm text-gray-600">Filtrer par statut :</label>
                <select name="statut" class="border rounded-lg p-2 focus:ring-2 focus:ring-emerald-500">
                    <option value="">Tous</option>
                    <option value="en_attente" {{ request('statut') == 'en_attente' ? 'selected' : '' }}>En attente</option>
                    <option value="validee" {{ request('statut') == 'validee' ? 'selected' : '' }}>Validée</option>
                    <option value="affectee" {{ request('statut') == 'affectee' ? 'selected' : '' }}>Affectée</option>
                    <option value="livree" {{ request('statut') == 'livree' ? 'selected' : '' }}>Livrée</option>
                    <option value="annulee" {{ request('statut') == 'annulee' ? 'selected' : '' }}>Annulée</option>
                </select>

                <!-- Filtre par collecteur (caché, utilisé pour le retour depuis la page des collecteurs) -->
                @if (request('collecteur_id'))
                    <input type="hidden" name="collecteur_id" value="{{ request('collecteur_id') }}">
                @endif

                <button type="submit" class="btn-primary text-sm">Filtrer</button>
                <a href="{{ route('admin.commandes.index') }}" class="btn-gray text-sm">Réinitialiser</a>
            </form>
        </div>

        <!-- Message d'information si on filtre par collecteur -->
        @if (request('collecteur_id'))
            <div
                class="bg-blue-50 border border-blue-200 rounded-lg p-4 text-blue-700 text-sm flex items-center justify-between">
                <div>
                    <i class="fas fa-info-circle mr-2"></i>
                    Affichage des commandes <strong>disponibles</strong> pour le collecteur sélectionné
                    (commandes en attente ou validées sans collecteur affecté).
                </div>
                <a href="{{ route('admin.commandes.index') }}"
                    class="text-blue-700 underline font-medium hover:text-blue-900">
                    Réinitialiser le filtre
                </a>
            </div>
        @endif

        <!-- Liste des commandes -->
        <div class="bg-white rounded-xl shadow-soft overflow-hidden">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">ID</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Partenaire</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Type</th>
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
                            <td class="px-4 py-3">{{ $commande->partenaire->nom }} {{ $commande->partenaire->prenom }}
                            </td>
                            <td class="px-4 py-3 capitalize">
                                {{ $commande->categorie->nom ?? 'Non défini' }}
                            </td>
                            <td class="px-4 py-3">{{ number_format($commande->quantite, 1) }} kg</td>
                            <td class="px-4 py-3 font-semibold">{{ number_format($commande->montant_total, 0, ',', ' ') }}
                                FCFA</td>
                            <td class="px-4 py-3">
                                @if ($commande->statut == 'en_attente')
                                    <span class="badge-warning"> En attente</span>
                                @elseif($commande->statut == 'validee')
                                    <span class="badge-info"> Validée</span>
                                @elseif($commande->statut == 'affectee')
                                    <span class="badge-secondary"> Affectée</span>
                                @elseif($commande->statut == 'livree')
                                    <span class="badge-success"> Livrée</span>
                                @else
                                    <span class="badge-danger"> Annulée</span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex gap-2">
                                    <!-- Voir détail -->
                                    <a href="{{ route('admin.commandes.show', $commande->id) }}"
                                        class="text-blue-500 hover:text-blue-700" title="Voir">
                                        <i class="fas fa-eye"></i>
                                    </a>

                                    <!-- Valider (si en attente) -->
                                    @if ($commande->statut == 'en_attente')
                                        <form method="POST" action="{{ route('admin.commandes.update', $commande->id) }}"
                                            class="inline">
                                            @csrf @method('PUT')
                                            <input type="hidden" name="statut" value="validee">
                                            <button type="submit" class="text-green-500 hover:text-green-700"
                                                title="Valider">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        </form>
                                    @endif

                                    <!-- Affecter (si validée sans collecteur) -->
                                    @if ($commande->statut == 'validee' && !$commande->collecteur_id)
                                        <button onclick="openAffectationModal({{ $commande->id }})"
                                            class="text-purple-500 hover:text-purple-700" title="Affecter un collecteur">
                                            <i class="fas fa-user-plus"></i>
                                        </button>
                                    @endif

                                    <!-- Livrer (si affectée ou validée avec collecteur) -->
                                    @if (in_array($commande->statut, ['validee', 'affectee']) && $commande->collecteur_id)
                                        <form method="POST" action="{{ route('admin.commandes.update', $commande->id) }}"
                                            class="inline">
                                            @csrf @method('PUT')
                                            <input type="hidden" name="statut" value="livree">
                                            <button type="submit" class="text-emerald-500 hover:text-emerald-700"
                                                title="Livrer">
                                                <i class="fas fa-truck"></i>
                                            </button>
                                        </form>
                                    @endif

                                    <!-- Annuler (si pas encore livrée) -->
                                    @if (!in_array($commande->statut, ['livree', 'annulee']))
                                        <form method="POST" action="{{ route('admin.commandes.update', $commande->id) }}"
                                            class="inline" onsubmit="return confirm('Annuler cette commande ?')">
                                            @csrf @method('PUT')
                                            <input type="hidden" name="statut" value="annulee">
                                            <button type="submit" class="text-red-500 hover:text-red-700"
                                                title="Annuler">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-8 text-center text-gray-500">
                                @if (request('collecteur_id'))
                                    Aucune commande disponible pour ce collecteur.
                                @else
                                    Aucune commande trouvée.
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="px-4 py-3 border-t">{{ $commandes->links() }}</div>
        </div>
    </div>

    <!-- Modal d'affectation (partial) -->
    @include('admin.commandes.partials.affectation-modal')
@endsection
