<?php $__env->startSection('content'); ?>
    <div class="text-center">
        <h1 class="text-4xl font-bold text-gray-800 mb-4">
            Gestion des Déchets à Dakar
        </h1>
        <p class="text-xl text-gray-600 mb-8">
            Solution innovante pour la collecte et la valorisation des déchets dans les quartiers enclavés
        </p>

        <div class="flex justify-center gap-4">
            <?php if(auth()->guard()->guest()): ?>
                <a href="<?php echo e(route('register')); ?>" class="bg-green-500 text-white px-6 py-3 rounded-lg hover:bg-green-600">
                    Créer un compte
                </a>
                <a href="<?php echo e(route('login')); ?>" class="bg-blue-500 text-white px-6 py-3 rounded-lg hover:bg-blue-600">
                    Se connecter
                </a>
            <?php endif; ?>

            <?php if(auth()->guard()->check()): ?>
                <?php if(auth()->user()->isAdmin()): ?>
                    <a href="<?php echo e(route('admin.dashboard')); ?>"
                        class="bg-blue-500 text-white px-6 py-3 rounded-lg hover:bg-blue-600">
                        Accéder à mon espace (Admin)
                    </a>
                <?php elseif(auth()->user()->isCollecteur()): ?>
                    <a href="<?php echo e(route('collecteur.dashboard')); ?>"
                        class="bg-blue-500 text-white px-6 py-3 rounded-lg hover:bg-blue-600">
                        Accéder à mon espace (Collecteur)
                    </a>
                <?php else: ?>
                    <a href="<?php echo e(route('menage.dashboard')); ?>" class="bg-blue-500...">
                        Accéder à mon espace
                    </a>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>

    <div class="grid md:grid-cols-3 gap-6 mt-16">
        <div class="bg-white p-6 rounded-lg shadow text-center">
            <div class="text-4xl mb-3"></div>
            <h3 class="text-xl font-bold mb-2">Pour les ménages</h3>
            <p class="text-gray-600">Collecte à domicile, tri simplifié, points de fidélité</p>
        </div>

        <div class="bg-white p-6 rounded-lg shadow text-center">
            <div class="text-4xl mb-3"></div>
            <h3 class="text-xl font-bold mb-2">Pour les entreprises</h3>
            <p class="text-gray-600">Collecte professionnelle, volumes adaptés</p>
        </div>

        <div class="bg-white p-6 rounded-lg shadow text-center">
            <div class="text-4xl mb-3"></div>
            <h3 class="text-xl font-bold mb-2">Économie circulaire</h3>
            <p class="text-gray-600">Valorisation des déchets, récompenses à la clé</p>
        </div>
    </div>

    <div class="mt-16 bg-green-100 p-6 rounded-lg text-center">
        <h3 class="text-2xl font-bold text-green-800 mb-2">15 jours d'essai gratuit</h3>
        <p class="text-green-700">Kit de tri offert. Collecte à domicile. Puis 5000 FCFA/mois.</p>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\gestion-dechets-dakar\resources\views/home.blade.php ENDPATH**/ ?>