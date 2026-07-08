@extends('layouts.collecteur')

@section('title', 'Historique des collectes')

@section('content')
    <div class="space-y-6">
        <div class="bg-white rounded-xl shadow-soft p-6">
            <h1 class="text-2xl font-bold text-gray-800"> Historique des collectes</h1>
            <p class="text-gray-500 mt-1">Consultez toutes vos collectes passées.</p>
        </div>

        <!-- Stats -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="card-info">
                <div>
                    <p class="text-gray-500 text-sm">Total collectes</p>
                    <p class="text-2xl font-bold text-blue-600">{{ $stats['total'] }}</p>
                </div>
            </div>
            <div class="card-success">
                <div>
                    <p class="text-gray-500 text-sm">Poids total</p>
                    <p class="text-2xl font-bold text-emerald-600">{{ number_format($stats['total_poids'], 1) }} kg</p>
                </div>
            </div>
            <div class="card-warning">
                <div>
                    <p class="text-gray-500 text-sm">Points générés</p>
                    <p class="text-2xl font-bold text-yellow-600">{{ number_format($stats['total_points']) }}</p>
                </div>
            </div>
        </div>

        <!-- Filtres -->
        <div class="bg-white rounded-xl shadow-soft p-4">
            <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div><label class="block text-gray-600 text-sm">Date début</label><input type="date" name="date_debut"
                        value="{{ request('date_debut') }}"
                        class="w-full border rounded-lg p-2 focus:ring-2 focus:ring-emerald-500"></div>
                <div><label class="block text-gray-600 text-sm">Date fin</label><input type="date" name="date_fin"
                        value="{{ request('date_fin') }}"
                        class="w-full border rounded-lg p-2 focus:ring-2 focus:ring-emerald-500"></div>
                <div><label class="block text-gray-600 text-sm">Client</label><input type="text" name="client"
                        placeholder="Nom ou prénom" value="{{ request('client') }}"
                        class="w-full border rounded-lg p-2 focus:ring-2 focus:ring-emerald-500"></div>
                <div><label class="block text-gray-600 text-sm">Statut</label>
                    <select name="statut" class="w-full border rounded-lg p-2 focus:ring-2 focus:ring-emerald-500">
                        <option value="">Tous</option>
                        <option value="planifiee" {{ request('statut') == 'planifiee' ? 'selected' : '' }}>Planifiée
                        </option>
                        <option value="realisee" {{ request('statut') == 'realisee' ? 'selected' : '' }}>Réalisée</option>
                        <option value="annulee" {{ request('statut') == 'annulee' ? 'selected' : '' }}>Annulée</option>
                    </select>
                </div>
                <div class="md:col-span-4 flex gap-2">
                    <button type="submit" class="btn-primary">Filtrer</button>
                    <a href="{{ route('collecteur.historique') }}" class="btn-gray">Réinitialiser</a>
                </div>
            </form>
        </div>

        <!-- Liste -->
        <div class="bg-white rounded-xl shadow-soft overflow-hidden">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Date</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Client</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Poids</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Points</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Statut</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($collectes as $collecte)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="px-4 py-3">
                                {{ \Carbon\Carbon::parse($collecte->date_collecte)->format('d/m/Y H:i') }}</td>
                            <td class="px-4 py-3">{{ $collecte->user->prenom ?? '' }} {{ $collecte->user->nom ?? '' }}
                            </td>
                            <td class="px-4 py-3">
                                {{ number_format($collecte->poids_recyclable + $collecte->poids_organique + $collecte->poids_residuel, 1) }}
                                kg</td>
                            <td class="px-4 py-3"><span class="badge-warning">{{ $collecte->points_obtenus }}</span></td>
                            <td class="px-4 py-3">
                                @if ($collecte->statut == 'realisee')
                                    <span class="badge-success"> Réalisée</span>
                                @elseif($collecte->statut == 'planifiee')
                                    <span class="badge-info"> Planifiée</span>
                                @else
                                    <span class="badge-gray">{{ $collecte->statut }}</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-8 text-center text-gray-500">Aucune collecte trouvée.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="px-4 py-3 border-t">{{ $collectes->links() }}</div>
        </div>
    </div>
@endsection
