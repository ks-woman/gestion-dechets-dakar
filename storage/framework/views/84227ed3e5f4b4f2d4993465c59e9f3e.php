<?php $__env->startSection('title', 'Mes statistiques'); ?>

<?php $__env->startSection('content'); ?>
    <div class="space-y-6">
        <div class="bg-white rounded-xl shadow p-6">
            <h1 class="text-2xl font-bold text-gray-800"> Mes statistiques</h1>
            <p class="text-gray-500 mt-1">Suivez vos performances en tant que collecteur.</p>
        </div>

        <!-- Cartes -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="bg-white rounded-xl shadow p-4 border-l-4 border-blue-500">
                <p class="text-gray-500 text-sm">Collectes</p>
                <p class="text-2xl font-bold text-blue-600"><?php echo e($totalCollectes); ?></p>
            </div>
            <div class="bg-white rounded-xl shadow p-4 border-l-4 border-green-500">
                <p class="text-gray-500 text-sm">Poids total</p>
                <p class="text-2xl font-bold text-green-600"><?php echo e(number_format($totalPoids->total ?? 0, 1)); ?> kg</p>
            </div>
            <div class="bg-white rounded-xl shadow p-4 border-l-4 border-yellow-500">
                <p class="text-gray-500 text-sm">Points générés</p>
                <p class="text-2xl font-bold text-yellow-600"><?php echo e(number_format($totalPoints ?? 0)); ?></p>
            </div>
            <div class="bg-white rounded-xl shadow p-4 border-l-4 border-purple-500">
                <p class="text-gray-500 text-sm">Note moyenne</p>
                <p class="text-2xl font-bold text-purple-600">
                    <?php echo e(number_format(auth()->user()->collecteur->note_moyenne ?? 0, 1)); ?> </p>
            </div>
        </div>

        <!-- Graphique -->
        <div class="bg-white rounded-xl shadow p-4">
            <h3 class="font-semibold text-gray-800 mb-3 text-sm"> Évolution des collectes (<?php echo e(now()->year); ?>)</h3>
            <div class="w-full" style="height: 180px;">
                <canvas id="collectesChart"></canvas>
            </div>
        </div>
        <!-- Top clients -->
        <div class="bg-white rounded-xl shadow p-6">
            <h3 class="font-semibold text-gray-800 mb-4"> Top 5 clients collectés</h3>
            <ul class="space-y-2">
                <?php $__empty_1 = true; $__currentLoopData = $topClients; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $client): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <li class="flex justify-between items-center p-2 bg-gray-50 rounded">
                        <span><?php echo e($client->user->prenom); ?> <?php echo e($client->user->nom); ?></span>
                        <span class="font-bold"><?php echo e($client->total); ?> collectes</span>
                    </li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <li class="text-gray-500">Aucun client pour le moment.</li>
                <?php endif; ?>
            </ul>
        </div>

        <!-- Dernières collectes -->
        <div class="bg-white rounded-xl shadow p-6">
            <h3 class="font-semibold text-gray-800 mb-4"> Dernières collectes</h3>
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b">
                        <th class="text-left py-2">Client</th>
                        <th class="text-left py-2">Date</th>
                        <th class="text-left py-2">Poids</th>
                        <th class="text-left py-2">Points</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $dernieresCollectes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $collecte): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr class="border-b hover:bg-gray-50">
                            <td class="py-2"><?php echo e($collecte->user->prenom ?? ''); ?> <?php echo e($collecte->user->nom ?? ''); ?></td>
                            <td class="py-2"><?php echo e(\Carbon\Carbon::parse($collecte->date_collecte)->format('d/m/Y')); ?></td>
                            <td class="py-2">
                                <?php echo e(number_format($collecte->poids_recyclable + $collecte->poids_organique + $collecte->poids_residuel, 1)); ?>

                                kg</td>
                            <td class="py-2"><?php echo e($collecte->points_obtenus); ?></td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="4" class="py-4 text-center text-gray-500">Aucune collecte</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('collectesChart').getContext('2d');
            const moisKeys = ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin', 'Juil', 'Aoû', 'Sep', 'Oct', 'Nov', 'Déc'];
            const data = <?php echo json_encode($collectesParMois, 15, 512) ?>;
            const values = moisKeys.map((_, i) => data[i + 1] || 0);

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: moisKeys,
                    datasets: [{
                        label: 'Collectes',
                        data: values,
                        borderColor: '#10b981',
                        backgroundColor: 'rgba(16, 185, 129, 0.15)',
                        borderWidth: 3,
                        tension: 0.4,
                        fill: true,
                        pointBackgroundColor: '#10b981',
                        pointBorderColor: '#ffffff',
                        pointBorderWidth: 2,
                        pointRadius: 4,
                        pointHoverRadius: 7
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            backgroundColor: 'rgba(0,0,0,0.8)',
                            titleFont: {
                                size: 12,
                                weight: 'bold'
                            },
                            bodyFont: {
                                size: 11
                            },
                            cornerRadius: 8,
                            padding: 10
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(0,0,0,0.05)'
                            },
                            ticks: {
                                font: {
                                    size: 10
                                },
                                stepSize: 1
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                font: {
                                    size: 10
                                },
                                maxRotation: 0
                            }
                        }
                    },
                    interaction: {
                        intersect: false,
                        mode: 'index'
                    }
                }
            });
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.collecteur', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\gestion-dechets-dakar\resources\views/collecteur/statistiques.blade.php ENDPATH**/ ?>