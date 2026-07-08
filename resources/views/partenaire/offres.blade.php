@extends('layouts.partenaire')

@section('title', 'Offres de déchets')

@section('content')
    <div class="space-y-6">
        <div class="bg-white rounded-xl shadow-soft p-6">
            <h1 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
                <i class="fas fa-boxes text-emerald-500"></i> Offres de déchets disponibles
            </h1>
            <p class="text-gray-500 mt-1">Consultez les déchets collectés disponibles pour valorisation.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @forelse($collectes as $collecte)
                <div class="bg-white rounded-xl shadow-soft border p-4 hover:shadow-lg transition">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="font-semibold">{{ $collecte->user->prenom }} {{ $collecte->user->nom }}</p>
                            <p class="text-sm text-gray-500">{{ $collecte->adresse }}</p>
                        </div>
                        <span class="badge-success">Disponible</span>
                    </div>

                    <div class="grid grid-cols-3 gap-2 mt-3 text-sm">
                        <div><span class="text-gray-500">Recyclable</span><br><span
                                class="font-semibold">{{ number_format($collecte->poids_recyclable, 1) }} kg</span></div>
                        <div><span class="text-gray-500">Organique</span><br><span
                                class="font-semibold">{{ number_format($collecte->poids_organique, 1) }} kg</span></div>
                        <div><span class="text-gray-500">Résiduel</span><br><span
                                class="font-semibold">{{ number_format($collecte->poids_residuel, 1) }} kg</span></div>
                    </div>

                    <div class="mt-3 text-sm">
                        <p><span class="text-gray-500">Collecté le :</span> {{ $collecte->date_collecte->format('d/m/Y') }}
                        </p>
                        <p><span class="text-gray-500">Poids total :</span> <span
                                class="font-semibold">{{ number_format($collecte->poids_recyclable + $collecte->poids_organique + $collecte->poids_residuel, 1) }}
                                kg</span></p>
                    </div>

                    <div class="mt-4">
                        <a href="{{ route('partenaire.commander.form', $collecte->id) }}"
                            class="btn-primary w-full text-center block">
                            <i class="fas fa-cart-plus mr-2"></i> Commander
                        </a>
                    </div>
                </div>
            @empty
                <div class="col-span-3 text-center py-12 text-gray-500">
                    <i class="fas fa-box-open text-4xl mb-2 block"></i>
                    <p>Aucune offre disponible pour le moment.</p>
                </div>
            @endforelse
        </div>

        <div class="mt-6">{{ $collectes->links() }}</div>
    </div>
@endsection
