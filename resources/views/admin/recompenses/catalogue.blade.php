@extends('layouts.menage')

@section('title', 'Catalogue des récompenses')

@section('content')
    <div class="space-y-6">
        <!-- En-tête avec points -->
        <div class="bg-gradient-to-r from-yellow-500 to-yellow-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-bold">🎁 Catalogue des récompenses</h1>
                    <p class="text-yellow-100 mt-1">Échangez vos points contre des récompenses !</p>
                </div>
                <div class="text-4xl">⭐</div>
            </div>
        </div>

        <!-- Vos points -->
        <div class="bg-white rounded-xl shadow p-4 border-l-4 border-yellow-500">
            <div class="flex justify-between items-center">
                <div>
                    <p class="text-gray-500 text-sm">Vos points</p>
                    <p class="text-2xl font-bold text-yellow-600">{{ auth()->user()->score_total }} pts</p>
                    <p class="text-xs text-gray-400">Niveau {{ auth()->user()->niveau }}</p>
                </div>
                <a href="{{ route('recompenses.historique') }}" class="text-blue-500 hover:text-blue-700 text-sm">
                    Voir mon historique →
                </a>
            </div>
        </div>

        <!-- Liste des récompenses -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @forelse($recompenses as $recompense)
                <div
                    class="bg-white rounded-xl shadow hover:shadow-lg transition p-4 border-2
                @if (auth()->user()->score_total >= $recompense->points_requis) border-green-300
                @else border-gray-200 @endif">

                    <div class="flex items-start justify-between">
                        <div>
                            <span class="text-2xl">
                                @if ($recompense->type_recompense == 'bon_achat')
                                    🛒
                                @elseif($recompense->type_recompense == 'article_physique')
                                    📦
                                @elseif($recompense->type_recompense == 'reduction')
                                    💰
                                @else
                                    🎁
                                @endif
                            </span>
                        </div>
                        @if ($recompense->quantite_disponible <= 5)
                            <span class="text-xs bg-red-100 text-red-800 px-2 py-1 rounded-full">
                                ⚠️ Plus que {{ $recompense->quantite_disponible }}
                            </span>
                        @endif
                    </div>

                    <h3 class="text-lg font-bold text-gray-800 mt-2">{{ $recompense->nom_recompense }}</h3>
                    <p class="text-sm text-gray-500 mt-1">
                        {{ Str::limit($recompense->description ?? 'Aucune description', 80) }}</p>

                    <div class="mt-3 flex justify-between items-center">
                        <span class="px-2 py-1 rounded text-sm bg-yellow-100 text-yellow-800">
                            ⭐ {{ $recompense->points_requis }} pts
                        </span>
                        <span class="text-xs text-gray-400">
                            Stock : {{ $recompense->quantite_disponible }}
                        </span>
                    </div>

                    @if ($recompense->date_expiration)
                        <p class="text-xs text-red-400 mt-1">
                            ⏳ Expire le {{ \Carbon\Carbon::parse($recompense->date_expiration)->format('d/m/Y') }}
                        </p>
                    @endif

                    <div class="mt-3">
                        @if (auth()->user()->score_total >= $recompense->points_requis && $recompense->quantite_disponible > 0)
                            <form method="POST" action="{{ route('recompenses.echanger', $recompense->id) }}"
                                onsubmit="return confirm('Confirmer l\'échange de {{ $recompense->points_requis }} points contre "{{ $recompense->nom_recompense }}"
                                ?')">
                                @csrf
                                <button type="submit"
                                    class="w-full bg-green-500 hover:bg-green-600 text-white py-2 rounded-lg text-sm transition">
                                    🔄 Échanger
                                </button>
                            </form>
                        @elseif($recompense->quantite_disponible <= 0)
                            <button disabled
                                class="w-full bg-gray-300 text-gray-500 py-2 rounded-lg text-sm cursor-not-allowed">
                                ❌ Rupture de stock
                            </button>
                        @else
                            <button disabled
                                class="w-full bg-gray-300 text-gray-500 py-2 rounded-lg text-sm cursor-not-allowed">
                                ⚠️ {{ $recompense->points_requis - auth()->user()->score_total }} pts manquants
                            </button>
                        @endif
                    </div>
                </div>
            @empty
                <div class="col-span-3 text-center py-12 text-gray-500">
                    <i class="fas fa-gift text-4xl mb-2 block"></i>
                    <p>Aucune récompense disponible pour le moment.</p>
                </div>
            @endforelse
        </div>

        <div class="mt-6">
            {{ $recompenses->links() }}
        </div>
    </div>
@endsection
