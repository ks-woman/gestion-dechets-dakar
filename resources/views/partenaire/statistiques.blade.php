@extends('layouts.partenaire')

@section('title', 'Statistiques')

@section('content')
    <div class="space-y-6">
        <div class="bg-white rounded-xl shadow-soft p-6">
            <h1 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
                <i class="fas fa-chart-bar text-emerald-500"></i> Statistiques
            </h1>
            <p class="text-gray-500 mt-1">Suivez vos achats et l’évolution des stocks.</p>
        </div>

        <!-- Cartes récapitulatives -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="card">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-gray-500 text-sm">Commandes totales</p>
                        <p class="text-2xl font-bold text-emerald-600">{{ $totalCommandes ?? 0 }}</p>
                    </div>
                    <i class="fas fa-shopping-cart text-3xl text-emerald-500"></i>
                </div>
            </div>
            <div class="card">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-gray-500 text-sm">Quantité totale achetée</p>
                        <p class="text-2xl font-bold text-emerald-600">{{ number_format($quantiteTotale ?? 0, 1) }} kg</p>
                    </div>
                    <i class="fas fa-weight-hanging text-3xl text-emerald-500"></i>
                </div>
            </div>
            <div class="card">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-gray-500 text-sm">Montant total dépensé</p>
                        <p class="text-2xl font-bold text-emerald-600">{{ number_format($montantTotal ?? 0, 0, ',', ' ') }}
                            FCFA</p>
                    </div>
                    <i class="fas fa-coins text-3xl text-emerald-500"></i>
                </div>
            </div>
        </div>

        <!-- Graphique : volumes par catégorie -->
        <div class="bg-white rounded-xl shadow-soft p-6">
            <h3 class="font-semibold text-gray-800 mb-4"> Volumes achetés par catégorie</h3>
            <canvas id="categorieChart" height="200"></canvas>
        </div>

        <!-- Graphique : évolution des commandes -->
        <div class="bg-white rounded-xl shadow-soft p-6">
            <h3 class="font-semibold text-gray-800 mb-4"> Évolution des commandes</h3>
            <canvas id="commandesChart" height="200"></canvas>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Graphique par catégorie
            const catCtx = document.getElementById('categorieChart').getContext('2d');
            const categories = @json($categories ?? []);
            const volumes = @json($volumesParCategorie ?? []);

            new Chart(catCtx, {
                type: 'bar',
                data: {
                    labels: categories,
                    datasets: [{
                        label: 'Quantité (kg)',
                        data: volumes,
                        backgroundColor: ['#10b981', '#3b82f6', '#f59e0b', '#ef4444', '#8b5cf6',
                            '#ec4899'
                        ],
                        borderRadius: 6
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });

            // Graphique des commandes par mois
            const cmdCtx = document.getElementById('commandesChart').getContext('2d');
            const mois = @json($moisCommandes ?? []);
            const nbCommandes = @json($nbCommandesParMois ?? []);

            new Chart(cmdCtx, {
                type: 'line',
                data: {
                    labels: mois,
                    datasets: [{
                        label: 'Commandes',
                        data: nbCommandes,
                        borderColor: '#10b981',
                        backgroundColor: 'rgba(16,185,129,0.1)',
                        tension: 0.4,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            display: false
                        }
                    }
                }
            });
        });
    </script>
@endsection
