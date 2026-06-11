@extends('layouts.collecteur')

@section('title', 'Tableau de bord')

@section('content')
    <!-- Cartes statistiques -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl shadow p-6 text-white">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-sm opacity-90">Collectes aujourd'hui</p>
                    <p class="text-3xl font-bold">{{ $collectesAujourdhui ?? 0 }}</p>
                </div>
                <div class="text-3xl">🚛</div>
            </div>
        </div>

        <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-xl shadow p-6 text-white">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-sm opacity-90">Collectes ce mois</p>
                    <p class="text-3xl font-bold">{{ $collectesMois ?? 0 }}</p>
                </div>
                <div class="text-3xl"></div>
            </div>
        </div>

        <div class="bg-gradient-to-r from-yellow-500 to-yellow-600 rounded-xl shadow p-6 text-white">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-sm opacity-90">Points gagnés</p>
                    <p class="text-3xl font-bold">{{ $pointsTotal ?? 0 }}</p>
                </div>
                <div class="text-3xl"></div>
            </div>
        </div>
    </div>

    <!-- Bouton Scanner un kit -->
    <div class="mb-4">
        <a href="{{ route('collecteur.scanner') }}"
            class="inline-flex items-center gap-2 bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600 transition">
            <span class="text-xl"></span>
            Scanner un kit
        </a>
    </div>

    <!-- Badge de notifications -->
    <div class="relative inline-block mb-4">
        <button class="bg-gray-200 px-4 py-2 rounded-lg">🔔 Notifications</button>
        @if (isset($nonLues) && $nonLues > 0)
            <span
                class="absolute -top-2 -right-2 bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center">
                {{ $nonLues }}
            </span>
        @endif
    </div>

    <!-- Liste des notifications -->
    <div class="bg-white rounded-xl shadow mb-6">
        <div class="p-4 border-b">
            <h3 class="font-semibold">Notifications</h3>
        </div>
        <div class="divide-y">
            @forelse($notifications ?? [] as $notif)
                <div class="p-3 {{ !$notif->est_lu ? 'bg-blue-50' : '' }}">
                    <p class="font-medium">{{ $notif->titre }}</p>
                    <p class="text-sm text-gray-600">{{ $notif->message }}</p>
                    <p class="text-xs text-gray-400 mt-1">{{ $notif->created_at->diffForHumans() }}</p>
                </div>
            @empty
                <div class="p-4 text-center text-gray-500">
                    Aucune notification
                </div>
            @endforelse
        </div>
    </div>

    <!-- ============================================= -->
    <!-- KITS À LIVRER AVEC QR CODE DIRECTEMENT        -->
    <!-- ============================================= -->
    <div class="bg-white rounded-xl shadow mb-6">
        <div class="p-4 border-b">
            <h3 class="font-semibold"> Kits à livrer</h3>
        </div>
        <div class="divide-y">
            @forelse($kitsALivrer ?? [] as $kit)
                <div class="p-4">
                    <div class="flex justify-between items-start mb-3">
                        <div>
                            <p class="font-medium">{{ $kit->user->prenom }} {{ $kit->user->nom }}</p>
                            <p class="text-sm text-gray-500">{{ $kit->user->adresse }}</p>
                            <p class="text-xs text-gray-400">Type: {{ $kit->type_kit }}</p>
                            <p class="text-xs text-gray-400">Code: {{ substr($kit->code_qr, 0, 20) }}...</p>
                        </div>
                        <form method="POST" action="{{ route('collecteur.kit.activer') }}" class="inline">
                            @csrf
                            <input type="hidden" name="kit_id" value="{{ $kit->id }}">
                            <input type="hidden" name="code_qr" value="{{ $kit->code_qr }}">
                            <button type="submit"
                                class="bg-green-500 text-white px-3 py-1 rounded text-sm hover:bg-green-600">
                                Activer ce kit
                            </button>
                        </form>
                    </div>

                    <!-- QR Code visible directement (pas de bouton "Afficher QR") -->
                    <div class="mt-3 p-3 bg-gray-100 rounded-lg text-center">
                        <div class="flex justify-center">
                            {!! QrCode::size(120)->generate($kit->code_qr) !!}
                        </div>
                        <p class="text-xs text-gray-500 mt-2">Scannez ce QR code pour activer le kit</p>
                    </div>
                </div>
            @empty
                <div class="p-4 text-center text-gray-500">
                    Aucun kit à livrer
                </div>
            @endforelse
        </div>
    </div>

    <!-- Prochaines collectes -->
    <div class="bg-white rounded-xl shadow">
        <div class="p-5 border-b">
            <h2 class="text-lg font-bold">📋 Prochaines collectes</h2>
        </div>
        <div class="p-5">
            @if (isset($collectes) && $collectes->count() > 0)
                @foreach ($collectes as $collecte)
                    <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg mb-2">
                        <div>
                            <p class="font-medium">{{ $collecte->user->prenom }} {{ $collecte->user->nom }}</p>
                            <p class="text-sm text-gray-500">{{ $collecte->user->adresse }}</p>
                        </div>
                        <div class="text-right">
                            <span
                                class="text-sm">{{ \Carbon\Carbon::parse($collecte->date_collecte)->format('d/m/Y') }}</span><br>
                            <a href="{{ route('collecteur.collecte.form', $collecte->id) }}"
                                class="bg-blue-500 text-white px-3 py-1 rounded text-sm mt-1 inline-block">
                                📝 Enregistrer collecte
                            </a>
                        </div>
                    </div>
                @endforeach
            @else
                <p class="text-center text-gray-500 py-8">Aucune collecte planifiée</p>
            @endif
        </div>
    </div>
@endsection
