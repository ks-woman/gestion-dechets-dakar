<?php $__env->startSection('title', 'Mes statistiques'); ?>

<?php $__env->startSection('content'); ?>
    <div class="space-y-6">
        <!-- En-tête -->
        <div class="bg-white rounded-xl shadow p-6">
            <h1 class="text-2xl font-bold text-gray-800"> Mes statistiques</h1>
            <p class="text-gray-500 mt-1">Suivez votre impact écologique et vos performances</p>
        </div>

        <!-- Cartes récapitulatives -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl shadow p-4 text-white">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-sm opacity-90">Total collectes</p>
                        <p class="text-2xl font-bold"><?php echo e($totalCollectes); ?></p>
                    </div>
                    <div class="text-2xl"></div>
                </div>
            </div>

            <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-xl shadow p-4 text-white">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-sm opacity-90">Total points</p>
                        <p class="text-2xl font-bold"><?php echo e($totalPoints); ?></p>
                    </div>
                    <div class="text-2xl"></div>
                </div>
            </div>

            <div class="bg-gradient-to-r from-yellow-500 to-yellow-600 rounded-xl shadow p-4 text-white">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-sm opacity-90">Recyclable (kg)</p>
                        <p class="text-2xl font-bold"><?php echo e(number_format($totalRecyclable, 1)); ?></p>
                    </div>
                    <div class="text-2xl"></div>
                </div>
            </div>

            <div class="bg-gradient-to-r from-emerald-500 to-emerald-600 rounded-xl shadow p-4 text-white">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-sm opacity-90">Organique (kg)</p>
                        <p class="text-2xl font-bold"><?php echo e(number_format($totalOrganique, 1)); ?></p>
                    </div>
                    <div class="text-2xl"></div>
                </div>
            </div>
        </div>

        <!-- Graphique des collectes -->
        <div class="bg-white rounded-xl shadow p-6">
            <h2 class="text-lg font-bold text-gray-800 mb-4">Évolution des collectes</h2>
            <canvas id="collectesChart" height="100"></canvas>
        </div>

        <!-- Graphique des déchets par type -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-white rounded-xl shadow p-6">
                <h2 class="text-lg font-bold text-gray-800 mb-4"> Déchets par type</h2>
                <canvas id="dechetsChart" height="200"></canvas>
            </div>

            <div class="bg-white rounded-xl shadow p-6">
                <h2 class="text-lg font-bold text-gray-800 mb-4"> Points par mois</h2>
                <canvas id="pointsChart" height="200"></canvas>
            </div>
        </div>

        <!-- Dernières collectes -->
        <div class="bg-white rounded-xl shadow p-6">
            <h2 class="text-lg font-bold text-gray-800 mb-4"> Détail des collectes</h2>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b">
                            <th class="text-left py-2">Date</th>
                            <th class="text-left py-2">Recyclable</th>
                            <th class="text-left py-2">Organique</th>
                            <th class="text-left py-2">Résiduel</th>
                            <th class="text-left py-2">Points</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $collectes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $collecte): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr class="border-b">
                                <td class="py-2"><?php echo e(\Carbon\Carbon::parse($collecte->date_collecte)->format('d/m/Y')); ?>

                                </td>
                                <td class="py-2"><?php echo e(number_format($collecte->poids_recyclable, 1)); ?> kg</td>
                                <td class="py-2"><?php echo e(number_format($collecte->poids_organique, 1)); ?> kg</td>
                                <td class="py-2"><?php echo e(number_format($collecte->poids_residuel, 1)); ?> kg</td>
                                <td class="py-2">+<?php echo e($collecte->points_obtenus); ?> pts</td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
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
                labels: <?php echo json_encode(array_keys($collectesParMois)); ?>,
                datasets: [{
                    label: 'Nombre de collectes',
                    data: <?php echo json_encode(array_values($collectesParMois)); ?>,
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

        // Graphique des déchets par type
        const dechetsCtx = document.getElementById('dechetsChart').getContext('2d');
        new Chart(dechetsCtx, {
            type: 'doughnut',
            data: {
                labels: ['Recyclable', 'Organique', 'Résiduel'],
                datasets: [{
                    data: [
                        <?php echo e($totalRecyclable); ?>,
                        <?php echo e($totalOrganique); ?>,
                        <?php echo e($totalResiduel); ?>

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

        // Graphique des points par mois
        const pointsCtx = document.getElementById('pointsChart').getContext('2d');
        new Chart(pointsCtx, {
            type: 'bar',
            data: {
                labels: <?php echo json_encode(array_keys($pointsParMois)); ?>,
                datasets: [{
                    label: 'Points gagnés',
                    data: <?php echo json_encode(array_values($pointsParMois)); ?>,
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
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.menage', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\gestion-dechets-dakar\resources\views/menage/statistiques.blade.php ENDPATH**/ ?>