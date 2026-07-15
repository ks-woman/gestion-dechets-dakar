<?php $__env->startSection('title', 'Mes statistiques'); ?>

<?php $__env->startSection('content'); ?>
    <div class="space-y-6">
        <div class="bg-white rounded-xl shadow-soft p-6">
            <h1 class="text-2xl font-bold text-gray-800"> Mes statistiques</h1>
            <p class="text-gray-500 mt-1">Suivez vos performances en tant que collecteur.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="card-info">
                <div>
                    <p class="text-gray-500 text-sm">Collectes</p>
                    <p class="text-2xl font-bold text-blue-600"><?php echo e($totalCollectes); ?></p>
                </div>
            </div>
            <div class="card-success">
                <div>
                    <p class="text-gray-500 text-sm">Poids total</p>
                    <p class="text-2xl font-bold text-emerald-600"><?php echo e(number_format($totalPoids->total ?? 0, 1)); ?> kg</p>
                </div>
            </div>
            <div class="card-warning">
                <div>
                    <p class="text-gray-500 text-sm">Points générés</p>
                    <p class="text-2xl font-bold text-yellow-600"><?php echo e(number_format($totalPoints ?? 0)); ?></p>
                </div>
            </div>
            <div class="card-danger">
                <div>
                    <p class="text-gray-500 text-sm">Note moyenne</p>
                    <p class="text-2xl font-bold text-red-600">
                        <?php echo e(number_format(auth()->user()->collecteur->note_moyenne ?? 0, 1)); ?> </p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-soft p-6">
            <h3 class="font-semibold text-gray-800 mb-4"> Évolution des collectes (<?php echo e(now()->year); ?>)</h3>
            <canvas id="collectesChart" height="150"></canvas>
        </div>

        <div class="bg-white rounded-xl shadow-soft p-6">
            <h3 class="font-semibold text-gray-800 mb-4"> Top 5 clients collectés</h3>
            <ul class="space-y-2">
                <?php $__empty_1 = true; $__currentLoopData = $topClients; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $client): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <li class="flex justify-between items-center p-2 bg-gray-50 rounded"><span><?php echo e($client->user->prenom); ?>

                            <?php echo e($client->user->nom); ?></span><span class="font-bold"><?php echo e($client->total); ?> collectes</span>
                    </li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <li class="text-gray-500">Aucun client pour le moment.</li>
                <?php endif; ?>
            </ul>
        </div>

        <div class="bg-white rounded-xl shadow-soft p-6">
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
            const moisKeys = ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin', 'Juil', 'Aoû', 'Sep', 'Oct', 'Nov', 'Déc'];
            const data = <?php echo json_encode($collectesParMois, 15, 512) ?>;
            const values = moisKeys.map((_, i) => data[i + 1] || 0);
            new Chart(document.getElementById('collectesChart'), {
                type: 'line',
                data: {
                    labels: moisKeys,
                    datasets: [{
                        label: 'Collectes',
                        data: values,
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
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.collecteur', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\gestion-dechets-dakar\resources\views/collecteur/statistiques.blade.php ENDPATH**/ ?>