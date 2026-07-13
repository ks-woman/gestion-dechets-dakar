<?php $__env->startSection('title', 'Demander une collecte'); ?>

<?php $__env->startSection('content'); ?>
    <div class="bg-white rounded-xl shadow-lg p-6">
        <div class="flex items-center gap-3 mb-6">
            <div class="text-3xl"></div>
            <h1 class="text-2xl font-bold text-gray-800">Demander une collecte</h1>
        </div>

        <?php if(auth()->user()->statut_compte == 'essai_15j'): ?>
            <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-6">
                <div class="flex items-center gap-3">
                    <div class="text-2xl"></div>
                    <div>
                        <p class="font-semibold text-green-800">Période d'essai gratuite !</p>
                        <p class="text-sm text-green-600">Vous êtes en période d'essai. Cette collecte est gratuite.</p>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <?php if(session('success')): ?>
            <div class="bg-green-500 text-white p-3 rounded-lg mb-4"><?php echo e(session('success')); ?></div>
        <?php endif; ?>
        <?php if(session('error')): ?>
            <div class="bg-red-500 text-white p-3 rounded-lg mb-4"><?php echo e(session('error')); ?></div>
        <?php endif; ?>

        <form method="POST" action="<?php echo e(route('collecte.demander.post')); ?>">
            <?php echo csrf_field(); ?>

            <div class="mb-6">
                <label class="block text-gray-700 mb-2"> Date souhaitée</label>
                <input type="date" name="date_souhaitee"
                    class="w-full border rounded-lg p-2 focus:ring-2 focus:ring-emerald-500" min="<?php echo e(date('Y-m-d')); ?>"
                    required>
            </div>

            <div class="mb-6">
                <label class="block text-gray-700 mb-2"> Adresse de collecte</label>
                <textarea name="adresse" rows="2" class="w-full border rounded-lg p-2 focus:ring-2 focus:ring-emerald-500"
                    required><?php echo e(auth()->user()->adresse); ?></textarea>
                <p class="text-xs text-gray-500 mt-1">Laissez vide pour utiliser votre adresse d'inscription</p>
            </div>

            <div class="mb-6">
                <label class="block text-gray-700 mb-2"> Instructions spéciales (optionnel)</label>
                <textarea name="instructions" rows="3" class="w-full border rounded-lg p-2 focus:ring-2 focus:ring-emerald-500"
                    placeholder="Ex: Sonnette à gauche, portail vert, etc."></textarea>
            </div>

            <div class="bg-blue-50 rounded-lg p-4 mb-6">
                <h3 class="font-semibold text-blue-800 mb-2"> À savoir</h3>
                <ul class="text-sm text-blue-700 space-y-1">
                    <li>✓ Les déchets doivent être triés (recyclable, organique, résiduel)</li>
                    <li>✓ Déposez vos sacs devant votre domicile le jour de la collecte</li>
                    <li>✓ Un collecteur se présentera entre 8h et 17h</li>
                    <li>✓ Vous recevrez une notification de confirmation</li>
                </ul>
            </div>

            <button type="submit"
                class="w-full bg-emerald-500 text-white py-3 rounded-lg font-semibold hover:bg-emerald-600 transition">
                Confirmer ma demande
            </button>
        </form>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.menage', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\gestion-dechets-dakar\resources\views/collecte/demander.blade.php ENDPATH**/ ?>