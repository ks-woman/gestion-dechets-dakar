@extends('layouts.collecteur')

@section('title', 'Scanner un kit')

@section('content')
    <div class="bg-white rounded-xl shadow p-6">
        <h1 class="text-2xl font-bold mb-4"> Scanner le QR code du kit</h1>

        <div class="bg-gray-100 p-6 rounded-lg">
            <div class="text-center mb-6">
                <div class="text-6xl mb-4"></div>
                <p class="text-gray-600">Saisissez le code QR du kit</p>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 mb-2">Code QR :</label>
                <input type="text" id="code_qr" class="w-full border rounded p-3 font-mono"
                    placeholder="Saisir le code QR...">
            </div>

            <div class="flex gap-4">
                <button onclick="activerKit()" class="flex-1 bg-green-500 text-white py-2 rounded hover:bg-green-600">
                    Activer le kit
                </button>
                <a href="{{ route('collecteur.dashboard') }}"
                    class="flex-1 bg-gray-300 text-gray-700 py-2 rounded text-center hover:bg-gray-400">
                    Annuler
                </a>
            </div>

            <div id="resultat" class="mt-4 hidden"></div>
        </div>
    </div>

    <script>
        function activerKit() {
            let code = document.getElementById('code_qr').value;
            let resultatDiv = document.getElementById('resultat');

            if (!code) {
                resultatDiv.innerHTML = '<div class="bg-red-100 text-red-700 p-2 rounded">Veuillez saisir un code QR</div>';
                resultatDiv.classList.remove('hidden');
                return;
            }

            resultatDiv.innerHTML = '<div class="bg-yellow-100 text-yellow-700 p-2 rounded">⏳ Activation...</div>';
            resultatDiv.classList.remove('hidden');

            fetch('{{ route('collecteur.kit.activer') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        code_qr: code
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.message) {
                        resultatDiv.innerHTML = '<div class="bg-green-100 text-green-700 p-2 rounded">✅ ' + data
                            .message + '</div>';
                        setTimeout(() => {
                            window.location.href = '{{ route('collecteur.dashboard') }}';
                        }, 2000);
                    } else if (data.error) {
                        resultatDiv.innerHTML = '<div class="bg-red-100 text-red-700 p-2 rounded">❌ ' + data.error +
                            '</div>';
                    }
                })
                .catch(error => {
                    resultatDiv.innerHTML = '<div class="bg-red-100 text-red-700 p-2 rounded">❌ Erreur: ' + error +
                        '</div>';
                });
        }
    </script>
@endsection
