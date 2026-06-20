@extends('layouts.collecteur')

@section('title', 'Kits à livrer')

@section('content')
    <div class="bg-white rounded-xl shadow p-6">
        <h1 class="text-2xl font-bold mb-4">📦 Kits à livrer</h1>
        <p class="text-gray-500 mb-6">Scannez le QR code affiché ou cliquez sur "Activer" pour simuler.</p>

        @if ($kits->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach ($kits as $kit)
                    <div class="border rounded-lg p-4 hover:shadow-md transition">
                        <p><strong>Ménage :</strong> {{ $kit->user->prenom }} {{ $kit->user->nom }}</p>
                        <p><strong>Adresse :</strong> {{ $kit->user->adresse }}</p>
                        <p><strong>Code :</strong> {{ $kit->code_qr }}</p>
                        <div class="my-3">
                            {!! \SimpleSoftwareIO\QrCode\Facades\QrCode::size(150)->generate($kit->code_qr) !!}
                        </div>
                        <div class="flex gap-2 mt-2">
                            <a href="{{ route('collecteur.activer-kit.par-scan', $kit->code_qr) }}"
                                class="bg-emerald-500 hover:bg-emerald-600 text-white px-3 py-1 rounded text-sm transition">
                                ✅ Activer (simulation)
                            </a>
                            <button onclick="copierCode('{{ $kit->code_qr }}')"
                                class="bg-gray-200 hover:bg-gray-300 px-3 py-1 rounded text-sm transition">
                                📋 Copier code
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-8 text-gray-500">
                <p>Aucun kit en attente de livraison.</p>
            </div>
        @endif
    </div>

    <script>
        function copierCode(code) {
            navigator.clipboard.writeText(code).then(() => {
                alert('Code copié : ' + code);
            });
        }
    </script>
@endsection
