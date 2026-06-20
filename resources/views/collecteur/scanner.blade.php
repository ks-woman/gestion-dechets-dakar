@extends('layouts.collecteur')

@section('title', 'Scanner un kit')

@section('content')
    <div class="bg-white rounded-xl shadow p-6">
        <h1 class="text-2xl font-bold mb-2">📷 Scanner un kit</h1>
        <p class="text-gray-500 text-sm mb-4">Scannez le QR code présent sur le kit pour l'activer</p>

        <!-- Zone de scan -->
        <div id="reader"
            style="width: 100%; max-width: 500px; margin: 0 auto; min-height: 250px; background: #f3f4f6; border-radius: 12px; padding: 8px;">
            <div id="camera-status" class="text-center p-4 text-gray-500">
                <div class="text-4xl mb-2">📷</div>
                <p>Chargement de la caméra...</p>
                <p class="text-xs text-gray-400">Si la caméra ne fonctionne pas, utilisez les boutons de test ci-dessous.</p>
            </div>
        </div>

        <!-- Résultat du scan -->
        <div id="resultat" class="mt-4 hidden"></div>

        <!-- ============================================= -->
        <!-- 🔥 SECTION TEST : Activer sans caméra          -->
        <!-- ============================================= -->
        <div class="mt-6 pt-4 border-t border-gray-200">
            <p class="text-gray-500 text-sm text-center font-semibold">🧪 Mode test (sans caméra)</p>

            <div class="flex flex-wrap gap-2 justify-center mt-2">
                @php
                    // Récupérer les 5 derniers kits en attente
                    $kitsEnAttente = \App\Models\KitTri::where('statut', 'en_attente')
                        ->with('user')
                        ->orderBy('created_at', 'desc')
                        ->take(5)
                        ->get();
                @endphp

                @forelse($kitsEnAttente as $kit)
                    <button onclick="activerKit('{{ $kit->code_qr }}')"
                        class="bg-emerald-500 hover:bg-emerald-600 text-white px-3 py-1 rounded text-xs transition">
                        {{ $kit->user->prenom ?? 'N/A' }} ({{ $kit->code_qr }})
                    </button>
                @empty
                    <p class="text-xs text-gray-400">Aucun kit en attente. Créez-en un depuis un compte ménage.</p>
                @endforelse
            </div>

            <p class="text-xs text-gray-400 text-center mt-2">
                Cliquez sur un bouton ci-dessus pour activer le kit correspondant (simulation).
            </p>
        </div>

        <!-- Saisie manuelle -->
        <div class="mt-4 pt-4 border-t border-gray-200">
            <p class="text-gray-500 text-sm text-center">Ou saisissez manuellement :</p>
            <div class="flex gap-2 mt-2 justify-center">
                <input type="text" id="code_manuel" class="border rounded-lg p-2 w-64" placeholder="KIT-ABC123">
                <button onclick="activerKitManuel()" class="bg-blue-500 text-white px-4 py-2 rounded-lg">Activer</button>
            </div>
        </div>

        <!-- Bouton retour -->
        <div class="mt-4 text-center">
            <a href="{{ route('collecteur.dashboard') }}" class="text-gray-500">← Retour</a>
        </div>
    </div>

    <!-- HTML5 QR Code Library -->
    <script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>

    <script>
        let html5QrCode;
        let scanActif = false;

        // ==== GESTION DE LA CAMÉRA ====
        function demarrerCamera() {
            const statusDiv = document.getElementById('camera-status');
            statusDiv.innerHTML = '<p class="text-blue-500">⏳ Tentative de démarrage...</p>';

            if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
                statusDiv.innerHTML = '<p class="text-red-500">❌ Votre navigateur ne supporte pas la caméra.</p>';
                return;
            }

            navigator.mediaDevices.getUserMedia({
                    video: {
                        facingMode: "environment"
                    }
                })
                .then(stream => {
                    stream.getTracks().forEach(track => track.stop());
                    lancerScanner();
                })
                .catch(err => {
                    console.error('Erreur caméra:', err);
                    let msg = 'Impossible d\'accéder à la caméra.';
                    if (err.name === 'NotAllowedError') msg = 'Permission refusée. Autorisez la caméra.';
                    else if (err.name === 'NotFoundError') msg = 'Aucune caméra trouvée.';
                    else if (err.name === 'NotReadableError') msg = 'La caméra est utilisée par une autre application.';
                    statusDiv.innerHTML =
                        `<p class="text-red-500">❌ ${msg}</p><p class="text-sm text-gray-500">Utilisez les boutons de test ou la saisie manuelle.</p>`;
                });
        }

        function lancerScanner() {
            const statusDiv = document.getElementById('camera-status');
            statusDiv.innerHTML = '<p class="text-green-500">✅ Caméra prête, scan en cours...</p>';

            try {
                html5QrCode = new Html5Qrcode("reader");
                const config = {
                    fps: 10,
                    qrbox: {
                        width: 250,
                        height: 250
                    },
                    aspectRatio: 1.0
                };
                html5QrCode.start({
                        facingMode: "environment"
                    },
                    config,
                    onScanSuccess,
                    onScanError
                );
            } catch (e) {
                console.error('Erreur scanner:', e);
                statusDiv.innerHTML = '<p class="text-red-500">❌ Erreur d\'initialisation du scanner.</p>';
            }
        }

        function onScanSuccess(decodedText) {
            if (scanActif) return;
            scanActif = true;
            html5QrCode.stop().then(() => activerKit(decodedText));
        }

        function onScanError(err) {}

        // ==== ACTIVATION DU KIT ====
        function activerKit(code) {
            const resultat = document.getElementById('resultat');
            resultat.classList.remove('hidden');
            resultat.innerHTML = '<div class="bg-yellow-100 text-yellow-700 p-3 rounded">⏳ Activation...</div>';

            fetch('{{ route('collecteur.kit.activer') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        code_kit: code
                    })
                })
                .then(r => r.json())
                .then(data => {
                    if (data.success) {
                        resultat.innerHTML =
                            `<div class="bg-green-100 text-green-700 p-3 rounded">✅ ${data.message}</div>`;
                        setTimeout(() => window.location.href = '{{ route('collecteur.dashboard') }}', 2000);
                    } else {
                        resultat.innerHTML = `<div class="bg-red-100 text-red-700 p-3 rounded">❌ ${data.error}</div>`;
                        scanActif = false;
                    }
                })
                .catch(err => {
                    resultat.innerHTML =
                        `<div class="bg-red-100 text-red-700 p-3 rounded">❌ Erreur: ${err.message}</div>`;
                    scanActif = false;
                });
        }

        function activerKitManuel() {
            const code = document.getElementById('code_manuel').value.trim();
            if (!code) {
                alert('Saisissez un code.');
                return;
            }
            activerKit(code);
        }

        // Démarrer la caméra au chargement
        document.addEventListener('DOMContentLoaded', demarrerCamera);
    </script>
@endsection
