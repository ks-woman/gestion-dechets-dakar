@extends('layouts.app')

@section('title', 'Inscription Collecteur')

@section('content')
    <div class="bg-white rounded-2xl shadow-2xl p-8 max-w-md mx-auto">
        <div class="text-center mb-6">
            <div class="text-4xl mb-2"></div>
            <h1 class="text-2xl font-bold text-gray-800">Créer un compte</h1>
            <p class="text-gray-500 text-sm mt-1">Inscription en tant que collecteur</p>
        </div>

        @if ($errors->any())
            <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-3 rounded-lg mb-4 text-sm">
                <i class="fas fa-exclamation-circle mr-2"></i>
                @foreach ($errors->all() as $error)
                    {{ $error }}<br>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('register.collecteur') }}" class="space-y-4">
            @csrf

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-gray-700 text-sm font-medium mb-1">Nom</label>
                    <input type="text" name="nom" value="{{ old('nom') }}"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-emerald-500"
                        required>
                    @error('nom')
                        <div class="text-red-500 text-xs mt-1">{{ $message }}</div>
                    @enderror
                </div>
                <div>
                    <label class="block text-gray-700 text-sm font-medium mb-1">Prénom</label>
                    <input type="text" name="prenom" value="{{ old('prenom') }}"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-emerald-500"
                        required>
                    @error('prenom')
                        <div class="text-red-500 text-xs mt-1">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div>
                <label class="block text-gray-700 text-sm font-medium mb-1">Email</label>
                <input type="email" name="email" value="{{ old('email') }}"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-emerald-500" required>
                @error('email')
                    <div class="text-red-500 text-xs mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div>
                <label class="block text-gray-700 text-sm font-medium mb-1">Téléphone</label>
                <input type="tel" name="telephone" value="{{ old('telephone') }}"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-emerald-500" required>
                @error('telephone')
                    <div class="text-red-500 text-xs mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div>
                <label class="block text-gray-700 text-sm font-medium mb-1">Adresse</label>
                <input type="text" name="adresse" value="{{ old('adresse') }}"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-emerald-500" required>
                @error('adresse')
                    <div class="text-red-500 text-xs mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div>
                <label class="block text-gray-700 text-sm font-medium mb-1">Quartier</label>
                <input type="text" name="quartier" value="{{ old('quartier') }}"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-emerald-500" required>
                @error('quartier')
                    <div class="text-red-500 text-xs mt-1">{{ $message }}</div>
                @enderror
            </div>

            <!-- ✅ Matricule (obligatoire et unique) -->
            <div>
                <label class="block text-gray-700 text-sm font-medium mb-1">Matricule</label>
                <input type="text" name="matricule" value="{{ old('matricule') }}"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-emerald-500" required>
                <p class="text-xs text-gray-500 mt-1">Ex: COL001, COL002, etc.</p>
                @error('matricule')
                    <div class="text-red-500 text-xs mt-1">{{ $message }}</div>
                @enderror
            </div>

            <!-- ✅ Type de véhicule -->
            <div>
                <label class="block text-gray-700 text-sm font-medium mb-1">Type de véhicule</label>
                <select name="vehicule_type"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-emerald-500" required>
                    <option value="">Sélectionner</option>
                    <option value="charette" {{ old('vehicule_type') == 'charette' ? 'selected' : '' }}>Charette</option>
                    <option value="motocycliste" {{ old('vehicule_type') == 'motocycliste' ? 'selected' : '' }}>
                        Motocycliste</option>
                    <option value="camion" {{ old('vehicule_type') == 'camion' ? 'selected' : '' }}>Camion</option>
                </select>
                @error('vehicule_type')
                    <div class="text-red-500 text-xs mt-1">{{ $message }}</div>
                @enderror
            </div>

            <!-- ✅ Zone de couverture (optionnel) -->
            <div>
                <label class="block text-gray-700 text-sm font-medium mb-1">Zone de couverture <span
                        class="text-gray-400 text-xs">(optionnel)</span></label>
                <input type="text" name="zone_couverture" value="{{ old('zone_couverture') }}"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-emerald-500">
                @error('zone_couverture')
                    <div class="text-red-500 text-xs mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div>
                <label class="block text-gray-700 text-sm font-medium mb-1">Mot de passe</label>
                <input type="password" name="mot_passe"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-emerald-500" required>
                @error('mot_passe')
                    <div class="text-red-500 text-xs mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div>
                <label class="block text-gray-700 text-sm font-medium mb-1">Confirmer le mot de passe</label>
                <input type="password" name="mot_passe_confirmation"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-emerald-500" required>
            </div>

            <button type="submit" class="btn-primary w-full py-3 rounded-lg text-base font-semibold">
                <i class="fas fa-user-plus mr-2"></i> S'inscrire
            </button>
        </form>

        <p class="text-center text-gray-500 text-sm mt-6">
            Déjà inscrit ?
            <a href="{{ route('login') }}" class="text-emerald-600 hover:text-emerald-700 font-medium">
                Se connecter
            </a>
        </p>
    </div>
@endsection
