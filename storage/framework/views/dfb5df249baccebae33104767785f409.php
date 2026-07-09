<?php $__env->startSection('title', 'Zones de collecte'); ?>

<?php $__env->startSection('content'); ?>
    <div class="bg-white rounded-xl shadow-soft">
        <div class="p-5 border-b flex justify-between items-center">
            <h3 class="font-semibold text-gray-800 flex items-center gap-2">
                <i class="fas fa-map-marked-alt text-emerald-500"></i> Zones de collecte
            </h3>
            <a href="<?php echo e(route('admin.zones.create')); ?>" class="btn-primary text-sm">
                <i class="fas fa-plus mr-1"></i> Ajouter une zone
            </a>
        </div>

        <div class="overflow-x-auto p-5">
            <table class="w-full">
                <thead>
                    <tr class="text-left text-gray-500 border-b">
                        <th class="pb-3">ID</th>
                        <th class="pb-3">Nom</th>
                        <th class="pb-3">Quartiers</th>
                        <th class="pb-3">Collecteurs</th>
                        <th class="pb-3">Clients</th>
                        <th class="pb-3">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $zones; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $zone): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr class="border-b hover:bg-gray-50">
                            <td class="py-3"><?php echo e($zone->id); ?></td>
                            <td class="py-3 font-medium"><?php echo e($zone->nom); ?></td>
                            <td class="py-3">
                                <?php $__currentLoopData = $zone->quartiers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $q): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <span class="badge-gray"><?php echo e($q); ?></span>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </td>
                            <td class="py-3">
                                <?php $__empty_2 = true; $__currentLoopData = $zone->collecteurs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $collecteur): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_2 = false; ?>
                                    <span class="badge-info"><?php echo e($collecteur->user->prenom ?? ''); ?>

                                        <?php echo e($collecteur->user->nom ?? ''); ?></span>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_2): ?>
                                    <span class="text-gray-400 text-sm">Aucun</span>
                                <?php endif; ?>
                            </td>
                            <td class="py-3"><?php echo e($zone->nombre_clients); ?></td>
                            <td class="py-3">
                                <div class="flex gap-2">
                                    <a href="<?php echo e(route('admin.zones.show', $zone->id)); ?>"
                                        class="text-blue-500 hover:text-blue-700"><i class="fas fa-eye"></i></a>
                                    <a href="<?php echo e(route('admin.zones.edit', $zone->id)); ?>"
                                        class="text-green-500 hover:text-green-700"><i class="fas fa-edit"></i></a>
                                    <form method="POST" action="<?php echo e(route('admin.zones.destroy', $zone->id)); ?>"
                                        class="inline" onsubmit="return confirm('Supprimer ?')">
                                        <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                        <button type="submit" class="text-red-500 hover:text-red-700"><i
                                                class="fas fa-trash"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="6" class="py-6 text-center text-gray-500">Aucune zone</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <div class="p-5 border-t"><?php echo e($zones->links()); ?></div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\gestion-dechets-dakar\resources\views/admin/zones/index.blade.php ENDPATH**/ ?>