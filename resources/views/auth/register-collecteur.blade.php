@extends('layouts.app')

@section('content')
    <div class="max-w-md mx-auto bg-white p-6 rounded-lg shadow">
        <h2 class="text-2xl font-bold mb-6 text-center">Inscription - Collecteur</h2>

        <form method="POST" action="{{ route('register.collecteur') }}">
            @csrf

            <div class="mb-4">
                <label class="block mb-2">Nom</label>
                <input type="text" name="nom" value="{{ old('nom') }}" class="w-full border rounded p-2" required>
                @error('nom')
                    <div class="text-red-500 mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-4">
                <label class="block mb-2">Prénom</label>
                <input type="text" name="prenom" value="{{ old('prenom') }}" class="w-full border rounded p-2" required>
                @error('prenom')
                    <div class="text-red-500 mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-4">
                <label class="block mb-2">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" class="w-full border rounded p-2" required>
                @error('email')
                    <div class="text-red-500 mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-4">
                <label class="block mb-2">Téléphone</label>
                <input type="tel" name="telephone" value="{{ old('telephone') }}" class="w-full border rounded p-2"
                    required>
                @error('telephone')
                    <div class="text-red-500 mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-4">
                <label class="block mb-2">Adresse</label>
                <textarea name="adresse" class="w-full border rounded p-2" required>{{ old('adresse') }}</textarea>
                @error('adresse')
                    <div class="text-red-500 mt-1">{{ $message }}</div>
                @enderror
            </div>

            <!-- ===== QUARTIER AVEC GÉOLOCALISATION ===== -->
            <div class="mb-4">
                <label class="block mb-2">Quartier</label>
                <input type="text" name="quartier" id="quartier" class="w-full border rounded p-2" required>
                <input type="hidden" name="latitude" id="latitude">
                <input type="hidden" name="longitude" id="longitude">
                <div id="geo_status" class="text-xs mt-1"></div>
                <p class="text-xs text-gray-500 mt-1">Ex: Yoff, Pikine, Guediawaye, Parcelles Assainies</p>
            </div>

            <div class="mb-4">
                <label class="block mb-2">Matricule</label>
                <input type="text" name="matricule" value="{{ old('matricule') }}" class="w-full border rounded p-2"
                    required>
                @error('matricule')
                    <div class="text-red-500 mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-4">
                <label class="block mb-2">Type de véhicule</label>
                <select name="vehicule_type" class="w-full border rounded p-2" required>
                    <option value="">Sélectionner</option>
                    <option value="charette">Charrette</option>
                    <option value="motocycliste">Moto</option>
                    <option value="camion">Camion</option>
                </select>
                @error('vehicule_type')
                    <div class="text-red-500 mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-4">
                <label class="block mb-2">Zone de couverture</label>
                <input type="text" name="zone_couverture" value="{{ old('zone_couverture') }}"
                    class="w-full border rounded p-2" placeholder="Ex: Pikine, Guediawaye">
                @error('zone_couverture')
                    <div class="text-red-500 mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-4">
                <label class="block mb-2">Mot de passe</label>
                <input type="password" name="mot_passe" class="w-full border rounded p-2" required>
                @error('mot_passe')
                    <div class="text-red-500 mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-4">
                <label class="block mb-2">Confirmer le mot de passe</label>
                <input type="password" name="mot_passe_confirmation" class="w-full border rounded p-2" required>
            </div>

            <button type="submit" class="w-full bg-green-500 text-white p-2 rounded">S'inscrire</button>
        </form>

        <p class="mt-4 text-center">Déjà inscrit ? <a href="{{ route('login') }}" class="text-blue-500">Se connecter</a>
        </p>
    </div>

    <!-- Script de géolocalisation -->
    <script>
        document.getElementById('quartier').addEventListener('blur', function() {
            let quartier = this.value;
            let statusDiv = document.getElementById('geo_status');

            if (!quartier) {
                statusDiv.innerHTML = '';
                return;
            }

            statusDiv.innerHTML = ' Recherche du quartier...';
            statusDiv.style.color = 'blue';

            fetch(
                    `https://nominatim.openstreetmap.org/search?q=${encodeURIComponent(quartier)}, Dakar, Sénégal&format=json&limit=1`
                )
                .then(response => response.json())
                .then(data => {
                    if (data.length > 0) {
                        document.getElementById('latitude').value = data[0].lat;
                        document.getElementById('longitude').value = data[0].lon;
                        statusDiv.innerHTML = ' Quartier localisé avec succès !';
                        statusDiv.style.color = 'green';
                    } else {
                        statusDiv.innerHTML = ' Quartier non trouvé. Vérifiez l\'orthographe.';
                        statusDiv.style.color = 'red';
                    }
                })
                .catch(error => {
                    console.error('Erreur:', error);
                    statusDiv.innerHTML = ' Erreur de connexion. Réessayez.';
                    statusDiv.style.color = 'red';
                });
        });
    </script>
@endsection
