<?php $__env->startSection('title', 'Gestion Déchets Dakar'); ?>

<?php $__env->startSection('content'); ?>
    <!-- ============================================ -->
    <!-- HERO                                         -->
    <!-- ============================================ -->
    <div class="bg-gradient-to-r from-emerald-600 to-emerald-800 text-white py-12 sm:py-16 md:py-24 px-4 text-center">
        <div class="max-w-4xl mx-auto">
            <div class="text-5xl sm:text-6xl md:text-7xl mb-4"></div>
            <h1 class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-bold drop-shadow-lg tracking-tight">
                Gestion des Déchets à Dakar
            </h1>
            <p
                class="text-base sm:text-lg md:text-xl lg:text-2xl mt-3 sm:mt-4 max-w-2xl mx-auto leading-relaxed drop-shadow-md">
                Solution innovante pour la collecte et la valorisation des déchets dans les quartiers enclavés
            </p>
            <div class="mt-6 sm:mt-8 flex flex-col sm:flex-row justify-center gap-3 sm:gap-4">
                <a href="<?php echo e(route('register')); ?>"
                    class="bg-white text-emerald-700 hover:bg-emerald-50 font-semibold px-5 sm:px-6 py-2.5 sm:py-3 rounded-lg transition shadow-lg hover:shadow-xl text-sm sm:text-base">
                    <i class="fas fa-user-plus mr-2"></i> Créer un compte
                </a>
                <a href="<?php echo e(route('login')); ?>"
                    class="border-2 border-white text-white hover:bg-white/10 font-semibold px-5 sm:px-6 py-2.5 sm:py-3 rounded-lg transition text-sm sm:text-base">
                    <i class="fas fa-sign-in-alt mr-2"></i> Se connecter
                </a>
            </div>
        </div>
    </div>


    <!-- ============================================ -->
    <!-- STATISTIQUES (données réelles)               -->
    <!-- ============================================ -->
    <div class="bg-white py-12 sm:py-16 px-4">
        <div class="max-w-5xl mx-auto">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6 sm:gap-8 text-center">
                <!-- Ménages -->
                <div>
                    <div
                        class="flex items-center justify-center gap-1 text-3xl sm:text-4xl md:text-5xl font-bold text-emerald-600">
                        <span>+</span>
                        <span><?php echo e(number_format($menages)); ?></span>
                    </div>
                    <div class="flex items-center justify-center gap-1.5 mt-1.5">
                        <i class="fas fa-home text-emerald-400 text-sm"></i>
                        <p class="text-xs sm:text-sm text-gray-600 font-medium">Ménages inscrits</p>
                    </div>
                </div>

                <!-- Collecteurs -->
                <div>
                    <div
                        class="flex items-center justify-center gap-1 text-3xl sm:text-4xl md:text-5xl font-bold text-emerald-600">
                        <span>+</span>
                        <span><?php echo e(number_format($collecteurs)); ?></span>
                    </div>
                    <div class="flex items-center justify-center gap-1.5 mt-1.5">
                        <i class="fas fa-users text-emerald-400 text-sm"></i>
                        <p class="text-xs sm:text-sm text-gray-600 font-medium">Collecteurs actifs</p>
                    </div>
                </div>

                <!-- Collectes -->
                <div>
                    <div
                        class="flex items-center justify-center gap-1 text-3xl sm:text-4xl md:text-5xl font-bold text-emerald-600">
                        <span>+</span>
                        <span><?php echo e(number_format($collectes)); ?></span>
                    </div>
                    <div class="flex items-center justify-center gap-1.5 mt-1.5">
                        <i class="fas fa-truck text-emerald-400 text-sm"></i>
                        <p class="text-xs sm:text-sm text-gray-600 font-medium">Collectes réalisées</p>
                    </div>
                </div>

                <!-- Déchets valorisés -->
                <div>
                    <div
                        class="flex items-center justify-center gap-1 text-3xl sm:text-4xl md:text-5xl font-bold text-emerald-600">
                        <span>+</span>
                        <span><?php echo e($tonnes); ?> T</span>
                    </div>
                    <div class="flex items-center justify-center gap-1.5 mt-1.5">
                        <i class="fas fa-recycle text-emerald-400 text-sm"></i>
                        <p class="text-xs sm:text-sm text-gray-600 font-medium">Déchets valorisés</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ============================================ -->
    <!-- SERVICES : 3 CARTES                           -->
    <!-- ============================================ -->
    <div class="bg-gray-50 py-12 sm:py-16 px-4">
        <div class="max-w-6xl mx-auto">
            <h2 class="text-2xl sm:text-3xl font-bold text-center text-gray-800 mb-8 sm:mb-12">
                Une solution pour tous
            </h2>
            <p class="text-center text-gray-600 max-w-2xl mx-auto mb-10 text-sm sm:text-base">
                Découvrez comment nous accompagnons chaque acteur de la chaîne de valorisation des déchets
            </p>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 sm:gap-8">
                <!-- Ménages -->
                <div class="bg-white rounded-xl shadow-soft p-6 text-center hover:shadow-lg transition">
                    <div class="text-4xl mb-3"></div>
                    <h3 class="text-lg sm:text-xl font-bold text-gray-800">Ménages</h3>
                    <p class="text-gray-600 text-sm sm:text-base mt-2">Collecte à domicile, tri simplifié, points de
                        fidélité et récompenses.</p>
                    <a href="<?php echo e(route('register.menage.form')); ?>"
                        class="inline-block mt-4 text-emerald-600 hover:text-emerald-700 font-medium text-sm sm:text-base">
                        Je m'inscris →
                    </a>
                </div>
                <!-- Entreprises -->
                <div class="bg-white rounded-xl shadow-soft p-6 text-center hover:shadow-lg transition">
                    <div class="text-4xl mb-3"></div>
                    <h3 class="text-lg sm:text-xl font-bold text-gray-800">Entreprises</h3>
                    <p class="text-gray-600 text-sm sm:text-base mt-2">Collecte professionnelle, volumes adaptés, solutions
                        sur mesure.</p>
                    <a href="<?php echo e(route('register.entreprise.form')); ?>"
                        class="inline-block mt-4 text-emerald-600 hover:text-emerald-700 font-medium text-sm sm:text-base">
                        Je m'inscris →
                    </a>
                </div>
                <!-- Économie circulaire -->
                <div class="bg-white rounded-xl shadow-soft p-6 text-center hover:shadow-lg transition">
                    <div class="text-4xl mb-3"></div>
                    <h3 class="text-lg sm:text-xl font-bold text-gray-800">Économie circulaire</h3>
                    <p class="text-gray-600 text-sm sm:text-base mt-2">Valorisation des déchets, récompenses, engagement
                        pour la planète.</p>
                    <a href="<?php echo e(route('register')); ?>"
                        class="inline-block mt-4 text-emerald-600 hover:text-emerald-700 font-medium text-sm sm:text-base">
                        Je m'inscris →
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- ============================================ -->
    <!-- BANDEAU ESSAI GRATUIT                        -->
    <!-- ============================================ -->
    <div class="bg-gradient-to-r from-emerald-500 to-emerald-700 text-white py-10 sm:py-12 px-4 text-center">
        <div class="max-w-3xl mx-auto">
            <h2 class="text-2xl sm:text-3xl font-bold">15 jours d'essai gratuit</h2>
            <p class="text-base sm:text-lg mt-2 text-emerald-100">
                Kit offert • Collecte à domicile • <span class="font-semibold">5000 FCFA/mois</span>
            </p>
            <a href="<?php echo e(route('register')); ?>"
                class="inline-block mt-6 bg-white text-emerald-700 hover:bg-emerald-50 font-semibold px-6 sm:px-8 py-3 rounded-lg transition shadow-lg hover:shadow-xl text-sm sm:text-base">
                Commencer →
            </a>
        </div>
    </div>

    <!-- ============================================ -->
    <!-- COMMENT ÇA MARCHE ? (3 étapes)               -->
    <!-- ============================================ -->
    <div class="bg-white py-12 sm:py-16 px-4">
        <div class="max-w-5xl mx-auto">
            <h2 class="text-2xl sm:text-3xl font-bold text-center text-gray-800 mb-4">Comment ça marche ?</h2>
            <p class="text-center text-gray-600 mb-10 text-sm sm:text-base">En 3 étapes simples, rejoignez la communauté du
                recyclage</p>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 sm:gap-8">
                <div class="text-center">
                    <div
                        class="w-16 h-16 sm:w-20 sm:h-20 bg-emerald-100 rounded-full flex items-center justify-center text-2xl sm:text-3xl font-bold text-emerald-700 mx-auto mb-3">
                        1</div>
                    <h3 class="text-base sm:text-lg font-semibold text-gray-800">Inscription</h3>
                    <p class="text-sm sm:text-base text-gray-600 mt-1">Créez votre compte en quelques minutes</p>
                </div>
                <div class="text-center">
                    <div
                        class="w-16 h-16 sm:w-20 sm:h-20 bg-emerald-100 rounded-full flex items-center justify-center text-2xl sm:text-3xl font-bold text-emerald-700 mx-auto mb-3">
                        2</div>
                    <h3 class="text-base sm:text-lg font-semibold text-gray-800">Recevez votre kit</h3>
                    <p class="text-sm sm:text-base text-gray-600 mt-1">Un collecteur vous livre votre kit de tri</p>
                </div>
                <div class="text-center">
                    <div
                        class="w-16 h-16 sm:w-20 sm:h-20 bg-emerald-100 rounded-full flex items-center justify-center text-2xl sm:text-3xl font-bold text-emerald-700 mx-auto mb-3">
                        3</div>
                    <h3 class="text-base sm:text-lg font-semibold text-gray-800">Collectez & gagnez</h3>
                    <p class="text-sm sm:text-base text-gray-600 mt-1">Triez vos déchets et accumulez des points</p>
                </div>
            </div>
        </div>
    </div>

    <!-- ============================================ -->
    <!-- FOOTER (intégré directement)                 -->
    <!-- ============================================ -->
    <footer class="bg-gradient-to-b from-emerald-900 to-emerald-950 text-white mt-8">
        <div class="container mx-auto px-4 py-10 sm:py-12">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                <!-- À propos -->
                <div>
                    <div class="flex items-center gap-2 mb-4">
                        <span class="text-3xl"></span>
                        <span class="text-xl sm:text-2xl font-bold">Gestion Déchets</span>
                    </div>
                    <p class="text-emerald-200 text-sm leading-relaxed">
                        Solution innovante pour la collecte et la valorisation des déchets à Dakar.
                    </p>
                    <div class="flex gap-4 mt-4">
                        <a href="#" class="text-emerald-300 hover:text-white transition-colors text-lg sm:text-xl"><i
                                class="fab fa-facebook"></i></a>
                        <a href="#" class="text-emerald-300 hover:text-white transition-colors text-lg sm:text-xl"><i
                                class="fab fa-twitter"></i></a>
                        <a href="#" class="text-emerald-300 hover:text-white transition-colors text-lg sm:text-xl"><i
                                class="fab fa-instagram"></i></a>
                        <a href="#" class="text-emerald-300 hover:text-white transition-colors text-lg sm:text-xl"><i
                                class="fab fa-linkedin"></i></a>
                    </div>
                </div>
                <!-- Liens -->
                <div>
                    <h3 class="text-base sm:text-lg font-semibold mb-4 text-white">Liens utiles</h3>
                    <ul class="space-y-2 text-sm">
                        <li><a href="<?php echo e(route('home')); ?>"
                                class="text-emerald-300 hover:text-white transition-colors">Accueil</a></li>
                        <li><a href="<?php echo e(route('login')); ?>"
                                class="text-emerald-300 hover:text-white transition-colors">Connexion</a></li>
                        <li><a href="<?php echo e(route('register')); ?>"
                                class="text-emerald-300 hover:text-white transition-colors">Inscription</a></li>
                        <li><a href="<?php echo e(route('about')); ?>" class="text-emerald-300 hover:text-white transition-colors">À
                                propos</a></li>
                        <li><a href="#" class="text-emerald-300 hover:text-white transition-colors">Contact</a></li>
                    </ul>
                </div>
                <!-- Contact -->
                <div>
                    <h3 class="text-base sm:text-lg font-semibold mb-4 text-white">Contact</h3>
                    <ul class="space-y-2 text-sm text-emerald-200">
                        <li class="flex items-start gap-2"><i
                                class="fas fa-map-marker-alt mt-1 text-emerald-400"></i><span>Dakar, Sénégal</span></li>
                        <li class="flex items-start gap-2"><i class="fas fa-phone mt-1 text-emerald-400"></i><span>+221 77
                                123 45 67</span></li>
                        <li class="flex items-start gap-2"><i
                                class="fas fa-envelope mt-1 text-emerald-400"></i><span>contact@gestiondechets.sn</span>
                        </li>
                        <li class="flex items-start gap-2"><i
                                class="fas fa-clock mt-1 text-emerald-400"></i><span>Lun-Ven: 8h - 18h</span></li>
                    </ul>
                </div>
                <!-- Newsletter -->
                <div>
                    <h3 class="text-base sm:text-lg font-semibold mb-4 text-white">Restez informé</h3>
                    <p class="text-sm text-emerald-200 mb-3">Recevez nos actualités et offres spéciales.</p>
                    <form class="flex flex-col gap-2">
                        <input type="email" placeholder="Votre email"
                            class="w-full px-4 py-2 rounded-lg bg-emerald-800/50 border border-emerald-700 text-white placeholder-emerald-300 focus:outline-none focus:ring-2 focus:ring-emerald-500 text-sm">
                        <button type="submit" class="btn-primary text-sm py-2 w-full">
                            <i class="fas fa-paper-plane mr-2"></i> S'abonner
                        </button>
                    </form>
                    <div class="mt-4 text-xs text-emerald-300 flex items-center gap-2">
                        <i class="fas fa-shield-alt"></i>
                        <span>Vos données sont sécurisées</span>
                    </div>
                </div>
            </div>
            <!-- Séparateur et copyright -->
            <div class="border-t border-emerald-800 mt-8 pt-6">
                <div class="flex flex-col sm:flex-row justify-between items-center text-sm text-emerald-300">
                    <p>&copy; <?php echo e(date('Y')); ?> Gestion Déchets Dakar. Tous droits réservés.</p>
                    <div class="flex gap-4 mt-2 sm:mt-0">
                        <a href="#" class="hover:text-white transition-colors">Mentions légales</a>
                        <a href="#" class="hover:text-white transition-colors">Politique de confidentialité</a>
                        <a href="#" class="hover:text-white transition-colors">CGU</a>
                    </div>
                </div>
            </div>
        </div>
    </footer>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\gestion-dechets-dakar\resources\views/home.blade.php ENDPATH**/ ?>