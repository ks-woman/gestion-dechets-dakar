@extends('layouts.collecteur')

@section('title', 'Ma tournée')

@section('content')
    <div class="space-y-6">
        <div class="bg-white rounded-xl shadow p-6">
            <h1 class="text-2xl font-bold text-gray-800">🚛 Ma tournée</h1>
            <p class="text-gray-500 mt-1">{{ now()->format('l d/m/Y') }}</p>
            <p class="text-sm text-emerald-600 mt-1">
                📍 Quartier : {{ auth()->user()->quartier ?? 'Non défini' }}
                @if (auth()->user()->quartier)
                    <span class="ml-2 text-xs bg-emerald-100 text-emerald-800 px-2 py-0.5 rounded-full">
                        {{ $clients->count() ?? 0 }} client(s)
                    </span>
                @endif
            </p>
        </div>

        <!-- Carte -->
        <div class="bg-white rounded-xl shadow overflow-hidden">
            <div id="map" style="height: 450px; width: 100%;"></div>
        </div>

        <!-- Liste des clients -->
        <div class="bg-white rounded-xl shadow">
            <div class="p-4 border-b bg-blue-50 rounded-t-xl">
                <h2 class="text-lg font-bold text-blue-800">
                    <i class="fas fa-users mr-2"></i> Clients à collecter aujourd'hui
                </h2>
            </div>
            <div class="p-4">
                @if (isset($clients) && $clients->count() > 0)
                    <div class="space-y-3">
                        @foreach ($clients as $client)
                            @php
                                $collecteDuJour = $client->collectes
                                    ->where('statut', 'planifiee')
                                    ->whereDate('date_collecte', today())
                                    ->first();
                            @endphp
                            @if ($collecteDuJour)
                                <div
                                    class="border rounded-lg p-3 flex justify-between items-center hover:bg-gray-50 transition">
                                    <div>
                                        <p class="font-semibold">{{ $client->prenom }} {{ $client->nom }}</p>
                                        <p class="text-sm text-gray-500">📍 {{ $client->adresse }}</p>
                                        <p class="text-xs text-gray-400">Quartier: {{ $client->quartier }}</p>
                                        @if ($client->latitude && $client->longitude)
                                            <span class="text-xs text-green-600">📍 Localisé</span>
                                        @else
                                            <span class="text-xs text-orange-500">⚠️ Coordonnées manquantes</span>
                                        @endif
                                    </div>
                                    <a href="{{ route('collecteur.collecte.form', $client->id) }}"
                                        class="bg-emerald-500 hover:bg-emerald-600 text-white px-4 py-2 rounded-lg text-sm transition">
                                        <i class="fas fa-check-circle mr-1"></i> Collecter
                                    </a>
                                </div>
                            @endif
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8 text-gray-500">
                        <i class="fas fa-calendar-day text-4xl mb-2 block"></i>
                        <p>Aucun client n'a de collecte planifiée aujourd'hui dans votre quartier.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Leaflet CSS et JS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialiser la carte (centre sur Dakar)
            var map = L.map('map').setView([14.7167, -17.4677], 13);

            // Fond de carte
            L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OSM</a>'
            }).addTo(map);

            // Récupérer les clients depuis le backend
            var clients = @json($clients ?? []);

            console.log('Clients chargés:', clients);

            // Icône personnalisée pour les clients
            var iconeClient = L.divIcon({
                html: '<div style="background-color: #10b981; width: 14px; height: 14px; border-radius: 50%; border: 3px solid white; box-shadow: 0 0 8px rgba(0,0,0,0.3);"></div>',
                iconSize: [14, 14],
                className: 'custom-marker'
            });

            // Compteur de marqueurs ajoutés
            var marqueursAjoutes = 0;

            // Parcourir les clients
            clients.forEach(function(client) {
                if (client.latitude && client.longitude) {
                    var lat = parseFloat(client.latitude);
                    var lng = parseFloat(client.longitude);

                    // Vérifier que les coordonnées sont valides
                    if (!isNaN(lat) && !isNaN(lng)) {
                        var marker = L.marker([lat, lng], {
                            icon: iconeClient
                        }).addTo(map);

                        // Contenu du popup
                        var collecte = client.collectes ? client.collectes.find(c => c.statut ===
                            'planifiee' && c.date_collecte === '{{ today()->toDateString() }}') : null;
                        var heures = collecte ? new Date(collecte.date_collecte).toLocaleTimeString(
                            'fr-FR', {
                                hour: '2-digit',
                                minute: '2-digit'
                            }) : 'Non défini';

                        marker.bindPopup(`
                            <div style="font-size:14px;">
                                <strong>${client.prenom} ${client.nom}</strong><br>
                                📍 ${client.adresse}<br>
                                📞 ${client.telephone || 'Non renseigné'}<br>
                                🕒 Collecte prévue : ${heures}
                                <br><br>
                                <a href="{{ route('collecteur.collecte.form', '') }}/${client.id}"
                                   style="background:#10b981; color:white; padding:4px 12px; border-radius:4px; text-decoration:none;">
                                   ✅ Collecter
                                </a>
                            </div>
                        `);

                        marqueursAjoutes++;
                    }
                }
            });

            // Si aucun marqueur n'a été ajouté, afficher un message dans la carte
            if (marqueursAjoutes === 0) {
                // Ajouter un marqueur factice pour indiquer qu'aucun client n'est localisé
                var noDataMarker = L.marker([14.7167, -17.4677], {
                    icon: L.divIcon({
                        html: '<div style="background-color: #f59e0b; width: 20px; height: 20px; border-radius: 50%; border: 2px solid white; text-align:center; line-height:20px; font-size:12px;">⚠️</div>',
                        iconSize: [20, 20]
                    })
                }).addTo(map);
                noDataMarker.bindPopup('Aucun client localisé dans ce quartier.');

                // Centrer sur Dakar
                map.setView([14.7167, -17.4677], 12);
            } else {
                // Ajuster la vue pour voir tous les marqueurs (si plus d'un)
                var group = L.featureGroup();
                map.eachLayer(function(layer) {
                    if (layer instanceof L.Marker) {
                        group.addLayer(layer);
                    }
                });
                if (group.getLayers().length > 0) {
                    map.fitBounds(group.getBounds(), {
                        padding: [50, 50]
                    });
                }
            }

            // Ajouter une échelle
            L.control.scale({
                position: 'bottomright'
            }).addTo(map);

            // Option : ajouter un contrôle de localisation de l'utilisateur
            // L.control.locate({ position: 'topright' }).addTo(map);
        });
    </script>

    <style>
        .custom-marker {
            background: transparent !important;
            border: none !important;
        }

        /* Ajustement du popup */
        .leaflet-popup-content {
            font-size: 14px;
            line-height: 1.5;
        }

        .leaflet-popup-content a {
            display: inline-block;
            margin-top: 5px;
        }
    </style>
@endsection
