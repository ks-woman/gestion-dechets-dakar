@extends('layouts.collecteur')

@section('title', 'Activer un kit')

@section('content')
    <div class="bg-white rounded-xl shadow-soft p-6 max-w-md mx-auto">
        <h1 class="text-2xl font-bold text-gray-800 mb-4"> Activer un kit</h1>

        <form action="{{ route('collecteur.kit.activer') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label class="block text-gray-700 mb-2">Code du kit</label>
                <input type="text" name="code_kit" class="w-full border rounded-lg p-2 focus:ring-2 focus:ring-emerald-500"
                    placeholder="Ex: KIT-ABC123" required>
            </div>
            <button type="submit" class="btn-primary w-full">Activer</button>
            <a href="{{ route('collecteur.dashboard') }}" class="btn-gray w-full text-center block mt-2">Retour</a>
        </form>
    </div>
@endsection
