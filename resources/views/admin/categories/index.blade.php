@extends('layouts.admin')

@section('title', 'Catégories de déchets')

@section('content')
    <div class="space-y-6">
        <div class="bg-white rounded-xl shadow-soft p-6 flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
                    <i class="fas fa-tags text-emerald-500"></i> Catégories de déchets
                </h1>
                <p class="text-gray-500 mt-1">Gérez les catégories de déchets disponibles.</p>
            </div>
            <a href="{{ route('admin.categories.create') }}" class="btn-primary">
                <i class="fas fa-plus mr-2"></i> Ajouter une catégorie
            </a>
        </div>

        <div class="bg-white rounded-xl shadow-soft overflow-hidden">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Icône</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Nom</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Couleur</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Stock</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Statut</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categories as $categorie)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="px-4 py-3 text-2xl">{{ $categorie->icone ?? '' }}</td>
                            <td class="px-4 py-3 font-medium">{{ $categorie->nom }}</td>
                            <td class="px-4 py-3">
                                <span class="inline-block w-4 h-4 rounded-full"
                                    style="background-color: {{ $categorie->couleur }};"></span>
                                {{ $categorie->couleur }}
                            </td>
                            <td class="px-4 py-3">
                                @if ($categorie->stock)
                                    <span
                                        class="font-semibold text-emerald-600">{{ number_format($categorie->stock->quantite, 1) }}
                                        kg</span>
                                @else
                                    <span class="text-gray-400">0 kg</span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                @if ($categorie->est_actif)
                                    <span class="badge-success">Actif</span>
                                @else
                                    <span class="badge-danger">Inactif</span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex gap-2">
                                    <a href="{{ route('admin.categories.edit', $categorie->id) }}"
                                        class="text-blue-500 hover:text-blue-700">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form method="POST" action="{{ route('admin.categories.destroy', $categorie->id) }}"
                                        class="inline" onsubmit="return confirm('Supprimer cette catégorie ?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:text-red-700">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-8 text-center text-gray-500">Aucune catégorie.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
