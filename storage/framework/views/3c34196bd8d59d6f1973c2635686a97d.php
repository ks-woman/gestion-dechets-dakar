<?php $__env->startSection('title', 'Créer un compte'); ?>

<?php $__env->startSection('content'); ?>
    <div class="w-full max-w-4xl mx-auto px-4">
        <div class="bg-white rounded-3xl shadow-2xl p-6 md:p-8">
            <!-- En-tête -->
            <div class="text-center mb-6">
                <div class="text-4xl mb-2"></div>
                <h1 class="text-2xl md:text-3xl font-bold text-gray-800">Créer un compte</h1>
                <p class="text-gray-600 text-sm mt-1">Choisissez votre type de compte pour commencer</p>
            </div>

            <!-- Grille des rôles -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <!-- Ménage -->
                <a href="<?php echo e(route('register.menage.form')); ?>"
                    class="group block bg-gray-50 rounded-xl p-4 border-2 border-gray-200 hover:border-emerald-400 transition-all duration-300 hover:shadow-lg hover:-translate-y-1 text-center">
                    <div
                        class="bg-emerald-100 w-12 h-12 rounded-xl flex items-center justify-center mx-auto mb-2 group-hover:bg-emerald-500 transition-colors duration-300">
                        <i
                            class="fas fa-home text-xl text-emerald-600 group-hover:text-white transition-colors duration-300"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-800">Ménage</h3>
                    <p class="text-gray-600 text-xs mt-0.5">Particulier habitant à Dakar</p>
                    <div class="mt-2 text-emerald-600 font-semibold text-xs group-hover:text-emerald-700">
                        S'inscrire →
                    </div>
                </a>

                <!-- Entreprise -->
                <a href="<?php echo e(route('register.entreprise.form')); ?>"
                    class="group block bg-gray-50 rounded-xl p-4 border-2 border-gray-200 hover:border-blue-400 transition-all duration-300 hover:shadow-lg hover:-translate-y-1 text-center">
                    <div
                        class="bg-blue-100 w-12 h-12 rounded-xl flex items-center justify-center mx-auto mb-2 group-hover:bg-blue-500 transition-colors duration-300">
                        <i
                            class="fas fa-building text-xl text-blue-600 group-hover:text-white transition-colors duration-300"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-800">Entreprise</h3>
                    <p class="text-gray-600 text-xs mt-0.5">Restaurant, commerce, hôtel</p>
                    <div class="mt-2 text-blue-600 font-semibold text-xs group-hover:text-blue-700">
                        S'inscrire →
                    </div>
                </a>

                <!-- Collecteur -->
                <a href="<?php echo e(route('register.collecteur.form')); ?>"
                    class="group block bg-gray-50 rounded-xl p-4 border-2 border-gray-200 hover:border-purple-400 transition-all duration-300 hover:shadow-lg hover:-translate-y-1 text-center">
                    <div
                        class="bg-purple-100 w-12 h-12 rounded-xl flex items-center justify-center mx-auto mb-2 group-hover:bg-purple-500 transition-colors duration-300">
                        <i
                            class="fas fa-truck text-xl text-purple-600 group-hover:text-white transition-colors duration-300"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-800">Collecteur</h3>
                    <p class="text-gray-600 text-xs mt-0.5">Agent de collecte des déchets</p>
                    <div class="mt-2 text-purple-600 font-semibold text-xs group-hover:text-purple-700">
                        S'inscrire →
                    </div>
                </a>

                <!-- Partenaire -->
                <a href="<?php echo e(route('register.partenaire.form')); ?>"
                    class="group block bg-gray-50 rounded-xl p-4 border-2 border-gray-200 hover:border-orange-400 transition-all duration-300 hover:shadow-lg hover:-translate-y-1 text-center">
                    <div
                        class="bg-orange-100 w-12 h-12 rounded-xl flex items-center justify-center mx-auto mb-2 group-hover:bg-orange-500 transition-colors duration-300">
                        <i
                            class="fas fa-handshake text-xl text-orange-600 group-hover:text-white transition-colors duration-300"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-800">Partenaire</h3>
                    <p class="text-gray-600 text-xs mt-0.5">Centre de recyclage ou compostage</p>
                    <div class="mt-2 text-orange-600 font-semibold text-xs group-hover:text-orange-700">
                        S'inscrire →
                    </div>
                </a>
            </div>

            <!-- Lien vers connexion -->
            <p class="text-center text-gray-600 text-sm mt-6">
                Déjà un compte ?
                <a href="<?php echo e(route('login')); ?>" class="text-emerald-600 hover:text-emerald-700 font-medium hover:underline">
                    Se connecter
                </a>
            </p>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.auth', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\gestion-dechets-dakar\resources\views/auth/choose-role.blade.php ENDPATH**/ ?>