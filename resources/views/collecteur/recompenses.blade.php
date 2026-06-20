@extends('layouts.collecteur')

@section('title', 'Récompenses disponibles')

@section('content')
    <div class="bg-white rounded-xl shadow p-6">
        <h1 class="text-2xl font-bold mb-2">🎁 Récompenses disponibles</h1>
        <p class="text-gray-500 mb-6">Découvrez les récompenses que les utilisateurs peuvent obtenir.</p>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @forelse($recompenses as $recompense)
                <div class="border rounded-lg p-4 hover:shadow-md transition">
                    <div class="flex items-center gap-2 mb-2">
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
                        <h3 class="text-lg font-bold text-gray-800">{{ $recompense->nom_recompense }}</h3>
                    </div>
                    <p class="text-sm text-gray-500">{{ Str::limit($recompense->description ?? 'Aucune description', 80) }}
                    </p>
                    <div class="mt-3 flex justify-between items-center">
                        <span class="px-2 py-1 rounded text-sm bg-yellow-100 text-yellow-800">
                            ⭐ {{ $recompense->points_requis }} pts
                        </span>
                        <span class="text-xs text-gray-400">
                            Stock : {{ $recompense->quantite_disponible }}
                        </span>
                    </div>
                </div>
            @empty
                <div class="col-span-3 text-center py-12 text-gray-500">
                    <i class="fas fa-gift text-4xl mb-2 block"></i>
                    <p>Aucune récompense disponible.</p>
                </div>
            @endforelse
        </div>

        <div class="mt-6">
            {{ $recompenses->links() }}
        </div>
    </div>
@endsection
