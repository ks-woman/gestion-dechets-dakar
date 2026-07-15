@extends('layouts.menage') {{-- ou layouts.app selon votre layout --}}

@section('title', 'Contacter l\'administration')

@section('content')
    <div class="bg-white rounded-xl shadow-soft p-6 max-w-2xl mx-auto">
        <h1 class="text-2xl font-bold text-gray-800 mb-4 flex items-center gap-2">
            <i class="fas fa-envelope text-emerald-500"></i> Contacter l'administration
        </h1>
        <p class="text-gray-500 mb-6">Utilisez ce formulaire pour envoyer un message à l'équipe d'administration. Nous vous
            répondrons dans les plus brefs délais.</p>

        @if (session('success'))
            <div class="alert-success">{{ session('success') }}</div>
        @endif

        <form method="POST" action="{{ route('reclamation.store') }}">
            @csrf
            <div class="mb-4">
                <label class="block text-gray-700 font-medium mb-2">Sujet <span class="text-red-500">*</span></label>
                <input type="text" name="sujet" value="{{ old('sujet') }}"
                    class="w-full border rounded-lg p-3 focus:ring-2 focus:ring-emerald-500 @error('sujet') border-red-500 @enderror"
                    placeholder="Résumé de votre demande" required>
                @error('sujet')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 font-medium mb-2">Message <span class="text-red-500">*</span></label>
                <textarea name="description" rows="6"
                    class="w-full border rounded-lg p-3 focus:ring-2 focus:ring-emerald-500 @error('description') border-red-500 @enderror"
                    placeholder="Décrivez votre demande en détail..." required>{{ old('description') }}</textarea>
                @error('description')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex gap-3">
                <button type="submit" class="btn-primary">
                    <i class="fas fa-paper-plane mr-2"></i> Envoyer
                </button>
                <a href="{{ route('menage.dashboard') }}" class="btn-gray">
                    <i class="fas fa-times mr-2"></i> Annuler
                </a>
            </div>
        </form>
    </div>
@endsection
