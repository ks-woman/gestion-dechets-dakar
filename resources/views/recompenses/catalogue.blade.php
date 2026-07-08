@extends('layouts.menage')

@section('title', 'Catalogue des récompenses')

@section('content')
    <div class="space-y-6">
        <!-- En-tête -->
        <div class="bg-gradient-to-r from-emerald-600 to-emerald-800 rounded-xl shadow-soft p-6 text-white">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-bold"> Catalogue des récompenses</h1>
                    <p class="text-emerald-100 mt-1">Échangez vos points contre des récompenses !</p>
                </div>
                <div class="text-5xl"></div>
            </div>
        </div>

        <!-- Points de l'utilisateur -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="card">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-gray-500 text-sm">Vos points</p>
                        <p class="text-2xl font-bold text-emerald-600">{{ auth()->user()->score_total }} pts</p>
                        <p class="text-xs text-gray-400">Niveau {{ auth()->user()->niveau }}</p>
                    </div>
                    <i class="fas fa-star text-3xl text-emerald-500"></i>
                </div>
            </div>
            <div class="card-info">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-gray-500 text-sm">Échanges effectués</p>
                        <p class="text-2xl font-bold text-blue-600">{{ $totalEchanges ?? 0 }}</p>
                    </div>
                    <i class="fas fa-exchange-alt text-3xl text-blue-500"></i>
                </div>
            </div>
            <div class="card-secondary">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-gray-500 text-sm">Points dépensés</p>
                        <p class="text-2xl font-bold text-yellow-600">{{ $totalPointsDepenses ?? 0 }}</p>
                    </div>
                    <i class="fas fa-coins text-3xl text-yellow-500"></i>
                </div>
            </div>
        </div>

        <!-- Liste des récompenses -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @forelse($recompenses as $recompense)
                <div
                    class="bg-white rounded-xl shadow-soft border-2 p-4 hover:shadow-lg transition
            @if (auth()->user()->score_total >= $recompense->points_requis) border-emerald-300 @else border-gray-200 @endif">

                    <div class="flex items-start justify-between">
                        <span class="text-3xl">
                            @if ($recompense->type_recompense == 'bon_achat')
                            @elseif($recompense->type_recompense == 'article_physique')

                            @elseif($recompense->type_recompense == 'reduction')
                            @else
                            @endif
                        </span>
                        @if ($recompense->quantite_disponible <= 5)
                            <span class="badge-danger"> Plus que {{ $recompense->quantite_disponible }}</span>
                        @endif
                    </div>

                    <h3 class="text-lg font-bold text-gray-800 mt-2">{{ $recompense->nom_recompense }}</h3>
                    <p class="text-sm text-gray-500 mt-1">
                        {{ Str::limit($recompense->description ?? 'Aucune description', 80) }}</p>

                    <div class="mt-3 flex justify-between items-center">
                        <span class="badge-warning"> {{ $recompense->points_requis }} pts</span>
                        <span class="text-xs text-gray-400">Stock : {{ $recompense->quantite_disponible }}</span>
                    </div>

                    @if ($recompense->date_expiration)
                        <p class="text-xs text-red-400 mt-1"> Expire le
                            {{ \Carbon\Carbon::parse($recompense->date_expiration)->format('d/m/Y') }}</p>
                    @endif

                    <div class="mt-3">
                        @if (auth()->user()->score_total >= $recompense->points_requis && $recompense->quantite_disponible > 0)
                            <form method="POST" action="{{ route('recompenses.echanger', $recompense->id) }}"
                                onsubmit="return confirm('Confirmer l\'échange de {{ $recompense->points_requis }} points contre "{{ $recompense->nom_recompense }}"
                                ?')">
                                @csrf
                                <button type="submit" class="btn-primary w-full"> Échanger</button>
                            </form>
                        @elseif($recompense->quantite_disponible <= 0)
                            <button disabled class="btn-gray w-full cursor-not-allowed"> Rupture de stock</button>
                        @else
                            <button disabled class="btn-gray w-full cursor-not-allowed">
                                {{ $recompense->points_requis - auth()->user()->score_total }} pts manquants</button>
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

        <div class="mt-6">{{ $recompenses->links() }}</div>
    </div>
@endsection
