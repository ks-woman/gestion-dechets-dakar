@extends('layouts.menage')

@section('title', 'Mon profil')

@section('content')
    <div class="bg-white rounded-xl shadow p-6 max-w-2xl mx-auto">
        <!-- ===== BOUTON RETOUR ===== -->
        <div class="mb-6">
            <a href="{{ route('menage.dashboard') }}"
                class="inline-block bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium px-6 py-2.5 rounded-lg transition">
                <i class="fas fa-arrow-left mr-2"></i> Retour
            </a>
        </div>

        <h1 class="text-2xl font-bold text-gray-800 mb-6">👤 Mon profil</h1>

        <div class="space-y-4">
            <!-- Informations -->
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p class="text-gray-500 text-sm">Nom</p>
                    <p class="font-semibold">{{ auth()->user()->nom }}</p>
                </div>
                <div>
                    <p class="text-gray-500 text-sm">Prénom</p>
                    <p class="font-semibold">{{ auth()->user()->prenom }}</p>
                </div>
                <div>
                    <p class="text-gray-500 text-sm">Email</p>
                    <p class="font-semibold">{{ auth()->user()->email }}</p>
                </div>
                <div>
                    <p class="text-gray-500 text-sm">Téléphone</p>
                    <p class="font-semibold">{{ auth()->user()->telephone }}</p>
                </div>
                <div class="col-span-2">
                    <p class="text-gray-500 text-sm">Adresse</p>
                    <p class="font-semibold">{{ auth()->user()->adresse }}</p>
                </div>
                <div>
                    <p class="text-gray-500 text-sm">Quartier</p>
                    <p class="font-semibold">{{ auth()->user()->quartier }}</p>
                </div>
                <div>
                    <p class="text-gray-500 text-sm">Rôle</p>
                    <p class="font-semibold capitalize">{{ auth()->user()->role }}</p>
                </div>
                <div>
                    <p class="text-gray-500 text-sm">Points</p>
                    <p class="font-semibold text-yellow-600">{{ auth()->user()->score_total }}</p>
                </div>
                <div>
                    <p class="text-gray-500 text-sm">Niveau</p>
                    <p class="font-semibold capitalize">{{ auth()->user()->niveau }}</p>
                </div>
                <div>
                    <p class="text-gray-500 text-sm">Statut du compte</p>
                    <p class="font-semibold">{{ auth()->user()->statut_compte }}</p>
                </div>
                <div>
                    <p class="text-gray-500 text-sm">Date d'inscription</p>
                    <p class="font-semibold">{{ \Carbon\Carbon::parse(auth()->user()->date_inscription)->format('d/m/Y') }}
                    </p>
                </div>
            </div>

            <!-- Modifier les informations -->
            <div class="mt-6 pt-4 border-t">
                <h3 class="font-semibold text-gray-700 mb-4">Modifier mes informations</h3>
                <form method="POST" action="{{ route('profil.update') }}" class="space-y-4">
                    @csrf
                    @method('PUT')
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-gray-700 text-sm">Nom</label>
                            <input type="text" name="nom" value="{{ old('nom', auth()->user()->nom) }}"
                                class="w-full border rounded-lg p-2">
                        </div>
                        <div>
                            <label class="block text-gray-700 text-sm">Prénom</label>
                            <input type="text" name="prenom" value="{{ old('prenom', auth()->user()->prenom) }}"
                                class="w-full border rounded-lg p-2">
                        </div>
                        <div>
                            <label class="block text-gray-700 text-sm">Téléphone</label>
                            <input type="text" name="telephone"
                                value="{{ old('telephone', auth()->user()->telephone) }}"
                                class="w-full border rounded-lg p-2">
                        </div>
                        <div>
                            <label class="block text-gray-700 text-sm">Adresse</label>
                            <input type="text" name="adresse" value="{{ old('adresse', auth()->user()->adresse) }}"
                                class="w-full border rounded-lg p-2">
                        </div>
                        <div class="col-span-2">
                            <label class="block text-gray-700 text-sm">Quartier</label>
                            <input type="text" name="quartier" value="{{ old('quartier', auth()->user()->quartier) }}"
                                class="w-full border rounded-lg p-2">
                        </div>
                    </div>
                    <button type="submit"
                        class="bg-emerald-500 text-white px-4 py-2 rounded-lg hover:bg-emerald-600">Mettre à jour</button>
                </form>
            </div>

            <!-- Changer le mot de passe -->
            <div class="mt-6 pt-4 border-t">
                <h3 class="font-semibold text-gray-700 mb-4">Changer le mot de passe</h3>
                <form method="POST" action="{{ route('profil.password') }}" class="space-y-4">
                    @csrf
                    @method('PUT')
                    <div>
                        <label class="block text-gray-700 text-sm">Mot de passe actuel</label>
                        <input type="password" name="mot_passe_actuel" class="w-full border rounded-lg p-2" required>
                    </div>
                    <div>
                        <label class="block text-gray-700 text-sm">Nouveau mot de passe</label>
                        <input type="password" name="mot_passe" class="w-full border rounded-lg p-2" required>
                    </div>
                    <div>
                        <label class="block text-gray-700 text-sm">Confirmer le nouveau mot de passe</label>
                        <input type="password" name="mot_passe_confirmation" class="w-full border rounded-lg p-2" required>
                    </div>
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600">Changer le
                        mot de passe</button>
                </form>
            </div>
        </div>
    </div>
@endsection
