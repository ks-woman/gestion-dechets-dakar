@extends('layouts.admin')

@section('title', 'Statistiques avancées')

@section('content')
    <div class="space-y-6">
        <!-- Cartes récapitulatives -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl shadow p-4 text-white">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-sm opacity-90">Total collectes</p>
                        <p class="text-2xl font-bold">{{ $totalCollectes ?? 0 }}</p>
                    </div>
                    <i class="fas fa-truck text-2xl opacity-75"></i>
                </div>
            </div>
            <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-xl shadow p-4 text-white">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-sm opacity-90">Total points</p>
                        <p class="text-2xl font-bold">{{ $totalPointsGeneraux ?? 0 }}</p>
                    </div>
                    <i class="fas fa-star text-2xl opacity-75"></i>
                </div>
            </div>
            <div class="bg-gradient-to-r from-yellow-500 to-yellow-600 rounded-xl shadow p-4 text-white">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-sm opacity-90">Total utilisateurs</p>
                        <p class="text-2xl font-bold">{{ $totalUsers ?? 0 }}</p>
                    </div>
                    <i class="fas fa-users text-2xl opacity-75"></i>
                </div>
            </div>
            <div class="bg-gradient-to-r from-purple-500 to-purple-600 rounded-xl shadow p-4 text-white">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-sm opacity-90">Total déchets (kg)</p>
                        <p class="text-2xl font-bold">
                            {{ number_format(($totalRecyclable ?? 0) + ($totalOrganique ?? 0) + ($totalResiduel ?? 0), 0) }}
                        </p>
                    </div>
                    <i class="fas fa-recycle text-2xl opacity-75"></i>
                </div>
            </div>
        </div>

        <!-- Graphiques -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="bg-white rounded-xl shadow-md p-6">
                <h3 class="font-semibold text-gray-800 mb-4 flex items-center gap-2">
                    <i class="fas fa-chart-line text-blue-500"></i> Évolution des collectes
                </h3>
                <canvas id="collectesChart" height="250"></canvas>
            </div>

            <div class="bg-white rounded-xl shadow-md p-6">
                <h3 class="font-semibold text-gray-800 mb-4 flex items-center gap-2">
                    <i class="fas fa-chart-pie text-green-500"></i> Répartition des déchets
                </h3>
                <canvas id="dechetsChart" height="250"></canvas>
            </div>

            <div class="bg-white rounded-xl shadow-md p-6">
                <h3 class="font-semibold text-gray-800 mb-4 flex items-center gap-2">
                    <i class="fas fa-chart-bar text-yellow-500"></i> Points par mois
                </h3>
                <canvas id="pointsChart" height="250"></canvas>
            </div>

            <div class="bg-white rounded-xl shadow-md p-6">
                <h3 class="font-semibold text-gray-800 mb-4 flex items-center gap-2">
                    <i class="fas fa-chart-pie text-purple-500"></i> Répartition des utilisateurs
                </h3>
                <canvas id="usersChart" height="250"></canvas>
            </div>
        </div>

        <!-- Top utilisateurs -->
        <div class="bg-white rounded-xl shadow-md">
            <div class="p-5 border-b">
                <h3 class="font-semibold text-gray-800 flex items-center gap-2">
                    <i class="fas fa-trophy text-yellow-500"></i> Top 5 des utilisateurs (points)
                </h3>
            </div>
            <div class="p-5">
                <div class="space-y-3">
                    @foreach ($topUsers as $index => $user)
                        <div
                            class="flex justify-between items-center p-3 rounded-lg {{ $index == 0 ? 'bg-yellow-50' : ($index == 1 ? 'bg-gray-50' : ($index == 2 ? 'bg-orange-50' : 'bg-gray-50')) }}">
                            <div class="flex items-center gap-3">
                                <div
                                    class="w-8 h-8 rounded-full flex items-center justify-center {{ $index == 0 ? 'bg-yellow-500' : ($index == 1 ? 'bg-gray-400' : ($index == 2 ? 'bg-orange-500' : 'bg-blue-500')) }} text-white font-bold">
                                    {{ $index + 1 }}
                                </div>
                                <div>
                                    <p class="font-medium">{{ $user->prenom }} {{ $user->nom }}</p>
                                    <p class="text-xs text-gray-500">{{ $user->role }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="font-bold text-yellow-600">{{ $user->score_total }} pts</p>
                                <p class="text-xs text-gray-500">{{ $user->collectes_count ?? 0 }} collectes</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Graphique des collectes
        const collectesCtx = document.getElementById('collectesChart').getContext('2d');
        new Chart(collectesCtx, {
            type: 'line',
            data: {
                labels: {!! json_encode($moisKeys ?? []) !!},
                datasets: [{
                    label: 'Collectes',
                    data: {!! json_encode($collectesParMoisValues ?? []) !!},
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

        // Graphique des déchets
        const dechetsCtx = document.getElementById('dechetsChart').getContext('2d');
        new Chart(dechetsCtx, {
            type: 'doughnut',
            data: {
                labels: ['Recyclable', 'Organique', 'Résiduel'],
                datasets: [{
                    data: [{{ $totalRecyclable ?? 0 }}, {{ $totalOrganique ?? 0 }},
                        {{ $totalResiduel ?? 0 }}
                    ],
                    backgroundColor: ['#10b981', '#f59e0b', '#6b7280'],
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

        // Graphique des points
        const pointsCtx = document.getElementById('pointsChart').getContext('2d');
        new Chart(pointsCtx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($moisKeys ?? []) !!},
                datasets: [{
                    label: 'Points',
                    data: {!! json_encode($pointsParMoisValues ?? []) !!},
                    backgroundColor: '#fbbf24',
                    borderRadius: 8
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
                labels: ['Ménages', 'Entreprises', 'Collecteurs', 'Admins', 'Partenaires'],
                datasets: [{
                    data: [{{ $totalMenages ?? 0 }}, {{ $totalEntreprises ?? 0 }},
                        {{ $totalCollecteurs ?? 0 }}, {{ $totalAdmins ?? 0 }},
                        {{ $totalPartenaires ?? 0 }}
                    ],
                    backgroundColor: ['#10b981', '#3b82f6', '#f59e0b', '#ef4444', '#8b5cf6'],
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
