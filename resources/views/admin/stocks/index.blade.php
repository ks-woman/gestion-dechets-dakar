@extends('layouts.admin')

@section('title', 'Gestion des stocks')

@section('content')
    <div class="space-y-6">
        <div class="bg-white rounded-xl shadow-soft p-6">
            <h1 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
                <i class="fas fa-warehouse text-emerald-500"></i> Gestion des stocks
            </h1>
            <p class="text-gray-500 mt-1">Gérez les quantités et les prix unitaires des déchets.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            @foreach ($stocks as $stock)
                <div class="bg-white rounded-xl shadow-soft border p-6">
                    <div class="flex items-center gap-3 mb-2">
                        <span class="text-3xl">
                            @if ($stock->type == 'recyclable')
                            @elseif($stock->type == 'organique')
                            @else
                            @endif
                        </span>
                        <h3 class="text-xl font-bold text-gray-800 capitalize">{{ $stock->type }}</h3>
                    </div>
                    <p class="text-3xl font-bold text-emerald-600">{{ number_format($stock->quantite, 1) }} kg</p>
                    <p class="text-sm text-gray-500">Prix : {{ number_format($stock->prix_unitaire ?? 0, 0) }} FCFA/kg</p>
                    <div class="mt-4">
                        <a href="{{ route('admin.stocks.edit', $stock->id) }}"
                            class="btn-primary text-sm w-full text-center block">
                            <i class="fas fa-edit mr-2"></i> Modifier
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
