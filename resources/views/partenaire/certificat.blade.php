@extends('layouts.partenaire')

@section('title', 'Certificat de valorisation')

@section('content')
    <div class="bg-white rounded-xl shadow-soft p-8 max-w-2xl mx-auto border-2 border-emerald-200">
        <!-- En-tête -->
        <div class="text-center border-b border-gray-200 pb-6 mb-6">
            <div class="text-4xl mb-2"></div>
            <h1 class="text-2xl font-bold text-emerald-700">CERTIFICAT DE VALORISATION</h1>
            <p class="text-gray-500 text-sm">N° {{ $certificat->numero_certificat }}</p>
            <p class="text-gray-500 text-sm">Émis le {{ $certificat->date_emission->format('d/m/Y') }}</p>
        </div>

        <!-- Corps -->
        <div class="space-y-4">
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p class="text-gray-500 text-sm">Partenaire</p>
                    <p class="font-semibold">{{ Auth::user()->nom }} {{ Auth::user()->prenom }}</p>
                </div>
                <div>
                    <p class="text-gray-500 text-sm">Quantité valorisée</p>
                    <p class="font-semibold">{{ number_format($certificat->quantite_valorisee, 1) }} kg</p>
                </div>
                <div>
                    <p class="text-gray-500 text-sm">Type de valorisation</p>
                    <p class="font-semibold">{{ $certificat->type_valorisation }}</p>
                </div>
                <div>
                    <p class="text-gray-500 text-sm">Collecte associée</p>
                    <p class="font-semibold">#{{ $commande->collecte_id }}</p>
                </div>
            </div>

            <div class="bg-gray-50 p-4 rounded-lg">
                <p class="text-gray-500 text-sm">Description</p>
                <p>{{ $certificat->description ?? 'Valorisation de déchets recyclables' }}</p>
            </div>

            <div class="bg-emerald-50 border border-emerald-200 rounded-lg p-4 text-sm text-emerald-700">
                <p> Ce certificat atteste que les déchets ont été valorisés conformément aux normes en vigueur.</p>
                <p class="text-xs mt-2">Document généré automatiquement par Gestion Déchets Dakar</p>
            </div>
        </div>

        <!-- Boutons -->
        <div class="mt-8 flex gap-3 justify-center border-t border-gray-200 pt-6">
            <a href="{{ route('partenaire.historique') }}" class="btn-primary">
                <i class="fas fa-arrow-left mr-2"></i> Retour à l'historique
            </a>
            <button onclick="window.print()" class="btn-secondary">
                <i class="fas fa-print mr-2"></i> Imprimer
            </button>
        </div>
    </div>
@endsection
