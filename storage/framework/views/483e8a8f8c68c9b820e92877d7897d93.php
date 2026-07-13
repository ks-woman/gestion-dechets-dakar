<?php $__env->startSection('title', 'Collectes'); ?>

<?php $__env->startSection('content'); ?>
    <div class="bg-white rounded-xl shadow-soft">
        <div class="p-5 border-b flex justify-between items-center">
            <h3 class="font-semibold text-gray-800 flex items-center gap-2">
                <i class="fas fa-truck text-emerald-500"></i> Gestion des collectes
            </h3>
            <span class="text-sm text-gray-500">Total : <?php echo e($collectes->total()); ?></span>
        </div>

        <div class="overflow-x-auto p-5">
            <table class="w-full">
                <thead>
                    <tr class="text-left text-gray-500 border-b">
                        <th class="pb-3">ID</th>
                        <th class="pb-3">Client</th>
                        <th class="pb-3">Date</th>
                        <th class="pb-3">Recyclable</th>
                        <th class="pb-3">Organique</th>
                        <th class="pb-3">Résiduel</th>
                        <th class="pb-3">Points</th>
                        <th class="pb-3">Statut</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $collectes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $collecte): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr class="border-b hover:bg-gray-50">
                            <td class="py-3"><?php echo e($collecte->id); ?></td>
                            <td class="py-3 font-medium"><?php echo e($collecte->user->prenom ?? 'N/A'); ?>

                                <?php echo e($collecte->user->nom ?? ''); ?></td>
                            <td class="py-3"><?php echo e(\Carbon\Carbon::parse($collecte->date_collecte)->format('d/m/Y')); ?></td>
                            <td class="py-3"><?php echo e(number_format($collecte->poids_recyclable, 1)); ?> kg</td>
                            <td class="py-3"><?php echo e(number_format($collecte->poids_organique, 1)); ?> kg</td>
                            <td class="py-3"><?php echo e(number_format($collecte->poids_residuel, 1)); ?> kg</td>
                            <td class="py-3 font-semibold text-yellow-600"><?php echo e($collecte->points_obtenus); ?> pts</td>
                            <td class="py-3">
                                <?php if($collecte->statut == 'realisee'): ?>
                                    <span class="badge-success"> Réalisée</span>
                                <?php elseif($collecte->statut == 'planifiee'): ?>
                                    <span class="badge-info"> Planifiée</span>
                                <?php elseif($collecte->statut == 'valorisee'): ?>
                                    <span class="badge-warning">Valorisee</span>
                                <?php else: ?>
                                    <span class="badge-gray"><?php echo e($collecte->statut); ?></span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="8" class="py-6 text-center text-gray-500">Aucune collecte trouvée</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div class="p-5 border-t"><?php echo e($collectes->links()); ?></div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\gestion-dechets-dakar\resources\views/admin/collectes.blade.php ENDPATH**/ ?>