<?php $__env->startSection('title', 'Ajouter une zone'); ?>

<?php $__env->startSection('content'); ?>
    <div class="bg-white rounded-xl shadow-soft p-6 max-w-2xl mx-auto">
        <h1 class="text-2xl font-bold text-gray-800 mb-4">Ajouter une zone de collecte</h1>

        <form method="POST" action="<?php echo e(route('admin.zones.store')); ?>">
            <?php echo csrf_field(); ?>
            <div class="mb-4">
                <label class="block text-gray-700">Nom de la zone</label>
                <input type="text" name="nom" class="w-full border rounded-lg p-2 focus:ring-2 focus:ring-emerald-500"
                    required>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700">Description (optionnelle)</label>
                <textarea name="description" rows="2" class="w-full border rounded-lg p-2 focus:ring-2 focus:ring-emerald-500"></textarea>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700">Quartiers</label>
                <select name="quartiers[]" multiple class="w-full border rounded-lg p-2 focus:ring-2 focus:ring-emerald-500"
                    required>
                    <?php $__currentLoopData = $quartiersDisponibles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $quartier): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($quartier); ?>"><?php echo e($quartier); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
                <p class="text-xs text-gray-500 mt-1">Maintenez Ctrl (ou Cmd) pour sélectionner plusieurs quartiers.</p>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700">Collecteurs affectés</label>
                <select name="collecteurs[]" multiple
                    class="w-full border rounded-lg p-2 focus:ring-2 focus:ring-emerald-500">
                    <?php $__currentLoopData = $collecteurs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $collecteur): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($collecteur->id); ?>"><?php echo e($collecteur->user->prenom ?? ''); ?>

                            <?php echo e($collecteur->user->nom ?? ''); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div class="flex gap-3">
                <button type="submit" class="btn-primary">Enregistrer</button>
                <a href="<?php echo e(route('admin.zones.index')); ?>" class="btn-gray">Annuler</a>
            </div>
        </form>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\gestion-dechets-dakar\resources\views/admin/zones/create.blade.php ENDPATH**/ ?>