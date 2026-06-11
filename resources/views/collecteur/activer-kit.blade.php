@extends('layouts.collecteur')

@section('title', 'Activer un kit')

@section('content')
    <div class="bg-white rounded-lg shadow p-6">
        <h1 class="text-2xl font-bold mb-6"> Activer un kit</h1>

        <form action="{{ route('collecteur.kit.activer') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label class="block text-gray-700 mb-2">Code du kit</label>
                <input type="text" name="code_kit" class="w-full border rounded-lg px-3 py-2"
                    placeholder="Entrez le code du kit">
            </div>

            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg">Activer</button>
            <a href="{{ route('collecteur.dashboard') }}" class="ml-2 text-gray-500">Retour</a>
        </form>
    </div>
@endsection
