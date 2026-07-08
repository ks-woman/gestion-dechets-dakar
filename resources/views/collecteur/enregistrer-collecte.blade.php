@extends('layouts.collecteur')

@section('title', 'Enregistrer une collecte')

@section('content')
    <div class="bg-white rounded-xl shadow-soft p-6 max-w-2xl mx-auto">
        <h1 class="text-2xl font-bold text-gray-800 mb-4"> Enregistrer une collecte</h1>

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
                <div class="bg-blue-50 p-4 rounded-lg border-l-4 border-blue-500">
                    <label class="block font-semibold text-blue-800"> Plastiques & Métaux</label>
                    <input type="number" step="0.01" name="plastiques_metaux" id="plastiques_metaux"
                        class="w-full border rounded p-2 mt-1" placeholder="0.00 kg" required>
                </div>
                <div class="bg-green-50 p-4 rounded-lg border-l-4 border-green-500">
                    <label class="block font-semibold text-green-800"> Déchets organiques</label>
                    <input type="number" step="0.01" name="organiques" id="organiques"
                        class="w-full border rounded p-2 mt-1" placeholder="0.00 kg" required>
                </div>
                <div class="bg-yellow-50 p-4 rounded-lg border-l-4 border-yellow-500">
                    <label class="block font-semibold text-yellow-800"> Papiers & Cartons</label>
                    <input type="number" step="0.01" name="papiers_cartons" id="papiers_cartons"
                        class="w-full border rounded p-2 mt-1" placeholder="0.00 kg" required>
                </div>
                <div class="bg-gray-50 p-4 rounded-lg border-l-4 border-gray-500">
                    <label class="block font-semibold text-gray-800"> Autres déchets</label>
                    <input type="number" step="0.01" name="autres" id="autres"
                        class="w-full border rounded p-2 mt-1" placeholder="0.00 kg" required>
                </div>
            </div>

            <div class="bg-emerald-50 p-4 rounded-lg mb-6 border-l-4 border-emerald-500">
                <h3 class="font-bold text-emerald-800"> Récapitulatif</h3>
                <div class="grid grid-cols-2 gap-2 mt-2 text-sm">
                    <p> Recyclable : <span id="recyclable">0.00</span> kg</p>
                    <p> Organique : <span id="organique_kg">0.00</span> kg</p>
                    <p> Total : <span id="total_kg">0.00</span> kg</p>
                    <p> Points : <span id="total_points">0</span> pts</p>
                </div>
            </div>

            <button type="submit" class="btn-primary w-full"> Valider la collecte</button>
        </form>
    </div>

    <script>
        ['plastiques_metaux', 'organiques', 'papiers_cartons', 'autres'].forEach(id => document.getElementById(id)
            .addEventListener('input', calculerPoints));

        function calculerPoints() {
            let plastiques = parseFloat(document.getElementById('plastiques_metaux')?.value) || 0;
            let papiers = parseFloat(document.getElementById('papiers_cartons')?.value) || 0;
            let organiques = parseFloat(document.getElementById('organiques')?.value) || 0;
            let autres = parseFloat(document.getElementById('autres')?.value) || 0;
            let recyclable = plastiques + papiers;
            let total = recyclable + organiques + autres;
            let points = (recyclable * 1) + (organiques * 0.5);
            document.getElementById('recyclable').innerText = recyclable.toFixed(2);
            document.getElementById('organique_kg').innerText = organiques.toFixed(2);
            document.getElementById('total_kg').innerText = total.toFixed(2);
            document.getElementById('total_points').innerText = Math.round(points);
        }
    </script>
@endsection
