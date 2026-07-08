<?php $__env->startSection('title', 'Déchets reçus'); ?>

<?php $__env->startSection('content'); ?>
    <div class="bg-white rounded-xl shadow-md">
        <div class="p-5 border-b flex justify-between items-center">
            <h3 class="font-semibold text-gray-800 flex items-center gap-2">
                <i class="fas fa-boxes text-purple-500"></i> Déchets reçus
            </h3>
            <div class="text-sm text-gray-500">
                Total : <?php echo e($collectes->total()); ?> collecte(s)
            </div>
        </div>

        <div class="overflow-x-auto p-5">
            <table class="w-full">
                <thead>
                    <tr class="text-left text-gray-500 border-b">
                        <th class="pb-3">ID</th>
                        <th class="pb-3">Client</th>
                        <th class="pb-3">Adresse</th>
                        <th class="pb-3">Date collecte</th>
                        <th class="pb-3">Recyclable</th>
                        <th class="pb-3">Organique</th>
                        <th class="pb-3">Résiduel</th>
                        <th class="pb-3">Statut</th>
                        <th class="pb-3">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $collectes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $collecte): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr class="border-b hover:bg-gray-50">
                            <td class="py-3"><?php echo e($collecte->id); ?></td>
                            <td class="py-3 font-medium"><?php echo e($collecte->user->prenom); ?> <?php echo e($collecte->user->nom); ?></td>
                            <td class="py-3 text-gray-600"><?php echo e($collecte->adresse); ?></td>
                            <td class="py-3"><?php echo e(\Carbon\Carbon::parse($collecte->date_collecte)->format('d/m/Y')); ?></td>
                            <td class="py-3"><?php echo e(number_format($collecte->poids_recyclable, 1)); ?> kg</td>
                            <td class="py-3"><?php echo e(number_format($collecte->poids_organique, 1)); ?> kg</td>
                            <td class="py-3"><?php echo e(number_format($collecte->poids_residuel, 1)); ?> kg</td>
                            <td class="py-3">
                                <?php if($collecte->statut == 'valorisee'): ?>
                                    <span class="px-2 py-1 rounded text-xs bg-green-100 text-green-800">✅ Valorisee</span>
                                <?php elseif($collecte->statut == 'realisee'): ?>
                                    <span class="px-2 py-1 rounded text-xs bg-yellow-100 text-yellow-800">⏳ En
                                        attente</span>
                                <?php else: ?>
                                    <span
                                        class="px-2 py-1 rounded text-xs bg-gray-100 text-gray-800"><?php echo e($collecte->statut); ?></span>
                                <?php endif; ?>
                            </td>
                            <td class="py-3">
                                <?php if($collecte->statut == 'realisee'): ?>
                                    <form method="POST" action="<?php echo e(route('partenaire.valider', $collecte->id)); ?>"
                                        class="inline" onsubmit="return confirm('Valider la réception de ces déchets ?')">
                                        <?php echo csrf_field(); ?>
                                        <button type="submit"
                                            class="bg-emerald-500 text-white px-3 py-1 rounded text-sm hover:bg-emerald-600">
                                            <i class="fas fa-check mr-1"></i> Valider
                                        </button>
                                    </form>
                                <?php else: ?>
                                    <span class="text-gray-400 text-sm">Déjà validé</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="9" class="py-6 text-center text-gray-500">Aucune collecte trouvée</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div class="p-5 border-t">
            <?php echo e($collectes->links()); ?>

        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.partenaire', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\gestion-dechets-dakar\resources\views/partenaire/dechets.blade.php ENDPATH**/ ?>