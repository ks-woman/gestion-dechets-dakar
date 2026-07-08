<?php $__env->startSection('title', 'Choisir votre rôle'); ?>

<?php $__env->startSection('content'); ?>
    <div class="bg-white rounded-2xl shadow-2xl p-8">
        <div class="text-center mb-6">
            <div class="text-5xl mb-2">♻️</div>
            <h1 class="text-2xl font-bold text-gray-800">Créer un compte</h1>
            <p class="text-gray-500 text-sm mt-1">Choisissez votre type de compte</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <a href="<?php echo e(route('register.menage.form')); ?>"
                class="block p-4 bg-gray-50 rounded-xl hover:bg-emerald-50 hover:border-emerald-300 border-2 border-transparent transition text-center">
                <div class="text-4xl mb-2">🏠</div>
                <h3 class="font-bold text-gray-800">Ménage</h3>
                <p class="text-sm text-gray-500">Particulier habitant à Dakar</p>
            </a>

            <a href="<?php echo e(route('register.entreprise.form')); ?>"
                class="block p-4 bg-gray-50 rounded-xl hover:bg-emerald-50 hover:border-emerald-300 border-2 border-transparent transition text-center">
                <div class="text-4xl mb-2">🏢</div>
                <h3 class="font-bold text-gray-800">Entreprise</h3>
                <p class="text-sm text-gray-500">Restaurant, commerce, hôtel</p>
            </a>

            <a href="<?php echo e(route('register.collecteur.form')); ?>"
                class="block p-4 bg-gray-50 rounded-xl hover:bg-emerald-50 hover:border-emerald-300 border-2 border-transparent transition text-center">
                <div class="text-4xl mb-2">🛵</div>
                <h3 class="font-bold text-gray-800">Collecteur</h3>
                <p class="text-sm text-gray-500">Agent de collecte des déchets</p>
            </a>

            <a href="<?php echo e(route('register.partenaire.form')); ?>"
                class="block p-4 bg-gray-50 rounded-xl hover:bg-emerald-50 hover:border-emerald-300 border-2 border-transparent transition text-center">
                <div class="text-4xl mb-2">♻️</div>
                <h3 class="font-bold text-gray-800">Partenaire</h3>
                <p class="text-sm text-gray-500">Centre de recyclage ou compostage</p>
            </a>
        </div>

        <p class="text-center text-gray-500 text-sm mt-6">
            Déjà un compte ?
            <a href="<?php echo e(route('login')); ?>" class="text-emerald-600 hover:text-emerald-700 font-medium">
                Se connecter
            </a>
        </p>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.auth', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\gestion-dechets-dakar\resources\views/auth/choose-role.blade.php ENDPATH**/ ?>