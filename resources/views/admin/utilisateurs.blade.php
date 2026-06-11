@extends('layouts.admin')

@section('title', 'Utilisateurs')

@section('content')
    <div class="bg-white rounded-xl shadow-md">
        <div class="p-5 border-b flex justify-between items-center">
            <h3 class="font-semibold text-gray-800 flex items-center gap-2">
                <i class="fas fa-users text-blue-500"></i> Gestion des utilisateurs
            </h3>
            <div class="flex gap-3">
                <a href="{{ route('admin.utilisateurs.create') }}"
                    class="bg-emerald-500 text-white px-4 py-2 rounded-lg text-sm hover:bg-emerald-600 transition flex items-center gap-2">
                    <i class="fas fa-plus"></i> Ajouter
                </a>
                <div class="text-sm text-gray-500">
                    Total : {{ $users->total() }} utilisateur(s)
                </div>
            </div>
        </div>

        <div class="overflow-x-auto p-5">
            <table class="w-full">
                <thead>
                    <tr class="text-left text-gray-500 border-b">
                        <th class="pb-3">ID</th>
                        <th class="pb-3">Nom</th>
                        <th class="pb-3">Email</th>
                        <th class="pb-3">Rôle</th>
                        <th class="pb-3">Statut</th>
                        <th class="pb-3">Points</th>
                        <th class="pb-3">Inscription</th>
                        <th class="pb-3">Actions</th>
                    <tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="py-3">{{ $user->id }}</td>
                            <td class="py-3 font-medium">{{ $user->prenom }} {{ $user->nom }}</td>
                            <td class="py-3 text-gray-600">{{ $user->email }}</td>
                            <td class="py-3">
                                @if ($user->role == 'admin')
                                    <span class="px-2 py-1 rounded text-xs bg-red-100 text-red-800"><i
                                            class="fas fa-crown mr-1"></i> Admin</span>
                                @elseif($user->role == 'collecteur')
                                    <span class="px-2 py-1 rounded text-xs bg-blue-100 text-blue-800"><i
                                            class="fas fa-truck mr-1"></i> Collecteur</span>
                                @elseif($user->role == 'menage')
                                    <span class="px-2 py-1 rounded text-xs bg-green-100 text-green-800"><i
                                            class="fas fa-home mr-1"></i> Ménage</span>
                                @elseif($user->role == 'entreprise')
                                    <span class="px-2 py-1 rounded text-xs bg-yellow-100 text-yellow-800"><i
                                            class="fas fa-building mr-1"></i> Entreprise</span>
                                @else
                                    <span class="px-2 py-1 rounded text-xs bg-purple-100 text-purple-800"><i
                                            class="fas fa-handshake mr-1"></i> Partenaire</span>
                                @endif
                            </td>
                            <td class="py-3">
                                @if ($user->statut_compte == 'abonne_actif')
                                    <span class="px-2 py-1 rounded text-xs bg-green-100 text-green-800"> Actif</span>
                                @elseif($user->statut_compte == 'essai_15j')
                                    <span class="px-2 py-1 rounded text-xs bg-blue-100 text-blue-800">Essai</span>
                                @else
                                    <span class="px-2 py-1 rounded text-xs bg-gray-100 text-gray-800">
                                        {{ $user->statut_compte }}</span>
                                @endif
                            </td>
                            <td class="py-3 font-semibold">{{ $user->score_total }} pts</td>
                            <td class="py-3 text-gray-500">{{ $user->created_at->format('d/m/Y') }}</td>
                            <td class="py-3">
                                <div class="flex gap-2">
                                    <a href="{{ route('admin.utilisateurs.edit', $user->id) }}"
                                        class="text-blue-500 hover:text-blue-700" title="Modifier">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form method="POST" action="{{ route('admin.utilisateurs.destroy', $user->id) }}"
                                        class="inline" onsubmit="return confirm('Supprimer cet utilisateur ?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:text-red-700" title="Supprimer">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-6 text-center text-gray-500">Aucun utilisateur trouvé</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-5 border-t">
            {{ $users->links() }}
        </div>
    </div>
@endsection
