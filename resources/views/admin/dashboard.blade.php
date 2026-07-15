@extends('layouts.admin')

@section('title', 'Tableau de bord')

@section('content')
    <div class="space-y-6">
        <!-- Bannière -->
        <div class="bg-gradient-to-r from-emerald-600 to-emerald-800 rounded-xl shadow-soft p-6 text-white">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-bold">Bonjour {{ auth()->user()->prenom }} !</h1>
                    <p class="text-emerald-100 mt-1">Tableau de bord administrateur</p>
                </div>
                <div class="text-5xl"></div>
            </div>
        </div>

        <!-- Cartes statistiques -->
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4">
            <div class="card">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-gray-500 text-sm">Utilisateurs</p>
                        <p class="text-2xl font-bold text-emerald-600">{{ $stats['total_users'] ?? 0 }}</p>
                        <p class="text-xs text-gray-400">{{ $stats['total_menages'] ?? 0 }} ménages |
                            {{ $stats['total_entreprises'] ?? 0 }} entreprises</p>
                    </div>
                    <i class="fas fa-users text-3xl text-emerald-500"></i>
                </div>
            </div>

            <div class="card">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-gray-500 text-sm">Collecteurs</p>
                        <p class="text-2xl font-bold text-emerald-600">{{ $stats['total_collecteurs'] ?? 0 }}</p>
                        <p class="text-xs text-gray-400">Agents de collecte</p>
                    </div>
                    <i class="fas fa-truck text-3xl text-emerald-500"></i>
                </div>
            </div>

            <div class="card">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-gray-500 text-sm">Collectes</p>
                        <p class="text-2xl font-bold text-emerald-600">{{ $stats['total_collectes'] ?? 0 }}</p>
                        <p class="text-xs text-gray-400">Collectes réalisées</p>
                    </div>
                    <i class="fas fa-recycle text-3xl text-emerald-500"></i>
                </div>
            </div>

            <div class="card">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-gray-500 text-sm">Points distribués</p>
                        <p class="text-2xl font-bold text-emerald-600">{{ $stats['total_points_distribues'] ?? 0 }}</p>
                        <p class="text-xs text-gray-400">Points cumulés</p>
                    </div>
                    <i class="fas fa-star text-3xl text-emerald-500"></i>
                </div>
            </div>

            <!--  Carte Abonnements -->
            <div class="card">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-gray-500 text-sm">Abonnés actifs</p>
                        <p class="text-2xl font-bold text-emerald-600">
                            {{ \App\Models\Abonnement::where('statut', 'actif')->count() }}</p>
                        <p class="text-xs text-gray-400">{{ \App\Models\Abonnement::where('statut', 'essai')->count() }} en
                            essai</p>
                    </div>
                    <i class="fas fa-credit-card text-3xl text-emerald-500"></i>
                </div>
            </div>
        </div>

        <!-- Graphiques -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="bg-white rounded-xl shadow-soft p-6">
                <h3 class="font-semibold text-gray-800 mb-4 flex items-center gap-2">
                    <i class="fas fa-chart-line text-emerald-500"></i> Évolution des collectes
                </h3>
                <canvas id="collectesChart" height="200"></canvas>
            </div>
            <div class="bg-white rounded-xl shadow-soft p-6">
                <h3 class="font-semibold text-gray-800 mb-4 flex items-center gap-2">
                    <i class="fas fa-chart-pie text-emerald-500"></i> Répartition des utilisateurs
                </h3>
                <canvas id="usersChart" height="200"></canvas>
            </div>
        </div>

        <!-- Dernières activités -->
        <div class="bg-white rounded-xl shadow-soft p-6">
            <h3 class="font-semibold text-gray-800 mb-4 flex items-center gap-2">
                <i class="fas fa-history text-emerald-500"></i> Dernières activités
            </h3>
            <div class="space-y-3">
                <div class="flex items-center gap-3 text-sm">
                    <div class="w-2 h-2 bg-emerald-500 rounded-full"></div>
                    <span class="text-gray-600">{{ $stats['total_users'] ?? 0 }} utilisateurs inscrits</span>
                </div>
                <div class="flex items-center gap-3 text-sm">
                    <div class="w-2 h-2 bg-blue-500 rounded-full"></div>
                    <span class="text-gray-600">{{ $stats['total_collectes'] ?? 0 }} collectes réalisées</span>
                </div>
                <div class="flex items-center gap-3 text-sm">
                    <div class="w-2 h-2 bg-yellow-500 rounded-full"></div>
                    <span class="text-gray-600">{{ $stats['total_points_distribues'] ?? 0 }} points distribués</span>
                </div>
                <div class="flex items-center gap-3 text-sm">
                    <div class="w-2 h-2 bg-purple-500 rounded-full"></div>
                    <span class="text-gray-600">{{ \App\Models\Abonnement::where('statut', 'actif')->count() }} abonnés
                        actifs</span>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const collectesCtx = document.getElementById('collectesChart').getContext('2d');
            new Chart(collectesCtx, {
                type: 'line',
                data: {
                    labels: ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin'],
                    datasets: [{
                        label: 'Collectes',
                        data: [12, 19, 15, 27, 22, {{ $stats['total_collectes'] ?? 0 }}],
                        borderColor: '#10b981',
                        backgroundColor: 'rgba(16, 185, 129, 0.1)',
                        tension: 0.3,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top'
                        }
                    }
                }
            });

            const usersCtx = document.getElementById('usersChart').getContext('2d');
            new Chart(usersCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Ménages', 'Entreprises', 'Collecteurs'],
                    datasets: [{
                        data: [
                            {{ $stats['total_menages'] ?? 0 }},
                            {{ $stats['total_entreprises'] ?? 0 }},
                            {{ $stats['total_collecteurs'] ?? 0 }}
                        ],
                        backgroundColor: ['#10b981', '#f59e0b', '#3b82f6'],
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });
        });
    </script>
@endsection
