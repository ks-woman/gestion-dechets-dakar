@extends('layouts.app')

@section('content')
    <div class="max-w-md mx-auto bg-white p-6 rounded-lg shadow">
        <h2 class="text-2xl font-bold mb-6">Connexion</h2>
        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="mb-4">
                <label class="block mb-2">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" class="w-full border rounded p-2" required>
                @error('email')
                    <div class="text-red-500 mt-1">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-4">
                <label class="block mb-2">Mot de passe</label>
                <input type="password" name="mot_passe" class="w-full border rounded p-2" required>
                @error('mot_passe')
                    <div class="text-red-500 mt-1">{{ $message }}</div>
                @enderror
            </div>
            <button type="submit" class="w-full bg-blue-500 text-white p-2 rounded">Se connecter</button>
        </form>
        <p class="mt-4 text-center">Pas encore inscrit ? <a href="{{ route('register') }}" class="text-blue-500">Créer un
                compte</a></p>
    </div>
@endsection
