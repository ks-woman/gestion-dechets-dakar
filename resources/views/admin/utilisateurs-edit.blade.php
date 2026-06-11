@extends('layouts.admin')

@section('title', 'Modifier un utilisateur')

@section('content')
    <div class="bg-white rounded-xl shadow-md p-6">
        <div class="flex items-center gap-3 mb-6">
            <i class="fas fa-user-edit text-2xl text-blue-500"></i>
            <h1 class="text-2xl font-bold text-gray-800">Modifier l'utilisateur</h1>
        </div>

        <form method="POST" action="{{ route('admin.utilisateurs.update', $user->id) }}">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-gray-700 mb-2">Nom</label>
                    <input type="text" name="nom" value="{{ old('nom', $user->nom) }}"
                        class="w-full border rounded-lg p-2" required>
                </div>
                <div>
                    <label class="block text-gray-700 mb-2">Prénom</label>
                    <input type="text" name="prenom" value="{{ old('prenom', $user->prenom) }}"
                        class="w-full border rounded-lg p-2" required>
                </div>
                <div>
                    <label class="block text-gray-700 mb-2">Email</label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}"
                        class="w-full border rounded-lg p-2" required>
                </div>
                <div>
                    <label class="block text-gray-700 mb-2">Téléphone</label>
                    <input type="tel" name="telephone" value="{{ old('telephone', $user->telephone) }}"
                        class="w-full border rounded-lg p-2" required>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-gray-700 mb-2">Adresse</label>
                    <textarea name="adresse" class="w-full border rounded-lg p-2" required>{{ old('adresse', $user->adresse) }}</textarea>
                </div>
                <div>
                    <label class="block text-gray-700 mb-2">Quartier</label>
                    <input type="text" name="quartier" value="{{ old('quartier', $user->quartier) }}"
                        class="w-full border rounded-lg p-2" required>
                </div>
                <div>
                    <label class="block text-gray-700 mb-2">Rôle</label>
                    <select name="role" class="w-full border rounded-lg p-2" required>
                        <option value="menage" {{ $user->role == 'menage' ? 'selected' : '' }}>Ménage</option>
                        <option value="entreprise" {{ $user->role == 'entreprise' ? 'selected' : '' }}>Entreprise</option>
                        <option value="collecteur" {{ $user->role == 'collecteur' ? 'selected' : '' }}>Collecteur</option>
                        <option value="partenaire" {{ $user->role == 'partenaire' ? 'selected' : '' }}>Partenaire</option>
                        <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Administrateur</option>
                    </select>
                </div>
                <div>
                    <label class="block text-gray-700 mb-2">Statut du compte</label>
                    <select name="statut_compte" class="w-full border rounded-lg p-2" required>
                        <option value="inscrit" {{ $user->statut_compte == 'inscrit' ? 'selected' : '' }}>Inscrit</option>
                        <option value="commande_kit" {{ $user->statut_compte == 'commande_kit' ? 'selected' : '' }}>Kit
                            commandé</option>
                        <option value="essai_15j" {{ $user->statut_compte == 'essai_15j' ? 'selected' : '' }}>Période
                            d'essai</option>
                        <option value="abonne_actif" {{ $user->statut_compte == 'abonne_actif' ? 'selected' : '' }}>Abonné
                            actif</option>
                        <option value="inactif" {{ $user->statut_compte == 'inactif' ? 'selected' : '' }}>Inactif</option>
                    </select>
                </div>
                <div>
                    <label class="block text-gray-700 mb-2">Nouveau mot de passe (laisser vide pour ne pas changer)</label>
                    <input type="password" name="mot_passe" class="w-full border rounded-lg p-2">
                </div>
            </div>

            <div class="mt-6 flex gap-3">
                <button type="submit" class="bg-emerald-500 text-white px-6 py-2 rounded-lg hover:bg-emerald-600">
                    <i class="fas fa-save mr-2"></i> Enregistrer
                </button>
                <a href="{{ route('admin.utilisateurs') }}"
                    class="bg-gray-500 text-white px-6 py-2 rounded-lg hover:bg-gray-600">
                    <i class="fas fa-times mr-2"></i> Annuler
                </a>
            </div>
        </form>
    </div>
@endsection
