@extends('layouts.admin')

@section('title', 'Tableau de bord')

@section('content')
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
        <!-- Carte Utilisateurs -->
        <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-blue-500 hover:shadow-lg transition">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-gray-500 text-sm">Utilisateurs</p>
                    <p class="text-3xl font-bold text-gray-800">{{ $stats['total_users'] ?? 0 }}</p>
                    <p class="text-xs text-gray-400 mt-1">
                        {{ $stats['total_menages'] ?? 0 }} ménages | {{ $stats['total_entreprises'] ?? 0 }}
                        entreprises
                    </p>
                </div>
                <div class="text-3xl text-blue-500"></div>
            </div>
        </div>

        <!-- Carte Collecteurs -->
        <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-emerald-500 hover:shadow-lg transition">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-gray-500 text-sm">Collecteurs</p>
                    <p class="text-3xl font-bold text-gray-800">{{ $stats['total_collecteurs'] ?? 0 }}</p>
                    <p class="text-xs text-gray-400 mt-1"> Agents de collecte</p>
                </div>
                <div class="text-3xl text-emerald-500"></div>
            </div>
        </div>

        <!-- Carte Collectes -->
        <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-yellow-500 hover:shadow-lg transition">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-gray-500 text-sm">Collectes</p>
                    <p class="text-3xl font-bold text-gray-800">{{ $stats['total_collectes'] ?? 0 }}</p>
                    <p class="text-xs text-gray-400 mt-1"> Collectes réalisées</p>
                </div>
                <div class="text-3xl text-yellow-500"></div>
            </div>
        </div>

        <!-- Carte Points -->
        <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-purple-500 hover:shadow-lg transition">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-gray-500 text-sm">Points distribués</p>
                    <p class="text-3xl font-bold text-gray-800">{{ $stats['total_points_distribues'] ?? 0 }}</p>
                    <p class="text-xs text-gray-400 mt-1"> Points cumulés</p>
                </div>
                <div class="text-3xl text-purple-500"></div>
            </div>
        </div>
    </div>

    <!-- Graphique rapide -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <div class="bg-white rounded-xl shadow-md p-6">
            <h3 class="font-semibold text-gray-800 mb-4 flex items-center gap-2">
                <i class="fas fa-chart-line text-blue-500"></i> Évolution des collectes
            </h3>
            <canvas id="collectesChart" height="200"></canvas>
        </div>

        <div class="bg-white rounded-xl shadow-md p-6">
            <h3 class="font-semibold text-gray-800 mb-4 flex items-center gap-2">
                <i class="fas fa-chart-pie text-emerald-500"></i> Répartition des utilisateurs
            </h3>
            <canvas id="usersChart" height="200"></canvas>
        </div>
    </div>

    <!-- Dernières activités -->
    <div class="bg-white rounded-xl shadow-md">
        <div class="p-5 border-b">
            <h3 class="font-semibold text-gray-800 flex items-center gap-2">
                <i class="fas fa-history text-gray-500"></i> Dernières activités
            </h3>
        </div>
        <div class="p-5">
            <div class="space-y-3">
                <div class="flex items-center gap-3 text-sm">
                    <div class="w-2 h-2 bg-green-500 rounded-full"></div>
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
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Graphique des collectes (simulé, à remplacer par vraies données)
        const collectesCtx = document.getElementById('collectesChart').getContext('2d');
        new Chart(collectesCtx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin'],
                datasets: [{
                    label: 'Collectes',
                    data: [12, 19, 15, 27, 22, {{ $stats['total_collectes'] ?? 0 }}],
                    borderColor: '#3b82f6',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
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

        // Graphique des utilisateurs
        const usersCtx = document.getElementById('usersChart').getContext('2d');
        new Chart(usersCtx, {
            type: 'doughnut',
            data: {
                labels: ['Ménages', 'Entreprises', 'Collecteurs'],
                datasets: [{
                    data: [{{ $stats['total_menages'] ?? 0 }}, {{ $stats['total_entreprises'] ?? 0 }},
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
    </script>
@endsection
