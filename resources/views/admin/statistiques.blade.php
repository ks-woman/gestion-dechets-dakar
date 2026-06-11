@extends('layouts.admin')

@section('title', 'Statistiques')

@section('content')
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Graphique collectes -->
        <div class="bg-white rounded-xl shadow-md p-6">
            <h3 class="font-semibold text-gray-800 mb-4 flex items-center gap-2">
                <i class="fas fa-chart-line text-blue-500"></i> Évolution des collectes
            </h3>
            <canvas id="collectesChart" height="250"></canvas>
        </div>

        <!-- Graphique répartition déchets -->
        <div class="bg-white rounded-xl shadow-md p-6">
            <h3 class="font-semibold text-gray-800 mb-4 flex items-center gap-2">
                <i class="fas fa-chart-pie text-emerald-500"></i> Déchets par type
            </h3>
            <canvas id="dechetsChart" height="250"></canvas>
        </div>

        <!-- Graphique points par mois -->
        <div class="bg-white rounded-xl shadow-md p-6">
            <h3 class="font-semibold text-gray-800 mb-4 flex items-center gap-2">
                <i class="fas fa-chart-bar text-yellow-500"></i> Points distribués par mois
            </h3>
            <canvas id="pointsChart" height="250"></canvas>
        </div>

        <!-- Top utilisateurs -->
        <div class="bg-white rounded-xl shadow-md p-6">
            <h3 class="font-semibold text-gray-800 mb-4 flex items-center gap-2">
                <i class="fas fa-trophy text-yellow-500"></i> Top utilisateurs (points)
            </h3>
            <div class="space-y-3">
                @foreach ($topUsers ?? [] as $user)
                    <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                        <div>
                            <p class="font-medium">{{ $user->prenom }} {{ $user->nom }}</p>
                            <p class="text-xs text-gray-500">{{ $user->role }}</p>
                        </div>
                        <div class="text-right">
                            <p class="font-bold text-yellow-600">{{ $user->score_total }} pts</p>
                        </div>
                    </div>
                @endforeach
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
    </script>
@endsection
