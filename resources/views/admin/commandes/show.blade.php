@extends('layouts.admin')

@section('title', 'Détail de la commande #' . $commande->id)

@section('content')
    <div class="bg-white rounded-xl shadow-soft p-6 max-w-4xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800"> Détail de la commande #{{ $commande->id }}</h1>
            <a href="{{ route('admin.commandes.index') }}" class="text-emerald-600 hover:underline">
                <i class="fas fa-arrow-left mr-1"></i> Retour
            </a>
        </div>

        <!-- Informations générales -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
            <div class="bg-gray-50 p-4 rounded-lg">
                <p class="text-gray-500 text-sm">Partenaire</p>
                <p class="font-semibold text-lg">{{ $commande->partenaire->prenom }} {{ $commande->partenaire->nom }}</p>
                <p class="text-sm text-gray-500">{{ $commande->partenaire->email }}</p>
                <p class="text-sm text-gray-500">{{ $commande->partenaire->telephone }}</p>
                <p class="text-sm text-gray-500"> {{ $commande->partenaire->quartier ?? 'Non renseigné' }}</p>
                <p class="text-sm text-gray-500">{{ $commande->partenaire->adresse }}</p>
            </div>
            <div class="bg-gray-50 p-4 rounded-lg">
                <p class="text-gray-500 text-sm">Statut</p>
                <p class="font-semibold text-lg">
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
                </p>
                <p class="text-sm text-gray-500 mt-1">Créée le {{ $commande->created_at->format('d/m/Y H:i') }}</p>
                @if ($commande->date_livraison)
                    <p class="text-sm text-gray-500">Livrée le {{ $commande->date_livraison->format('d/m/Y H:i') }}</p>
                @endif
                @if ($commande->date_affectation)
                    <p class="text-sm text-gray-500">Affectée le {{ $commande->date_affectation->format('d/m/Y H:i') }}</p>
                @endif
            </div>
        </div>

        <!-- Détails de la commande -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <div class="bg-gray-50 p-4 rounded-lg text-center">
                <p class="text-gray-500 text-sm">Type de déchet</p>
                <p class="font-semibold text-lg">{{ $commande->categorie->nom ?? 'Non défini' }}</p>
            </div>
            <div class="bg-gray-50 p-4 rounded-lg text-center">
                <p class="text-gray-500 text-sm">Quantité</p>
                <p class="font-semibold text-lg">{{ number_format($commande->quantite, 1) }} kg</p>
            </div>
            <div class="bg-gray-50 p-4 rounded-lg text-center">
                <p class="text-gray-500 text-sm">Montant total</p>
                <p class="font-semibold text-lg text-emerald-600">
                    {{ number_format($commande->montant_total, 0, ',', ' ') }} FCFA</p>
            </div>
        </div>

        <!-- Collecteur affecté -->
        <div class="mb-6">
            <h3 class="font-semibold text-gray-700 mb-2"> Collecteur affecté</h3>
            @if ($commande->collecteur)
                <div class="bg-emerald-50 p-4 rounded-lg border border-emerald-200 flex items-center gap-4">
                    <div
                        class="w-12 h-12 bg-emerald-600 rounded-full flex items-center justify-center text-white font-bold text-xl">
                        {{ substr($commande->collecteur->user->prenom, 0, 1) }}{{ substr($commande->collecteur->user->nom, 0, 1) }}
                    </div>
                    <div>
                        <p class="font-semibold">{{ $commande->collecteur->user->prenom }}
                            {{ $commande->collecteur->user->nom }}</p>
                        <p class="text-sm text-gray-500">{{ $commande->collecteur->user->email }}</p>
                        <p class="text-sm text-gray-500">{{ $commande->collecteur->user->telephone }}</p>
                        <p class="text-sm text-gray-500">Matricule : {{ $commande->collecteur->matricule }}</p>
                    </div>
                </div>
            @else
                <div class="bg-gray-50 p-4 rounded-lg text-gray-500">
                    <i class="fas fa-user-slash mr-2"></i> Aucun collecteur affecté
                </div>
            @endif
        </div>

        <!-- Formulaire de mise à jour -->
        <div class="border-t pt-6">
            <h3 class="font-semibold text-gray-700 mb-4">Mettre à jour le statut</h3>
            <form method="POST" action="{{ route('admin.commandes.update', $commande->id) }}">
                @csrf
                @method('PUT')
                <div class="flex flex-wrap gap-4 items-end">
                    <div class="flex-1 min-w-[200px]">
                        <label class="block text-gray-700 text-sm mb-1">Nouveau statut</label>
                        <select name="statut" class="w-full border rounded-lg p-2 focus:ring-2 focus:ring-emerald-500">
                            <option value="en_attente" {{ $commande->statut == 'en_attente' ? 'selected' : '' }}> En
                                attente</option>
                            <option value="validee" {{ $commande->statut == 'validee' ? 'selected' : '' }}> Valider
                            </option>
                            <option value="affectee" {{ $commande->statut == 'affectee' ? 'selected' : '' }}> Affectée
                            </option>
                            <option value="livree" {{ $commande->statut == 'livree' ? 'selected' : '' }}> Livrer</option>
                            <option value="annulee" {{ $commande->statut == 'annulee' ? 'selected' : '' }}> Annuler
                            </option>
                        </select>
                    </div>
                    <button type="submit" class="btn-primary">
                        <i class="fas fa-save mr-1"></i> Mettre à jour
                    </button>
                </div>
            </form>
        </div>

        <!-- Actions -->
        <div class="border-t pt-6 mt-6 flex gap-3">
            <a href="{{ route('admin.commandes.index') }}" class="btn-gray">
                <i class="fas fa-arrow-left mr-1"></i> Retour
            </a>
            @if ($commande->statut == 'validee' && !$commande->collecteur_id)
                <button onclick="openAffectationModal({{ $commande->id }})" class="btn-secondary">
                    <i class="fas fa-user-plus mr-1"></i> Affecter un collecteur
                </button>
            @endif
        </div>
    </div>

    <!-- Inclusion du modal d'affectation -->
    @include('admin.commandes.partials.affectation-modal')
@endsection
