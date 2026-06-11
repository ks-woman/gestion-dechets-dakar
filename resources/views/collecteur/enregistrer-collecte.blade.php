@extends('layouts.collecteur')

@section('title', 'Enregistrer une collecte')

@section('content')
    <div class="bg-white rounded-xl shadow p-6">
        <h1 class="text-2xl font-bold mb-4"> Enregistrer une collecte</h1>

        <!-- Informations client -->
        <div class="mb-6 p-4 bg-gray-50 rounded-lg">
            <p class="font-semibold">Client : {{ $client->prenom }} {{ $client->nom }}</p>
            <p class="text-sm text-gray-500"> {{ $client->adresse }}</p>
            <p class="text-sm text-gray-500"> {{ $client->telephone }}</p>
            <p class="text-sm text-gray-500">Quartier : {{ $client->quartier }}</p>
        </div>

        <form method="POST" action="{{ route('collecteur.collecte.enregistrer') }}">
            @csrf
            <input type="hidden" name="user_id" value="{{ $client->id }}">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                <div class="bg-blue-50 p-4 rounded-lg">
                    <label class="block font-semibold"> Plastiques & Métaux (kg)</label>
                    <input type="number" step="0.01" name="plastiques_metaux" id="plastiques_metaux"
                        class="w-full border rounded p-2 mt-1" required>
                </div>

                <div class="bg-green-50 p-4 rounded-lg">
                    <label class="block font-semibold"> Déchets organiques (kg)</label>
                    <input type="number" step="0.01" name="organiques" id="organiques"
                        class="w-full border rounded p-2 mt-1" required>
                </div>

                <div class="bg-yellow-50 p-4 rounded-lg">
                    <label class="block font-semibold"> Papiers & Cartons (kg)</label>
                    <input type="number" step="0.01" name="papiers_cartons" id="papiers_cartons"
                        class="w-full border rounded p-2 mt-1" required>
                </div>

                <div class="bg-gray-50 p-4 rounded-lg">
                    <label class="block font-semibold"> Autres déchets (kg)</label>
                    <input type="number" step="0.01" name="autres" id="autres"
                        class="w-full border rounded p-2 mt-1" required>
                </div>
            </div>

            <div class="bg-emerald-50 p-4 rounded-lg mb-6">
                <h3 class="font-bold"> Récapitulatif</h3>
                <p>Recyclable : <span id="recyclable">0</span> kg → <span id="points_recyclable">0</span> points</p>
                <p>Organique : <span id="organique_kg">0</span> kg → <span id="points_organique">0</span> points</p>
                <p><strong>Total points : <span id="total_points">0</span></strong></p>
            </div>

            <button type="submit" class="w-full bg-emerald-500 text-white py-2 rounded-lg">✅ Valider la collecte</button>
        </form>
    </div>

    <script>
        const inputs = ['plastiques_metaux', 'organiques', 'papiers_cartons', 'autres'];
        inputs.forEach(id => {
            const el = document.getElementById(id);
            if (el) el.addEventListener('input', calculerPoints);
        });

        function calculerPoints() {
            let plastiques = parseFloat(document.getElementById('plastiques_metaux')?.value) || 0;
            let papiers = parseFloat(document.getElementById('papiers_cartons')?.value) || 0;
            let organiques = parseFloat(document.getElementById('organiques')?.value) || 0;

            let recyclable = plastiques + papiers;
            let pointsRecyclable = recyclable * 1;
            let pointsOrganique = organiques * 0.5;

            document.getElementById('recyclable').innerText = recyclable.toFixed(2);
            document.getElementById('points_recyclable').innerText = pointsRecyclable.toFixed(0);
            document.getElementById('organique_kg').innerText = organiques.toFixed(2);
            document.getElementById('points_organique').innerText = pointsOrganique.toFixed(0);
            document.getElementById('total_points').innerText = (pointsRecyclable + pointsOrganique).toFixed(0);
        }
    </script>
@endsection
