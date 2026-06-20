@extends('layouts.admin')

@section('title', 'Gestion des récompenses')

@section('content')
    <div class="bg-white rounded-xl shadow-md">
        <div class="p-5 border-b flex justify-between items-center">
            <h3 class="font-semibold text-gray-800 flex items-center gap-2">
                <i class="fas fa-gift text-purple-500"></i> Récompenses
            </h3>
            <a href="{{ route('admin.recompenses.create') }}"
                class="bg-purple-500 text-white px-4 py-2 rounded-lg text-sm hover:bg-purple-600">
                <i class="fas fa-plus mr-1"></i> Ajouter
            </a>
        </div>

        <div class="overflow-x-auto p-5">
            <table class="w-full">
                <thead>
                    <tr class="text-left text-gray-500 border-b">
                        <th class="pb-3">ID</th>
                        <th class="pb-3">Nom</th>
                        <th class="pb-3">Points</th>
                        <th class="pb-3">Type</th>
                        <th class="pb-3">Stock</th>
                        <th class="pb-3">Expiration</th>
                        <th class="pb-3">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($recompenses as $recompense)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="py-3">{{ $recompense->id }}</td>
                            <td class="py-3 font-medium">{{ $recompense->nom_recompense }}</td>
                            <td class="py-3">{{ $recompense->points_requis }}</td>
                            <td class="py-3">{{ $recompense->type_recompense }}</td>
                            <td class="py-3">{{ $recompense->quantite_disponible }}</td>
                            <td class="py-3">
                                {{ $recompense->date_expiration ? \Carbon\Carbon::parse($recompense->date_expiration)->format('d/m/Y') : '-' }}
                            </td>
                            <td class="py-3">
                                <a href="{{ route('admin.recompenses.edit', $recompense->id) }}"
                                    class="text-blue-500 hover:text-blue-700"><i class="fas fa-edit"></i></a>
                                <form method="POST" action="{{ route('admin.recompenses.destroy', $recompense->id) }}"
                                    class="inline" onsubmit="return confirm('Supprimer ?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-700"><i
                                            class="fas fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="p-5 border-t">{{ $recompenses->links() }}</div>
    </div>
@endsection
