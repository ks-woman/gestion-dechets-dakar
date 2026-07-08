@extends('layouts.admin')

@section('title', 'Zones de collecte')

@section('content')
    <div class="bg-white rounded-xl shadow-soft">
        <div class="p-5 border-b flex justify-between items-center">
            <h3 class="font-semibold text-gray-800 flex items-center gap-2">
                <i class="fas fa-map-marked-alt text-emerald-500"></i> Zones de collecte
            </h3>
            <a href="{{ route('admin.zones.create') }}" class="btn-primary text-sm">
                <i class="fas fa-plus mr-1"></i> Ajouter une zone
            </a>
        </div>

        <div class="overflow-x-auto p-5">
            <table class="w-full">
                <thead>
                    <tr class="text-left text-gray-500 border-b">
                        <th class="pb-3">ID</th>
                        <th class="pb-3">Nom</th>
                        <th class="pb-3">Quartiers</th>
                        <th class="pb-3">Collecteurs</th>
                        <th class="pb-3">Clients</th>
                        <th class="pb-3">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($zones as $zone)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="py-3">{{ $zone->id }}</td>
                            <td class="py-3 font-medium">{{ $zone->nom }}</td>
                            <td class="py-3">
                                @foreach ($zone->quartiers as $q)
                                    <span class="badge-gray">{{ $q }}</span>
                                @endforeach
                            </td>
                            <td class="py-3">
                                @forelse($zone->collecteurs as $collecteur)
                                    <span class="badge-info">{{ $collecteur->user->prenom ?? '' }}
                                        {{ $collecteur->user->nom ?? '' }}</span>
                                @empty
                                    <span class="text-gray-400 text-sm">Aucun</span>
                                @endforelse
                            </td>
                            <td class="py-3">{{ $zone->nombre_clients }}</td>
                            <td class="py-3">
                                <div class="flex gap-2">
                                    <a href="{{ route('admin.zones.show', $zone->id) }}"
                                        class="text-blue-500 hover:text-blue-700"><i class="fas fa-eye"></i></a>
                                    <a href="{{ route('admin.zones.edit', $zone->id) }}"
                                        class="text-green-500 hover:text-green-700"><i class="fas fa-edit"></i></a>
                                    <form method="POST" action="{{ route('admin.zones.destroy', $zone->id) }}"
                                        class="inline" onsubmit="return confirm('Supprimer ?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:text-red-700"><i
                                                class="fas fa-trash"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-6 text-center text-gray-500">Aucune zone</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-5 border-t">{{ $zones->links() }}</div>
    </div>
@endsection
