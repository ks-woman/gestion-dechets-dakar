@extends('layouts.app')

@section('title', 'À propos - Gestion Déchets Dakar')

@section('content')
    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-2xl shadow-xl p-6 md:p-8">
            <!-- En-tête -->
            <div class="text-center mb-8">
                <div class="text-5xl mb-3"></div>
                <h1 class="text-3xl md:text-4xl font-bold text-gray-800">À propos du projet</h1>
                <p class="text-gray-600 mt-2">Gestion et valorisation des déchets à Dakar</p>
            </div>

            <!-- Description -->
            <div class="space-y-6 text-gray-700 leading-relaxed">
                <div class="bg-emerald-50 p-6 rounded-xl border-l-4 border-emerald-500">
                    <h2 class="text-xl font-bold text-emerald-800 mb-2">Notre mission</h2>
                    <p>
                        Le projet <strong>Gestion Déchets Dakar</strong> vise à apporter une solution innovante et durable
                        à la problématique de gestion des déchets dans les quartiers enclavés de la capitale sénégalaise.
                    </p>
                </div>

                <div>
                    <h2 class="text-xl font-bold text-gray-800 mb-2">Contexte et enjeux</h2>
                    <p>
                        Les quartiers enclavés de Dakar rencontrent d'importantes difficultés liées à la gestion des déchets
                        :
                        accès limité aux services de collecte, irrégularité des passages, accumulation prolongée, absence de
                        tri à la source
                        et faible valorisation des matières recyclables et organiques. Ces problématiques impactent à la
                        fois les ménages,
                        dont le cadre de vie et la santé sont directement affectés, et les petites entreprises locales
                        (restaurants,
                        commerces, hôtels, etc.) qui produisent des volumes importants de déchets.
                    </p>
                </div>

                <div>
                    <h2 class="text-xl font-bold text-gray-800 mb-2">Notre solution</h2>
                    <p>
                        Nous avons développé une <strong>application mobile intégrée</strong> permettant aux utilisateurs de
                        :
                    </p>
                    <ul class="list-disc list-inside space-y-1 mt-2 text-gray-700">
                        <li>S'inscrire sur une plateforme de gestion des déchets</li>
                        <li>Trier leurs déchets grâce à un <strong>kit de tri fourni</strong></li>
                        <li>Suivre leurs pratiques et leurs performances</li>
                        <li>Profiter de services de collecte, d'incitations et de valorisation</li>
                        <li>Contribuer à une économie circulaire locale</li>
                    </ul>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
                    <div class="bg-gray-50 p-4 rounded-xl text-center border border-gray-200">
                        <div class="text-3xl mb-2"></div>
                        <h3 class="font-bold text-gray-800">Ménages</h3>
                        <p class="text-sm text-gray-600">Collecte à domicile, tri simplifié, points de fidélité</p>
                    </div>
                    <div class="bg-gray-50 p-4 rounded-xl text-center border border-gray-200">
                        <div class="text-3xl mb-2"></div>
                        <h3 class="font-bold text-gray-800">Entreprises</h3>
                        <p class="text-sm text-gray-600">Collecte professionnelle, volumes adaptés</p>
                    </div>
                    <div class="bg-gray-50 p-4 rounded-xl text-center border border-gray-200">
                        <div class="text-3xl mb-2"></div>
                        <h3 class="font-bold text-gray-800">Économie circulaire</h3>
                        <p class="text-sm text-gray-600">Valorisation des déchets, récompenses à la clé</p>
                    </div>
                </div>

                <div>
                    <h2 class="text-xl font-bold text-gray-800 mb-2">Objectifs du projet</h2>
                    <ul class="list-disc list-inside space-y-1 text-gray-700">
                        <li>Faciliter l'inscription des ménages et des entreprises</li>
                        <li>Encourager le tri à la source grâce à un kit de collecte offert</li>
                        <li>Assurer un suivi personnalisé des volumes triés et collectés</li>
                        <li>Améliorer la communication entre ménages, entreprises et collecteurs</li>
                        <li>Favoriser la valorisation des déchets recyclables ou organiques</li>
                        <li>Proposer des incitations (points, récompenses, réductions, sensibilisation)</li>
                    </ul>
                </div>

                <div class="bg-yellow-50 p-6 rounded-xl border-l-4 border-yellow-500">
                    <h2 class="text-xl font-bold text-yellow-800 mb-2">Période d'essai gratuite</h2>
                    <p>
                        Profitez de <strong>15 jours d'essai gratuit</strong> avec kit de tri offert, collecte à domicile,
                        puis <strong>5000 FCFA/mois</strong> pour un service complet.
                    </p>
                    <a href="{{ route('register') }}" class="inline-block mt-3 btn-primary text-sm">
                        <i class="fas fa-rocket mr-2"></i> Commencer l'essai
                    </a>
                </div>
            </div>

            <!-- Bouton retour -->
            <div class="mt-8 text-center">
                <a href="{{ route('home') }}"
                    class="inline-flex items-center text-emerald-600 hover:text-emerald-700 transition">
                    <i class="fas fa-arrow-left mr-2"></i> Retour à l'accueil
                </a>
            </div>
        </div>
    </div>
@endsection
