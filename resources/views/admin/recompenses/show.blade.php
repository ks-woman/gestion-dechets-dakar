@extends('layouts.menage')

@section('title', 'Détail de la récompense')

@section('content')
    <div class="bg-white rounded-xl shadow p-6">
        <h1 class="text-2xl font-bold">{{ $recompense->nom_recompense }}</h1>
        <p class="text-gray-500">{{ $recompense->description }}</p>
        <p>Points requis : {{ $recompense->points_requis }}</p>
        <p>Stock : {{ $recompense->quantite_disponible }}</p>
        <!-- Formulaire d'échange ici -->
    </div>
@endsection
