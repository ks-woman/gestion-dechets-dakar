@extends('layouts.auth')

@section('title', 'Connexion')

@section('content')
    <div class="bg-white rounded-2xl shadow-2xl p-8">
        <!-- Logo / En-tête -->
        <div class="text-center mb-6">
            <div class="text-5xl mb-2"></div>
            <h1 class="text-2xl font-bold text-gray-800">Gestion Déchets Dakar</h1>
            <p class="text-gray-500 text-sm mt-1">Connectez-vous à votre espace</p>
        </div>

        <!-- Message d'erreur -->
        @if ($errors->any())
            <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-3 rounded-lg mb-4 text-sm">
                <i class="fas fa-exclamation-circle mr-2"></i> {{ $errors->first() }}
            </div>
        @endif

        <!-- Formulaire -->
        <form method="POST" action="{{ route('login') }}" class="space-y-5">
            @csrf

            <div>
                <label class="block text-gray-700 text-sm font-medium mb-1">Email</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                        <i class="fas fa-envelope"></i>
                    </span>
                    <input type="email" name="email" value="{{ old('email') }}"
                        class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent"
                        placeholder="exemple@email.com" required autofocus>
                </div>
            </div>

            <div>
                <label class="block text-gray-700 text-sm font-medium mb-1">Mot de passe</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                        <i class="fas fa-lock"></i>
                    </span>
                    <input type="password" name="mot_passe"
                        class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent"
                        placeholder="••••••••" required>
                </div>
            </div>

            <div class="flex items-center justify-between text-sm">
                <label class="flex items-center gap-2 text-gray-600">
                    <input type="checkbox" name="remember"
                        class="rounded border-gray-300 text-emerald-600 focus:ring-emerald-500">
                    Se souvenir de moi
                </label>
                <a href="#" class="text-emerald-600 hover:text-emerald-700 font-medium">Mot de passe oublié ?</a>
            </div>

            <button type="submit" class="btn-primary w-full py-3 rounded-lg text-base font-semibold">
                <i class="fas fa-sign-in-alt mr-2"></i> Se connecter
            </button>
        </form>

        <!-- Lien vers l'inscription -->
        <p class="text-center text-gray-500 text-sm mt-6">
            Pas encore de compte ?
            <a href="{{ route('register') }}" class="text-emerald-600 hover:text-emerald-700 font-medium">
                Créer un compte
            </a>
        </p>
    </div>
@endsection
