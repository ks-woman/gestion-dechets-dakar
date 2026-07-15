<?php $__env->startSection('title', 'Sélectionner une collecte'); ?>

<?php $__env->startSection('content'); ?>
    <div class="bg-white rounded-lg shadow p-6">
        <h1 class="text-2xl font-bold mb-6">Sélectionner un client</h1>

        <?php if($clients->isEmpty()): ?>
            <div class="bg-yellow-100 text-yellow-700 p-4 rounded-lg">
                Aucune collecte planifiée pour aujourd'hui dans votre quartier.
            </div>
        <?php else: ?>
            <div class="space-y-4">
                <?php $__currentLoopData = $clients; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $client): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="border rounded-lg p-4 flex justify-between items-center">
                        <div>
                            <p class="font-semibold"><?php echo e($client->prenom); ?> <?php echo e($client->nom); ?></p>
                            <p class="text-sm text-gray-500"><?php echo e($client->adresse); ?></p>
                            <p class="text-sm text-gray-500">Quartier : <?php echo e($client->quartier); ?></p>
                        </div>
                        <a href="<?php echo e(route('collecteur.collecte.form', $client->id)); ?>"
                            class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700">
                            Enregistrer collecte
                        </a>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        <?php endif; ?>

        <a href="<?php echo e(route('collecteur.dashboard')); ?>" class="inline-block mt-4 text-gray-500">← Retour</a>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.collecteur', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\gestion-dechets-dakar\resources\views/collecteur/selectionner-collecte.blade.php ENDPATH**/ ?>