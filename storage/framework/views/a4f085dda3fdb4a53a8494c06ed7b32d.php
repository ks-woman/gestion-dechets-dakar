<?php $__env->startSection('title', 'Gestion des stocks'); ?>

<?php $__env->startSection('content'); ?>
    <div class="space-y-6">
        <div class="bg-white rounded-xl shadow-soft p-6">
            <h1 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
                <i class="fas fa-warehouse text-emerald-500"></i> Gestion des stocks
            </h1>
            <p class="text-gray-500 mt-1">Consultez les quantités disponibles par catégorie de déchet.</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <?php $__currentLoopData = $stocks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $stock): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php
                    $couleur = $stock->categorie->couleur ?? 'gray';
                ?>
                <div
                    class="bg-white rounded-xl shadow-soft border border-<?php echo e($couleur); ?>-200 overflow-hidden hover:shadow-lg transition">
                    <!-- En-tête coloré -->
                    <div
                        class="px-4 py-2 bg-<?php echo e($couleur); ?>-50 border-b border-<?php echo e($couleur); ?>-200 flex items-center gap-2">
                        <span class="text-3xl"><?php echo e($stock->categorie->icone ?? '📦'); ?></span>
                        <h3 class="text-lg font-bold text-gray-800"><?php echo e($stock->categorie->nom); ?></h3>
                    </div>

                    <!-- Contenu -->
                    <div class="p-4">
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-sm text-gray-500">Quantité</span>
                            <span class="text-2xl font-bold text-emerald-600"><?php echo e(number_format($stock->quantite, 1)); ?>

                                kg</span>
                        </div>
                        <div class="flex justify-between items-center mb-4">
                            <span class="text-sm text-gray-500">Prix unitaire</span>
                            <span
                                class="text-lg font-semibold text-gray-700"><?php echo e(number_format($stock->prix_unitaire ?? 0, 0)); ?>

                                FCFA/kg</span>
                        </div>

                        <a href="<?php echo e(route('admin.stocks.edit', $stock->id)); ?>"
                            class="btn-primary text-sm w-full text-center block">
                            <i class="fas fa-edit mr-1"></i> Modifier
                        </a>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\gestion-dechets-dakar\resources\views/admin/stocks/index.blade.php ENDPATH**/ ?>