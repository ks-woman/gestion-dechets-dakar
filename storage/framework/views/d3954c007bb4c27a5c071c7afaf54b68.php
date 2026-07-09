<?php $__env->startSection('title', 'Kits commandés'); ?>

<?php $__env->startSection('content'); ?>
    <div class="bg-white rounded-xl shadow">
        <div class="p-6 border-b">
            <h1 class="text-2xl font-bold"> Kits commandés</h1>
            <p class="text-gray-500 mt-1">Liste de tous les kits de tri commandés</p>
        </div>

        <div class="overflow-x-auto p-6">
            <table class="w-full">
                <thead>
                    <tr class="text-left border-b">
                        <th class="pb-3">ID</th>
                        <th class="pb-3">Client</th>
                        <th class="pb-3">Type</th>
                        <th class="pb-3">Date commande</th>
                        <th class="pb-3">Date livraison</th>
                        <th class="pb-3">Statut</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $kits; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $kit): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr class="border-b">
                            <td class="py-3"><?php echo e($kit->id); ?></td>
                            <td class="py-3"><?php echo e($kit->user->prenom); ?> <?php echo e($kit->user->nom); ?></td>
                            <td class="py-3"><?php echo e($kit->type_kit); ?></td>
                            <td class="py-3">
                                <?php echo e($kit->date_demande ? \Carbon\Carbon::parse($kit->date_demande)->format('d/m/Y') : '-'); ?>

                            </td>
                            <td class="py-3">
                                <?php echo e($kit->date_distribution ? \Carbon\Carbon::parse($kit->date_distribution)->format('d/m/Y') : '-'); ?>

                            </td>
                            <td class="py-3">
                                <?php if($kit->statut == 'en_attente'): ?>
                                    <span class="bg-yellow-100 text-yellow-800 px-2 py-1 rounded text-sm"> En
                                        attente</span>
                                <?php elseif($kit->statut == 'actif'): ?>
                                    <span class="bg-green-100 text-green-800 px-2 py-1 rounded text-sm"> Actif</span>
                                <?php else: ?>
                                    <span
                                        class="bg-gray-100 text-gray-800 px-2 py-1 rounded text-sm"><?php echo e($kit->statut_kit); ?></span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="6" class="py-6 text-center text-gray-500">Aucun kit commandé</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\gestion-dechets-dakar\resources\views/admin/kits.blade.php ENDPATH**/ ?>