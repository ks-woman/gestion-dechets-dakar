<?php $__env->startSection('content'); ?>
    <div class="max-w-2xl mx-auto">
        <h2 class="text-2xl font-bold text-center mb-8">Créer un compte</h2>
        <p class="text-center text-gray-600 mb-8">Choisissez votre type de compte</p>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <a href="<?php echo e(route('register.menage.form')); ?>"
                class="block p-6 bg-white rounded-lg shadow hover:shadow-lg transition">
                <div class="text-4xl mb-3">🏠</div>
                <h3 class="text-xl font-bold">Ménage</h3>
                <p class="text-gray-600 mt-2">Particulier habitant à Dakar</p>
            </a>

            <a href="<?php echo e(route('register.entreprise.form')); ?>"
                class="block p-6 bg-white rounded-lg shadow hover:shadow-lg transition">
                <div class="text-4xl mb-3">🏢</div>
                <h3 class="text-xl font-bold">Entreprise</h3>
                <p class="text-gray-600 mt-2">Restaurant, commerce, hôtel</p>
            </a>

            <a href="<?php echo e(route('register.collecteur.form')); ?>"
                class="block p-6 bg-white rounded-lg shadow hover:shadow-lg transition">
                <div class="text-4xl mb-3">🛵</div>
                <h3 class="text-xl font-bold">Collecteur</h3>
                <p class="text-gray-600 mt-2">Agent de collecte des déchets</p>
            </a>

            <a href="<?php echo e(route('register.partenaire.form')); ?>"
                class="block p-6 bg-white rounded-lg shadow hover:shadow-lg transition">
                <div class="text-4xl mb-3">♻️</div>
                <h3 class="text-xl font-bold">Partenaire</h3>
                <p class="text-gray-600 mt-2">Centre de recyclage ou compostage</p>
            </a>
        </div>

        <p class="text-center mt-8">
            Déjà un compte ?
            <a href="<?php echo e(route('login')); ?>" class="text-blue-500">Se connecter</a>
        </p>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\gestion-dechets-dakar\resources\views/auth/choose-role.blade.php ENDPATH**/ ?>