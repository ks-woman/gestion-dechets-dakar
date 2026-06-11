@extends('layouts.app')

@section('content')
    <div class="max-w-md mx-auto bg-white p-6 rounded-lg shadow">
        <h2 class="text-2xl font-bold mb-6 text-center">Inscription - Ménage</h2>

        <form method="POST" action="{{ route('register.menage') }}">
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
                <label class="block mb-2">Adresse / Quartier</label>
                <textarea name="adresse" class="w-full border rounded p-2" required>{{ old('adresse') }}</textarea>
                @error('adresse')
                    <div class="text-red-500 mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-4">
                <label class="block mb-2">Quartier</label>
                <input type="text" name="quartier" id="quartier" class="w-full border rounded p-2" required>
                <input type="hidden" name="latitude" id="latitude">
                <input type="hidden" name="longitude" id="longitude">
                <p class="text-xs text-gray-500 mt-1">Ex: Yoff, Pikine, Guediawaye, Parcelles Assainies</p>
                <div id="geo_status" class="text-xs mt-1"></div>
            </div>

            <div class="mb-4">
                <label class="block mb-2">Nombre de personnes</label>
                <input type="number" name="nombre_personnes" value="{{ old('nombre_personnes', 1) }}"
                    class="w-full border rounded p-2" required>
                @error('nombre_personnes')
                    <div class="text-red-500 mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-4">
                <label class="block mb-2">Type de logement</label>
                <select name="type_logement" class="w-full border rounded p-2" required>
                    <option value="">Sélectionner</option>
                    <option value="villa">Villa</option>
                    <option value="appartement">Appartement</option>
                    <option value="studio">Studio</option>
                    <option value="chambre">Chambre</option>
                    <option value="immeuble_collectif">Immeuble collectif</option>
                </select>
                @error('type_logement')
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
    <script>
        document.getElementById('quartier').addEventListener('blur', function() {
            let quartier = this.value;
            let statusDiv = document.getElementById('geo_status');

            if (!quartier) return;

            statusDiv.innerHTML = ' Recherche du quartier...';
            statusDiv.className = 'text-xs text-blue-500 mt-1';

            fetch(
                    `https://nominatim.openstreetmap.org/search?q=${encodeURIComponent(quartier)}, Dakar, Sénégal&format=json&limit=1`
                    )
                .then(response => response.json())
                .then(data => {
                    if (data.length > 0) {
                        document.getElementById('latitude').value = data[0].lat;
                        document.getElementById('longitude').value = data[0].lon;
                        statusDiv.innerHTML = ' Quartier localisé avec succès !';
                        statusDiv.className = 'text-xs text-green-500 mt-1';
                    } else {
                        statusDiv.innerHTML = ' Quartier non trouvé. Veuillez vérifier l\'orthographe.';
                        statusDiv.className = 'text-xs text-red-500 mt-1';
                    }
                })
                .catch(error => {
                    console.error('Erreur:', error);
                    statusDiv.innerHTML = ' Erreur de localisation. Veuillez réessayer.';
                    statusDiv.className = 'text-xs text-red-500 mt-1';
                });
        });
    </script>
@endsection
