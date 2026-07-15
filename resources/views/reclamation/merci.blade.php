@extends('layouts.menage')

@section('title', 'Message envoyé')

@section('content')
    <div class="bg-white rounded-xl shadow-soft p-8 max-w-2xl mx-auto text-center">
        <div class="text-6xl mb-4"></div>
        <h1 class="text-2xl font-bold text-gray-800 mb-2">Message envoyé !</h1>
        <p class="text-gray-600 mb-6">Votre message a été transmis à l'administration. Nous vous répondrons dans les plus
            brefs délais.</p>
        <div class="flex justify-center gap-4">
            <a href="{{ route('menage.dashboard') }}" class="btn-primary">
                <i class="fas fa-home mr-2"></i> Retour à l'accueil
            </a>
            <a href="{{ route('reclamation.create') }}" class="btn-secondary">
                <i class="fas fa-plus mr-2"></i> Envoyer un autre message
            </a>
        </div>
    </div>
@endsection
