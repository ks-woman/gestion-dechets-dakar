@extends('layouts.collecteur')

@section('title', 'Scanner un kit')

@section('content')
    <div class="bg-white rounded-xl shadow p-6">
        <h1 class="text-2xl font-bold mb-2"> Scanner un kit</h1>
        <p class="text-gray-500 text-sm mb-4">Scannez le QR code présent sur le kit pour l'activer</p>

        <!-- Zone de scan -->
        <div id="reader" style="width: 100%; max-width: 500px; margin: 0 auto;"></div>

        <!-- Résultat du scan -->
        <div id="resultat" class="mt-4 hidden"></div>

        <!-- Fallback : saisie manuelle -->
        <div class="mt-6 pt-4 border-t border-gray-200">
            <p class="text-gray-500 text-sm text-center">Ou saisissez manuellement le code du kit :</p>
            <div class="flex gap-2 mt-2 justify-center">
                <input type="text" id="code_manuel" class="border rounded-lg p-2 w-64 focus:ring-2 focus:ring-blue-500"
                    placeholder="Ex: KIT-ABC123">
                <button onclick="activerKitManuel()"
                    class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition">
                    Activer
                </button>
            </div>
        </div>

        <!-- Bouton retour -->
        <div class="mt-4 text-center">
            <a href="{{ route('collecteur.dashboard') }}" class="text-gray-500 hover:text-gray-700">
                ← Retour au tableau de bord
            </a>
        </div>
    </div>

    <!-- HTML5 QR Code Library -->
    <script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>

    <script>
        let html5QrCode;
        let scanActif = false;

        function onScanSuccess(decodedText, decodedResult) {
            if (scanActif) return;
            scanActif = true;

            console.log(' Code scanné:', decodedText);

            html5QrCode.stop().then(() => {
                activerKit(decodedText);
            }).catch(err => {
                console.log('Erreur arrêt:', err);
                activerKit(decodedText);
            });
        }

        function onScanError(errorMessage) {
            // Ignorer les erreurs de scan
        }

        document.addEventListener('DOMContentLoaded', function() {
            if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
                document.getElementById('reader').innerHTML = `
                <div class="bg-yellow-100 text-yellow-800 p-4 rounded-lg text-center">
                     Votre navigateur ne supporte pas la caméra.<br>
                    Utilisez la saisie manuelle ci-dessous.
                </div>
            `;
                return;
            }

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
                ).then(() => {
                    console.log(' Caméra démarrée');
                }).catch(err => {
                    console.error('Erreur caméra:', err);
                    document.getElementById('reader').innerHTML = `
                    <div class="bg-red-100 text-red-800 p-4 rounded-lg text-center">
                         Impossible d'accéder à la caméra.<br>
                        <span class="text-sm">Vérifiez les permissions ou utilisez la saisie manuelle.</span>
                    </div>
                `;
                });
            } catch (e) {
                console.error('Erreur initialisation:', e);
                document.getElementById('reader').innerHTML = `
                <div class="bg-red-100 text-red-800 p-4 rounded-lg text-center">
                     Erreur d'initialisation.<br>
                    Utilisez la saisie manuelle ci-dessous.
                </div>
            `;
            }
        });

        function activerKit(code) {
            let resultatDiv = document.getElementById('resultat');
            resultatDiv.classList.remove('hidden');
            resultatDiv.innerHTML = `
            <div class="bg-yellow-100 text-yellow-700 p-3 rounded-lg flex items-center gap-2">
                <span class="animate-spin"></span> Activation en cours...
            </div>
        `;

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
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        resultatDiv.innerHTML = `
                    <div class="bg-green-100 text-green-700 p-3 rounded-lg flex items-center gap-2">
                         ${data.message}
                        ${data.menage ? `<br><span class="text-sm">👤 ${data.menage}</span>` : ''}
                    </div>
                `;
                        setTimeout(() => {
                            window.location.href = '{{ route('collecteur.dashboard') }}';
                        }, 2500);
                    } else {
                        resultatDiv.innerHTML = `
                    <div class="bg-red-100 text-red-700 p-3 rounded-lg flex items-center gap-2">
                         ${data.error}
                    </div>
                `;
                        scanActif = false;
                        setTimeout(() => {
                            if (html5QrCode) {
                                html5QrCode.start({
                                        facingMode: "environment"
                                    }, {
                                        fps: 10,
                                        qrbox: {
                                            width: 250,
                                            height: 250
                                        },
                                        aspectRatio: 1.0
                                    },
                                    onScanSuccess,
                                    onScanError
                                );
                            }
                        }, 3000);
                    }
                })
                .catch(error => {
                    console.error('Erreur:', error);
                    resultatDiv.innerHTML = `
                <div class="bg-red-100 text-red-700 p-3 rounded-lg flex items-center gap-2">
                     Erreur de connexion : ${error.message}
                </div>
            `;
                    scanActif = false;
                });
        }

        function activerKitManuel() {
            let code = document.getElementById('code_manuel').value.trim();
            if (!code) {
                alert(' Veuillez saisir un code.');
                return;
            }
            activerKit(code);
        }

        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('code_manuel').addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    activerKitManuel();
                }
            });
        });
    </script>

    <style>
        #reader {
            background: #f3f4f6;
            border-radius: 12px;
            padding: 8px;
        }

        #reader video {
            border-radius: 8px;
        }

        .animate-spin {
            display: inline-block;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            from {
                transform: rotate(0deg);
            }

            to {
                transform: rotate(360deg);
            }
        }
    </style>
@endsection
