@extends('layouts.collecteur')

@section('title', 'Ma tournée')

@section('content')
    <div class="space-y-6">
        <div class="bg-white rounded-xl shadow p-6">
            <h1 class="text-2xl font-bold text-gray-800"> Ma tournée</h1>
            <p class="text-gray-500 mt-1">{{ now()->format('l d/m/Y') }}</p>
            <p class="text-sm text-emerald-600 mt-1"> Quartier : {{ auth()->user()->quartier ?? 'Non défini' }}</p>
        </div>

        <!-- Carte -->
        <div class="bg-white rounded-xl shadow overflow-hidden">
            <div id="map" style="height: 400px; width: 100%;"></div>
        </div>

        <!-- Liste des clients -->
        <div class="bg-white rounded-xl shadow">
            <div class="p-4 border-b bg-blue-50 rounded-t-xl">
                <h2 class="text-lg font-bold text-blue-800"> Clients à collecter</h2>
            </div>
            <div class="p-4">
                @if (isset($clients) && $clients->count() > 0)
                    <div class="space-y-3">
                        @foreach ($clients as $client)
                            <div class="border rounded-lg p-3 flex justify-between items-center">
                                <div>
                                    <p class="font-semibold">{{ $client->prenom }} {{ $client->nom }}</p>
                                    <p class="text-sm text-gray-500"> {{ $client->adresse }}</p>
                                    <p class="text-xs text-gray-400">Quartier: {{ $client->quartier }}</p>
                                </div>
                                <a href="{{ route('collecteur.collecte.form', $client->id) }}"
                                    class="bg-emerald-500 text-white px-3 py-1 rounded text-sm">
                                    ✅ Collecter
                                </a>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-center text-gray-500 py-8">Aucun client dans votre quartier</p>
                @endif
            </div>
        </div>
    </div>

    <!-- Leaflet CSS et JS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <script>
        // Initialiser la carte (centre sur Dakar)
        var map = L.map('map').setView([14.7167, -17.4677], 12);

        // Fond de carte
        L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OSM</a>'
        }).addTo(map);

        // Récupérer les clients depuis le backend
        var clients = @json($clients ?? []);

        console.log('Clients:', clients);

        // Icône personnalisée
        var iconeClient = L.divIcon({
            html: '<div style="background-color: #10b981; width: 12px; height: 12px; border-radius: 50%; border: 2px solid white; box-shadow: 0 0 4px rgba(0,0,0,0.3);"></div>',
            iconSize: [12, 12],
            className: 'custom-marker'
        });

        // Ajouter les marqueurs pour chaque client avec décalage
        var offset = 0.0005;
        clients.forEach((client, index) => {
            if (client.latitude && client.longitude) {
                var lat = parseFloat(client.latitude) + (index * offset);
                var lng = parseFloat(client.longitude) + (index * offset);
                var marker = L.marker([lat, lng], {
                    icon: iconeClient
                }).addTo(map);
                marker.bindPopup(`
                    <b>${client.prenom} ${client.nom}</b><br>
                     ${client.adresse}<br>
                     ${client.telephone}
                `);
            }
        });
    </script>

    <style>
        .custom-marker {
            background: transparent !important;
            border: none !important;
        }
    </style>
@endsection
