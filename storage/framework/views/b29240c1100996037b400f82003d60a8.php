<?php $__env->startSection('title', 'Historique des collectes'); ?>

<?php $__env->startSection('content'); ?>
    <div class="bg-white rounded-xl shadow-lg p-6">
        <div class="flex items-center gap-3 mb-6">
            <div class="text-3xl"></div>
            <h1 class="text-2xl font-bold text-gray-800">Historique des collectes</h1>
        </div>

        <?php if(session('success')): ?>
            <div class="bg-green-500 text-white p-3 rounded-lg mb-4"><?php echo e(session('success')); ?></div>
        <?php endif; ?>
        <?php if(session('error')): ?>
            <div class="bg-red-500 text-white p-3 rounded-lg mb-4"><?php echo e(session('error')); ?></div>
        <?php endif; ?>

        <?php if($collectes->count() > 0): ?>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b">
                            <th class="text-left py-3">Date</th>
                            <th class="text-left py-3">Adresse</th>
                            <th class="text-left py-3">Recyclable</th>
                            <th class="text-left py-3">Organique</th>
                            <th class="text-left py-3">Résiduel</th>
                            <th class="text-left py-3">Points</th>
                            <th class="text-left py-3">Statut</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $collectes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $collecte): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr class="border-b">
                                <td class="py-3"><?php echo e(\Carbon\Carbon::parse($collecte->date_collecte)->format('d/m/Y')); ?>

                                </td>
                                <td class="py-3"><?php echo e(Str::limit($collecte->adresse ?? '-', 30)); ?></td>
                                <td class="py-3"><?php echo e($collecte->poids_recyclable); ?> kg</td>
                                <td class="py-3"><?php echo e($collecte->poids_organique); ?> kg</td>
                                <td class="py-3"><?php echo e($collecte->poids_residuel); ?> kg</td>
                                <td class="py-3">
                                    <span class="bg-yellow-100 text-yellow-800 px-2 py-1 rounded text-sm">
                                        +<?php echo e($collecte->points_obtenus); ?> pts
                                    </span>
                                </td>
                                <td class="py-3">
                                    <?php if($collecte->statut == 'planifiee'): ?>
                                        <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded text-sm"> Planifiée</span>
                                    <?php elseif($collecte->statut == 'realisee'): ?>
                                        <span class="bg-green-100 text-green-800 px-2 py-1 rounded text-sm">
                                            Réalisée</span>
                                    <?php elseif($collecte->statut == 'en_cours'): ?>
                                        <span class="bg-yellow-100 text-yellow-800 px-2 py-1 rounded text-sm"> En
                                            cours</span>
                                    <?php else: ?>
                                        <span
                                            class="bg-gray-100 text-gray-800 px-2 py-1 rounded text-sm"><?php echo e($collecte->statut); ?></span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>

            <div class="mt-6">
                <?php echo e($collectes->links()); ?>

            </div>
        <?php else: ?>
            <div class="text-center py-12 text-gray-500">
                <div class="text-5xl mb-3"></div>
                <p>Aucune collecte pour le moment</p>
                <a href="<?php echo e(route('collecte.demander')); ?>"
                    class="text-emerald-600 hover:text-emerald-700 mt-3 inline-block font-medium">
                    → Demander une collecte
                </a>
            </div>
        <?php endif; ?>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.menage', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\gestion-dechets-dakar\resources\views/collecte/historique.blade.php ENDPATH**/ ?>