@extends('layouts.admin')

@section('title', 'Gestion des réclamations')

@section('content')
    <div class="space-y-6">
        <div class="bg-white rounded-xl shadow-soft p-6">
            <h1 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
                <i class="fas fa-exclamation-triangle text-red-500"></i> Gestion des réclamations
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
            <div class="card-danger">
                <div>
                    <p class="text-gray-500 text-sm">Ouvertes</p>
                    <p class="text-2xl font-bold text-red-600">{{ $stats['ouvertes'] }}</p>
                </div>
            </div>
            <div class="card-secondary">
                <div>
                    <p class="text-gray-500 text-sm">En cours</p>
                    <p class="text-2xl font-bold text-yellow-600">{{ $stats['en_cours'] }}</p>
                </div>
            </div>
            <div class="card">
                <div>
                    <p class="text-gray-500 text-sm">Résolues</p>
                    <p class="text-2xl font-bold text-emerald-600">{{ $stats['resolues'] }}</p>
                </div>
            </div>
            <div class="card">
                <div>
                    <p class="text-gray-500 text-sm">Fermées</p>
                    <p class="text-2xl font-bold text-gray-600">{{ $stats['fermees'] }}</p>
                </div>
            </div>
        </div>

        <!-- Filtre -->
        <div class="bg-white rounded-xl shadow-soft p-4">
            <form method="GET" class="flex gap-3 items-center">
                <label class="text-sm text-gray-600">Filtrer par statut :</label>
                <select name="statut" class="border rounded-lg p-2 focus:ring-2 focus:ring-emerald-500">
                    <option value="">Tous</option>
                    <option value="ouverte" {{ request('statut') == 'ouverte' ? 'selected' : '' }}>Ouverte</option>
                    <option value="en_cours" {{ request('statut') == 'en_cours' ? 'selected' : '' }}>En cours</option>
                    <option value="resolue" {{ request('statut') == 'resolue' ? 'selected' : '' }}>Résolue</option>
                    <option value="fermee" {{ request('statut') == 'fermee' ? 'selected' : '' }}>Fermée</option>
                </select>
                <button type="submit" class="btn-primary text-sm">Filtrer</button>
                <a href="{{ route('admin.reclamations.index') }}" class="btn-gray text-sm">Réinitialiser</a>
            </form>
        </div>

        <!-- Liste -->
        <div class="bg-white rounded-xl shadow-soft overflow-hidden">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">ID</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Utilisateur</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Sujet</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Statut</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Date</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reclamations as $reclamation)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="px-4 py-3">{{ $reclamation->id }}</td>
                            <td class="px-4 py-3">
                                {{ $reclamation->user->prenom }} {{ $reclamation->user->nom }}
                                <br><span class="text-xs text-gray-400">{{ $reclamation->user->email }}</span>
                            </td>
                            <td class="px-4 py-3 font-medium">{{ Str::limit($reclamation->sujet, 40) }}</td>
                            <td class="px-4 py-3">
                                @if ($reclamation->statut == 'ouverte')
                                    <span class="badge-danger"> Ouverte</span>
                                @elseif($reclamation->statut == 'en_cours')
                                    <span class="badge-warning"> En cours</span>
                                @elseif($reclamation->statut == 'resolue')
                                    <span class="badge-success"> Résolue</span>
                                @else
                                    <span class="badge-gray"> Fermée</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-gray-500">{{ $reclamation->created_at->format('d/m/Y H:i') }}</td>
                            <td class="px-4 py-3">
                                <a href="{{ route('admin.reclamations.show', $reclamation->id) }}"
                                    class="text-blue-500 hover:text-blue-700">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-8 text-center text-gray-500">Aucune réclamation trouvée.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="px-4 py-3 border-t">{{ $reclamations->links() }}</div>
        </div>
    </div>
@endsection
