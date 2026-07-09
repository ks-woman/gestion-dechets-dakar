<?php $__env->startSection('title', 'Mes primes'); ?>

<?php $__env->startSection('content'); ?>
    <div class="space-y-6">
        <div class="bg-white rounded-xl shadow-soft p-6">
            <h1 class="text-2xl font-bold text-gray-800"> Mes primes</h1>
            <p class="text-gray-500 mt-1">Retrouvez ici le détail de vos primes mensuelles.</p>
        </div>

        <div class="bg-gradient-to-r from-emerald-500 to-emerald-700 rounded-xl shadow-soft p-6 text-white">
            <p class="text-sm opacity-80">Total des primes validées</p>
            <p class="text-3xl font-bold"><?php echo e(number_format($totalPrimes, 0, ',', ' ')); ?> FCFA</p>
        </div>

        <div class="bg-white rounded-xl shadow-soft overflow-hidden">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Mois</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Montant</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Statut</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Détails</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $primes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $prime): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr class="border-b">
                            <td class="px-4 py-3"><?php echo e(\Carbon\Carbon::create($prime->annee, $prime->mois)->format('F Y')); ?>

                            </td>
                            <td class="px-4 py-3 font-bold text-emerald-600">
                                <?php echo e(number_format($prime->montant_total, 0, ',', ' ')); ?> FCFA</td>
                            <td class="px-4 py-3">
                                <?php if($prime->statut == 'calcule'): ?>
                                    <span class="badge-warning"> En attente</span>
                                <?php elseif($prime->statut == 'valide'): ?>
                                    <span class="badge-success">Validée</span>
                                <?php else: ?>
                                    <span class="badge-info"> Payée</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-4 py-3"><button onclick="afficherDetails(<?php echo e(json_encode($prime->details)); ?>)"
                                    class="text-blue-500 hover:underline text-sm">Voir détails</button></td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="4" class="px-4 py-8 text-center text-gray-500">Aucune prime calculée.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
            <div class="px-4 py-3 border-t"><?php echo e($primes->links()); ?></div>
        </div>
    </div>

    <script>
        function afficherDetails(details) {
            if (!details) return alert('Aucun détail.');
            let msg = '';
            for (let [key, value] of Object.entries(details)) msg += `${key.replace(/_/g,' ').toUpperCase()} : ${value}\n`;
            alert(msg);
        }
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.collecteur', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\gestion-dechets-dakar\resources\views/collecteur/primes.blade.php ENDPATH**/ ?>