@extends('layouts.admin')

@section('title', 'Gestion des stocks')

@section('content')
    <div class="space-y-6">
        <div class="bg-white rounded-xl shadow-soft p-6">
            <h1 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
                <i class="fas fa-warehouse text-emerald-500"></i> Gestion des stocks
            </h1>
            <p class="text-gray-500 mt-1">Consultez les quantités disponibles par catégorie de déchet.</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            @foreach ($stocks as $stock)
                @php
                    $couleur = $stock->categorie->couleur ?? 'gray';
                @endphp
                <div
                    class="bg-white rounded-xl shadow-soft border border-{{ $couleur }}-200 overflow-hidden hover:shadow-lg transition">
                    <!-- En-tête coloré -->
                    <div
                        class="px-4 py-2 bg-{{ $couleur }}-50 border-b border-{{ $couleur }}-200 flex items-center gap-2">
                        <span class="text-3xl">{{ $stock->categorie->icone ?? '📦' }}</span>
                        <h3 class="text-lg font-bold text-gray-800">{{ $stock->categorie->nom }}</h3>
                    </div>

                    <!-- Contenu -->
                    <div class="p-4">
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-sm text-gray-500">Quantité</span>
                            <span class="text-2xl font-bold text-emerald-600">{{ number_format($stock->quantite, 1) }}
                                kg</span>
                        </div>
                        <div class="flex justify-between items-center mb-4">
                            <span class="text-sm text-gray-500">Prix unitaire</span>
                            <span
                                class="text-lg font-semibold text-gray-700">{{ number_format($stock->prix_unitaire ?? 0, 0) }}
                                FCFA/kg</span>
                        </div>

                        <a href="{{ route('admin.stocks.edit', $stock->id) }}"
                            class="btn-primary text-sm w-full text-center block">
                            <i class="fas fa-edit mr-1"></i> Modifier
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
