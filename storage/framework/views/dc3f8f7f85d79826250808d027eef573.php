<?php $__env->startSection('content'); ?>
    <div class="min-h-screen bg-white">
        <!-- ============================================ -->
        <!-- SECTION 1 : HERO (plein écran)              -->
        <!-- ============================================ -->
        <section
            class="relative min-h-screen flex items-center justify-center bg-gradient-to-br from-emerald-700 via-emerald-600 to-emerald-800 text-white overflow-hidden">
            <!-- Effet de fond -->
            <div class="absolute inset-0 opacity-10">
                <div class="absolute top-0 left-0 w-96 h-96 bg-white rounded-full -translate-x-1/2 -translate-y-1/2"></div>
                <div class="absolute bottom-0 right-0 w-96 h-96 bg-white rounded-full translate-x-1/2 translate-y-1/2"></div>
                <div class="absolute top-1/2 left-1/2 w-64 h-64 bg-white rounded-full -translate-x-1/2 -translate-y-1/2">
                </div>
            </div>

            <div class="relative z-10 max-w-6xl w-full mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <!-- Logo -->
                <div class="flex justify-center mb-6">
                    <div class="bg-white/20 backdrop-blur-sm p-4 rounded-2xl inline-block">
                        <span class="text-6xl"></span>
                    </div>
                </div>
                <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold mb-4 leading-tight">
                    Gestion des Déchets à Dakar
                </h1>
                <p class="text-xl md:text-2xl text-emerald-100 max-w-3xl mx-auto leading-relaxed">
                    Solution innovante pour la collecte et la valorisation des déchets dans les quartiers enclavés
                </p>

                <!-- Boutons -->
                <div class="mt-8 flex flex-wrap justify-center gap-4">
                    <?php if(auth()->guard()->guest()): ?>
                        <a href="<?php echo e(route('register')); ?>"
                            class="inline-flex items-center gap-2 px-8 py-4 bg-white text-emerald-700 font-semibold rounded-xl hover:bg-emerald-50 transition-all duration-200 shadow-lg hover:shadow-xl hover:-translate-y-1">
                            <i class="fas fa-user-plus"></i> Créer un compte
                        </a>
                        <a href="<?php echo e(route('login')); ?>"
                            class="inline-flex items-center gap-2 px-8 py-4 bg-emerald-800 text-white font-semibold rounded-xl hover:bg-emerald-900 transition-all duration-200 shadow-lg hover:shadow-xl hover:-translate-y-1">
                            <i class="fas fa-sign-in-alt"></i> Se connecter
                        </a>
                    <?php else: ?>
                        <a href="<?php echo e(auth()->user()->isAdmin() ? route('admin.dashboard') : (auth()->user()->isCollecteur() ? route('collecteur.dashboard') : (auth()->user()->isPartenaire() ? route('partenaire.dashboard') : route('menage.dashboard')))); ?>"
                            class="inline-flex items-center gap-2 px-8 py-4 bg-white text-emerald-700 font-semibold rounded-xl hover:bg-emerald-50 transition-all duration-200 shadow-lg hover:shadow-xl hover:-translate-y-1">
                            <i class="fas fa-tachometer-alt"></i> Accéder à mon espace
                        </a>
                    <?php endif; ?>
                </div>

                <!-- Flèche vers le bas -->
                <div class="absolute bottom-8 left-1/2 -translate-x-1/2 animate-bounce">
                    <i class="fas fa-chevron-down text-2xl text-white/70"></i>
                </div>
            </div>
        </section>

        <!-- ============================================ -->
        <!-- SECTION 2 : STATISTIQUES                     -->
        <!-- ============================================ -->
        <section class="py-12 bg-white border-b border-gray-100">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-8">
                    <div class="text-center">
                        <p class="text-4xl font-bold text-emerald-600">+500</p>
                        <p class="text-gray-500 text-sm mt-1">Ménages inscrits</p>
                    </div>
                    <div class="text-center">
                        <p class="text-4xl font-bold text-emerald-600">+50</p>
                        <p class="text-gray-500 text-sm mt-1">Collecteurs actifs</p>
                    </div>
                    <div class="text-center">
                        <p class="text-4xl font-bold text-emerald-600">+1000</p>
                        <p class="text-gray-500 text-sm mt-1">Collectes réalisées</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- ============================================ -->
        <!-- SECTION 3 : SERVICES                         -->
        <!-- ============================================ -->
        <section class="py-16 bg-gray-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-12">
                    <h2 class="text-3xl md:text-4xl font-bold text-gray-800">
                        Une solution pour <span class="text-emerald-600">tous</span>
                    </h2>
                    <p class="text-gray-500 mt-2 max-w-2xl mx-auto">
                        Découvrez comment nous accompagnons chaque acteur de la chaîne de valorisation des déchets
                    </p>
                </div>

                <div class="grid md:grid-cols-3 gap-8">
                    <div
                        class="group bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 p-8 text-center hover:-translate-y-2 border-b-4 border-emerald-500">
                        <div
                            class="bg-emerald-100 w-20 h-20 rounded-2xl flex items-center justify-center mx-auto mb-4 group-hover:bg-emerald-500 transition-colors duration-300">
                            <i
                                class="fas fa-home text-3xl text-emerald-600 group-hover:text-white transition-colors duration-300"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-800 mb-3">Ménages</h3>
                        <p class="text-gray-500 leading-relaxed text-sm">Collecte à domicile, tri simplifié, points de
                            fidélité et récompenses.</p>
                        <a href="<?php echo e(route('register')); ?>"
                            class="mt-4 inline-block text-emerald-600 font-medium hover:text-emerald-700 hover:underline text-sm">Je
                            m'inscris →</a>
                    </div>

                    <div
                        class="group bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 p-8 text-center hover:-translate-y-2 border-b-4 border-blue-500">
                        <div
                            class="bg-blue-100 w-20 h-20 rounded-2xl flex items-center justify-center mx-auto mb-4 group-hover:bg-blue-500 transition-colors duration-300">
                            <i
                                class="fas fa-building text-3xl text-blue-600 group-hover:text-white transition-colors duration-300"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-800 mb-3">Entreprises</h3>
                        <p class="text-gray-500 leading-relaxed text-sm">Collecte professionnelle, volumes adaptés,
                            solutions sur mesure.</p>
                        <a href="<?php echo e(route('register.entreprise.form')); ?>"
                            class="mt-4 inline-block text-blue-600 font-medium hover:text-blue-700 hover:underline text-sm">Je
                            m'inscris →</a>
                    </div>

                    <div
                        class="group bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 p-8 text-center hover:-translate-y-2 border-b-4 border-purple-500">
                        <div
                            class="bg-purple-100 w-20 h-20 rounded-2xl flex items-center justify-center mx-auto mb-4 group-hover:bg-purple-500 transition-colors duration-300">
                            <i
                                class="fas fa-recycle text-3xl text-purple-600 group-hover:text-white transition-colors duration-300"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-800 mb-3">Économie circulaire</h3>
                        <p class="text-gray-500 leading-relaxed text-sm">Valorisation des déchets, récompenses, engagement
                            pour la planète.</p>
                        <a href="<?php echo e(route('register')); ?>"
                            class="mt-4 inline-block text-purple-600 font-medium hover:text-purple-700 hover:underline text-sm">Je
                            m'inscris →</a>
                    </div>
                </div>
            </div>
        </section>

        <!-- ============================================ -->
        <!-- SECTION 4 : OFFRE ESSAI                      -->
        <!-- ============================================ -->
        <section class="py-12 bg-gradient-to-r from-emerald-600 to-emerald-800">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-8 border border-white/20">
                    <div class="flex flex-col md:flex-row items-center justify-between gap-6">
                        <div class="flex items-center gap-4">
                            <span class="text-5xl"></span>
                            <div class="text-left text-white">
                                <h3 class="text-2xl font-bold">15 jours d'essai gratuit</h3>
                                <p class="text-emerald-100 text-sm">Kit offert • Collecte à domicile • 5000 FCFA/mois</p>
                            </div>
                        </div>
                        <a href="<?php echo e(route('register')); ?>"
                            class="inline-flex items-center gap-2 px-6 py-3 bg-white text-emerald-700 font-bold rounded-xl hover:bg-emerald-50 transition shadow-lg hover:shadow-xl">
                            <i class="fas fa-rocket"></i> Commencer
                        </a>
                    </div>
                </div>
            </div>
        </section>

        <!-- ============================================ -->
        <!-- SECTION 5 : COMMENT ÇA MARCHE               -->
        <!-- ============================================ -->
        <section class="py-16 bg-gray-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-12">
                    <h2 class="text-3xl md:text-4xl font-bold text-gray-800">
                        Comment ça <span class="text-emerald-600">marche</span> ?
                    </h2>
                    <p class="text-gray-500 mt-2">En 3 étapes simples, rejoignez la communauté du recyclage</p>
                </div>

                <div class="grid md:grid-cols-3 gap-8">
                    <div class="text-center">
                        <div class="relative inline-block">
                            <div
                                class="bg-emerald-100 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-4">
                                <span class="text-3xl">1️</span>
                            </div>
                            <div class="hidden md:block absolute top-10 left-full w-full h-0.5 bg-emerald-200"></div>
                        </div>
                        <h4 class="text-lg font-bold text-gray-800">Inscription</h4>
                        <p class="text-gray-500 text-sm mt-1">Créez votre compte en quelques minutes</p>
                    </div>

                    <div class="text-center">
                        <div class="relative inline-block">
                            <div
                                class="bg-emerald-100 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-4">
                                <span class="text-3xl">2️</span>
                            </div>
                            <div class="hidden md:block absolute top-10 left-full w-full h-0.5 bg-emerald-200"></div>
                        </div>
                        <h4 class="text-lg font-bold text-gray-800">Recevez votre kit</h4>
                        <p class="text-gray-500 text-sm mt-1">Un collecteur vous livre votre kit de tri</p>
                    </div>

                    <div class="text-center">
                        <div class="relative inline-block">
                            <div
                                class="bg-emerald-100 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-4">
                                <span class="text-3xl">3️</span>
                            </div>
                        </div>
                        <h4 class="text-lg font-bold text-gray-800">Collectez & gagnez</h4>
                        <p class="text-gray-500 text-sm mt-1">Triez vos déchets et accumulez des points</p>
                    </div>
                </div>
            </div>
        </section>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\gestion-dechets-dakar\resources\views/home.blade.php ENDPATH**/ ?>