<?php $__env->startSection('title', 'Activer un kit'); ?>

<?php $__env->startSection('content'); ?>
    <div class="bg-white rounded-lg shadow p-6">
        <h1 class="text-2xl font-bold mb-6"> Activer un kit</h1>

        <form action="<?php echo e(route('collecteur.kit.activer')); ?>" method="POST">
            <?php echo csrf_field(); ?>
            <div class="mb-4">
                <label class="block text-gray-700 mb-2">Code du kit</label>
                <input type="text" name="code_kit" class="w-full border rounded-lg px-3 py-2"
                    placeholder="Entrez le code du kit">
            </div>

            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg">Activer</button>
            <a href="<?php echo e(route('collecteur.dashboard')); ?>" class="ml-2 text-gray-500">Retour</a>
        </form>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.collecteur', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\gestion-dechets-dakar\resources\views/collecteur/activer-kit.blade.php ENDPATH**/ ?>