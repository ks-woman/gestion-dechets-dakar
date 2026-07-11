@extends('layouts.collecteur')

@section('title', 'Tableau de bord')

@section('content')
    <div class="space-y-6">
        <!-- Bannière -->
        <div class="bg-gradient-to-r from-emerald-600 to-emerald-800 rounded-xl shadow-soft p-6 text-white">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-bold">Bonjour {{ auth()->user()->prenom }} !</h1>
                    <p class="text-emerald-100 mt-1">Tableau de bord collecteur</p>
                </div>
                <div class="text-5xl"></div>
            </div>
        </div>

        <!-- Cartes statistiques -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="card">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-gray-500 text-sm">Collectes aujourd'hui</p>
                        <p class="text-2xl font-bold text-emerald-600">{{ $collectesAujourdhui ?? 0 }}</p>
                    </div>
                    <i class="fas fa-calendar-day text-3xl text-emerald-500"></i>
                </div>
            </div>
            <div class="card">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-gray-500 text-sm">Collectes ce mois</p>
                        <p class="text-2xl font-bold text-emerald-600">{{ $collectesMois ?? 0 }}</p>
                    </div>
                    <i class="fas fa-calendar-alt text-3xl text-emerald-500"></i>
                </div>
            </div>
            <div class="card">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-gray-500 text-sm">Kits à livrer</p>
                        <p class="text-2xl font-bold text-emerald-600">{{ $kitsALivrer->count() ?? 0 }}</p>
                    </div>
                    <i class="fas fa-box text-3xl text-emerald-500"></i>
                </div>
            </div>
            <div class="card">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-gray-500 text-sm">Notifications</p>
                        <p class="text-2xl font-bold text-emerald-600">{{ $nonLues ?? 0 }}</p>
                    </div>
                    <i class="fas fa-bell text-3xl text-emerald-500"></i>
                </div>
            </div>
        </div>

        <!-- Actions rapides -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
            <a href="{{ route('collecteur.tournee') }}" class="btn-primary text-center">
                <i class="fas fa-map-marked-alt mr-2"></i> Ma tournée
            </a>
            <a href="{{ route('collecteur.scanner') }}" class="btn-primary text-center">
                <i class="fas fa-qrcode mr-2"></i> Scanner kit
            </a>
            <a href="{{ route('collecteur.activer-kit') }}" class="btn-primary text-center">
                <i class="fas fa-box mr-2"></i> Activer kit
            </a>
            <a href="{{ route('collecteur.enregistrer-collecte') }}" class="btn-primary text-center">
                <i class="fas fa-clipboard-list mr-2"></i> Enregistrer
            </a>
        </div>

        <!-- 📦 Commandes à livrer -->
        <div class="bg-white rounded-xl shadow-soft p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold text-gray-800 flex items-center gap-2">
                    <i class="fas fa-truck text-blue-500"></i> Commandes à livrer
                    <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded-full">
                        {{ $commandesALivrer->count() ?? 0 }}
                    </span>
                </h2>
            </div>

            @if (isset($commandesALivrer) && $commandesALivrer->count() > 0)
                <div class="space-y-3">
                    @foreach ($commandesALivrer as $commande)
                        <div
                            class="flex flex-col md:flex-row justify-between items-start md:items-center p-4 bg-gray-50 rounded-lg border border-gray-100 hover:border-blue-200 transition">
                            <div class="mb-3 md:mb-0">
                                <div class="flex items-center gap-2">
                                    <span class="font-medium text-gray-800">Commande #{{ $commande->id }}</span>
                                    <span class="bg-blue-100 text-blue-800 text-xs px-2 py-0.5 rounded-full">À livrer</span>
                                </div>
                                <p class="text-sm text-gray-600 mt-1">
                                    <i class="fas fa-user mr-1"></i> Partenaire : {{ $commande->partenaire->nom }}
                                    {{ $commande->partenaire->prenom }}
                                </p>
                                <p class="text-sm text-gray-500">
                                    <i class="fas fa-tag mr-1"></i> {{ $commande->categorie->nom ?? 'N/A' }} -
                                    <span class="font-semibold">{{ number_format($commande->quantite, 1) }} kg</span>
                                </p>
                                @if ($commande->date_affectation)
                                    <p class="text-xs text-gray-400 mt-1">
                                        <i class="fas fa-clock mr-1"></i> Affectée le
                                        {{ $commande->date_affectation->format('d/m/Y H:i') }}
                                    </p>
                                @endif
                            </div>
                            <form method="POST" action="{{ route('collecteur.commande.livrer', $commande->id) }}"
                                class="w-full md:w-auto">
                                @csrf
                                @method('PUT')
                                <button type="submit"
                                    onclick="return confirm('Confirmer la livraison de la commande #{{ $commande->id }} ?')"
                                    class="w-full md:w-auto bg-emerald-500 hover:bg-emerald-600 text-white px-5 py-2 rounded-lg text-sm transition font-medium">
                                    <i class="fas fa-check mr-1"></i> Confirmer la livraison
                                </button>
                            </form>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8 text-gray-500">
                    <i class="fas fa-check-circle text-4xl mb-3 block text-emerald-400"></i>
                    <p class="font-medium">Aucune commande à livrer</p>
                    <p class="text-sm text-gray-400">Vous êtes à jour !</p>
                </div>
            @endif
        </div>

        <!-- 📦 Kits à livrer -->
        <div class="bg-white rounded-xl shadow-soft p-6">
            <h2 class="text-lg font-semibold text-gray-800 flex items-center gap-2 mb-4">
                <i class="fas fa-box text-emerald-500"></i> Kits à livrer
                <span class="bg-emerald-100 text-emerald-800 text-xs font-medium px-2.5 py-0.5 rounded-full">
                    {{ $kitsALivrer->count() ?? 0 }}
                </span>
            </h2>
            @if (isset($kitsALivrer) && $kitsALivrer->count() > 0)
                <div class="space-y-3">
                    @foreach ($kitsALivrer as $kit)
                        <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                            <div>
                                <p class="font-medium">{{ $kit->user->prenom }} {{ $kit->user->nom }}</p>
                                <p class="text-sm text-gray-500">{{ $kit->user->adresse }}</p>
                                <p class="text-xs text-gray-400">Type: {{ $kit->type_kit }}</p>
                            </div>
                            <a href="{{ route('collecteur.scanner') }}" class="btn-primary text-sm">
                                <i class="fas fa-qrcode mr-1"></i> Scanner
                            </a>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8 text-gray-500">
                    <i class="fas fa-box-open text-4xl mb-2 block"></i>
                    <p>Aucun kit à livrer</p>
                </div>
            @endif
        </div>

        <!--  Notifications -->
        <div class="bg-white rounded-xl shadow-soft p-6">
            <h2 class="text-lg font-semibold text-gray-800 flex items-center gap-2 mb-4">
                <i class="fas fa-bell text-emerald-500"></i> Notifications
                @if (isset($nonLues) && $nonLues > 0)
                    <span class="bg-red-500 text-white text-xs font-medium px-2.5 py-0.5 rounded-full">
                        {{ $nonLues }} non lue(s)
                    </span>
                @endif
            </h2>
            @if (isset($notifications) && $notifications->count() > 0)
                <div class="space-y-2">
                    @foreach ($notifications->take(5) as $notification)
                        <div
                            class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg {{ $notification->est_lu ? '' : 'border-l-4 border-emerald-500' }}">
                            <div class="flex-1">
                                <p class="text-sm font-medium">{{ $notification->titre }}</p>
                                <p class="text-xs text-gray-500">{{ $notification->message }}</p>
                                <p class="text-xs text-gray-400">{{ $notification->created_at->diffForHumans() }}</p>
                            </div>
                            @if (!$notification->est_lu)
                                <span class="badge-success text-xs">Nouveau</span>
                            @endif
                        </div>
                    @endforeach
                </div>
                @if ($notifications->count() > 5)
                    <div class="text-center mt-3">
                        <a href="#" class="text-emerald-600 hover:text-emerald-700 text-sm font-medium">
                            Voir toutes les notifications →
                        </a>
                    </div>
                @endif
            @else
                <div class="text-center py-4 text-gray-500">
                    <i class="fas fa-bell-slash text-2xl mb-2 block"></i>
                    <p>Aucune notification</p>
                </div>
            @endif
        </div>

    </div>
@endsection
