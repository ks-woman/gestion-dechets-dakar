@extends('layouts.admin')

@section('title', 'Gestion des abonnements')

@section('content')
    <div class="space-y-6">
        <div class="bg-white rounded-xl shadow-soft p-6">
            <h1 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
                <i class="fas fa-credit-card text-emerald-500"></i> Gestion des abonnements
            </h1>
            <p class="text-gray-500 mt-1">Consultez et gérez tous les abonnements des utilisateurs.</p>
        </div>

        <!-- Statistiques -->
        <div class="grid grid-cols-5 gap-4">
            <div class="card">
                <div>
                    <p class="text-gray-500 text-sm">Total</p>
                    <p class="text-2xl font-bold">{{ $stats['total'] }}</p>
                </div>
            </div>
            <div class="card-info">
                <div>
                    <p class="text-gray-500 text-sm">Essai</p>
                    <p class="text-2xl font-bold text-blue-600">{{ $stats['essai'] }}</p>
                </div>
            </div>
            <div class="card-success">
                <div>
                    <p class="text-gray-500 text-sm">Actifs</p>
                    <p class="text-2xl font-bold text-emerald-600">{{ $stats['actif'] }}</p>
                </div>
            </div>
            <div class="card-danger">
                <div>
                    <p class="text-gray-500 text-sm">Expirés</p>
                    <p class="text-2xl font-bold text-red-600">{{ $stats['expire'] }}</p>
                </div>
            </div>
            <div class="card-secondary">
                <div>
                    <p class="text-gray-500 text-sm">Résiliés</p>
                    <p class="text-2xl font-bold text-yellow-600">{{ $stats['resilie'] }}</p>
                </div>
            </div>
        </div>

        <!-- Filtres -->
        <div class="bg-white rounded-xl shadow-soft p-4">
            <form method="GET" class="flex flex-wrap gap-4 items-center">
                <div class="flex items-center gap-2">
                    <label class="text-sm text-gray-600">Statut :</label>
                    <select name="statut" class="border rounded-lg p-2 focus:ring-2 focus:ring-emerald-500">
                        <option value="">Tous</option>
                        <option value="essai" {{ request('statut') == 'essai' ? 'selected' : '' }}>Essai</option>
                        <option value="actif" {{ request('statut') == 'actif' ? 'selected' : '' }}>Actif</option>
                        <option value="expire" {{ request('statut') == 'expire' ? 'selected' : '' }}>Expiré</option>
                        <option value="resilie" {{ request('statut') == 'resilie' ? 'selected' : '' }}>Résilié</option>
                    </select>
                </div>
                <div class="flex items-center gap-2 flex-1">
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Rechercher un utilisateur..."
                        class="flex-1 border rounded-lg p-2 focus:ring-2 focus:ring-emerald-500">
                    <button type="submit" class="btn-primary text-sm">Filtrer</button>
                    <a href="{{ route('admin.abonnements.index') }}" class="btn-gray text-sm">Réinitialiser</a>
                </div>
            </form>
        </div>

        <!-- Liste -->
        <div class="bg-white rounded-xl shadow-soft overflow-hidden">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">ID</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Utilisateur</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Statut</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Début essai</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Fin essai</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Montant</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($abonnements as $abonnement)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="px-4 py-3">#{{ $abonnement->id }}</td>
                            <td class="px-4 py-3">
                                <div class="font-medium">{{ $abonnement->user->prenom ?? '' }}
                                    {{ $abonnement->user->nom ?? '' }}</div>
                                <div class="text-xs text-gray-500">{{ $abonnement->user->email ?? '' }}</div>
                            </td>
                            <td class="px-4 py-3">
                                @if ($abonnement->statut == 'actif')
                                    <span class="badge-success">✅ Actif</span>
                                @elseif($abonnement->statut == 'essai')
                                    <span class="badge-info">🆓 Essai</span>
                                @elseif($abonnement->statut == 'expire')
                                    <span class="badge-danger">⛔ Expiré</span>
                                @else
                                    <span class="badge-gray">❌ Résilié</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-sm">
                                {{ $abonnement->date_debut_essai ? $abonnement->date_debut_essai->format('d/m/Y') : '-' }}
                            </td>
                            <td class="px-4 py-3 text-sm">
                                {{ $abonnement->date_fin_essai ? $abonnement->date_fin_essai->format('d/m/Y') : '-' }}
                            </td>
                            <td class="px-4 py-3 font-semibold text-emerald-600">
                                {{ number_format($abonnement->montant_mensuel, 0) }} FCFA
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex gap-2">
                                    <a href="{{ route('admin.abonnements.show', $abonnement->id) }}"
                                        class="text-blue-500 hover:text-blue-700" title="Voir">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    @if ($abonnement->statut != 'actif')
                                        <form method="POST"
                                            action="{{ route('admin.abonnements.activer', $abonnement->id) }}"
                                            class="inline">
                                            @csrf
                                            <button type="submit" class="text-emerald-500 hover:text-emerald-700"
                                                title="Activer">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-8 text-center text-gray-500">
                                Aucun abonnement trouvé.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="px-4 py-3 border-t">{{ $abonnements->links() }}</div>
        </div>
    </div>
@endsection
