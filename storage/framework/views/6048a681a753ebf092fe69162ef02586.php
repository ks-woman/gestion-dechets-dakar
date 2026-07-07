<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Collecteur - Gestion Déchets Dakar</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">
    <div class="flex h-screen">
        <!-- Sidebar -->
        <div class="w-72 bg-gradient-to-b from-blue-700 to-blue-900 text-white flex flex-col">
            <div class="p-5 border-b border-blue-600">
                <h1 class="text-xl font-bold"> Gestion Déchets</h1>
                <p class="text-xs text-blue-300 mt-1">Espace Collecteur</p>
            </div>

            <div class="p-4 mx-3 mt-4 bg-blue-800/50 rounded-lg">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-blue-600 rounded-full flex items-center justify-center">
                        <?php echo e(substr(auth()->user()->prenom, 0, 1)); ?><?php echo e(substr(auth()->user()->nom, 0, 1)); ?>

                    </div>
                    <div>
                        <p class="font-semibold"><?php echo e(auth()->user()->prenom); ?> <?php echo e(auth()->user()->nom); ?></p>
                        <p class="text-xs text-blue-300"><?php echo e(auth()->user()->email); ?></p>
                    </div>
                </div>
            </div>

            <nav class="flex-1 mt-6">
                <a href="<?php echo e(route('collecteur.dashboard')); ?>"
                    class="flex items-center px-5 py-3 text-blue-100 hover:bg-blue-800 transition
                    <?php echo e(request()->routeIs('collecteur.dashboard') ? 'bg-blue-800 border-r-4 border-white' : ''); ?>">
                    <i class="fas fa-tachometer-alt mr-3"></i> Tableau de bord
                </a>

                <a href="<?php echo e(route('collecteur.tournee')); ?>"
                    class="flex items-center px-5 py-3 text-blue-100 hover:bg-blue-800 transition
                    <?php echo e(request()->routeIs('collecteur.tournee') ? 'bg-blue-800 border-r-4 border-white' : ''); ?>">
                    <i class="fas fa-map-marker-alt mr-3"></i> Ma tournée
                </a>

                <a href="<?php echo e(route('collecteur.activer-kit')); ?>"
                    class="flex items-center px-5 py-3 text-blue-100 hover:bg-blue-800 transition
                    <?php echo e(request()->routeIs('collecteur.activer-kit') ? 'bg-blue-800 border-r-4 border-white' : ''); ?>">
                    <i class="fas fa-qrcode mr-3"></i> Activer un kit
                </a>

                <a href="<?php echo e(route('collecteur.enregistrer-collecte')); ?>"
                    class="flex items-center px-5 py-3 text-blue-100 hover:bg-blue-800 transition
                    <?php echo e(request()->routeIs('collecteur.enregistrer-collecte') ? 'bg-blue-800 border-r-4 border-white' : ''); ?>">
                    <i class="fas fa-clipboard-list mr-3"></i> Enregistrer collecte
                </a>

                <a href="<?php echo e(route('collecteur.historique')); ?>"
                    class="flex items-center px-5 py-3 text-blue-100 hover:bg-blue-800 transition
                    <?php echo e(request()->routeIs('collecteur.historique') ? 'bg-blue-800 border-r-4 border-white' : ''); ?>">
                    <i class="fas fa-history mr-3"></i> Historique
                </a>

                <a href="<?php echo e(route('collecteur.statistiques')); ?>"
                    class="flex items-center px-5 py-3 text-blue-100 hover:bg-blue-800 transition
                    <?php echo e(request()->routeIs('collecteur.statistiques') ? 'bg-blue-800 border-r-4 border-white' : ''); ?>">
                    <i class="fas fa-chart-bar mr-3"></i> Statistiques
                </a>



                <!-- ===== NOUVEAU LIEN MES PRIMES ===== -->
                <a href="<?php echo e(route('collecteur.primes')); ?>"
                    class="flex items-center px-5 py-3 text-blue-100 hover:bg-blue-800 transition
                    <?php echo e(request()->routeIs('collecteur.primes') ? 'bg-blue-800 border-r-4 border-white' : ''); ?>">
                    <i class="fas fa-coins mr-3"></i> Mes primes
                </a>
            </nav>

            <div class="p-4 border-t border-blue-600">
                <form method="POST" action="<?php echo e(route('logout')); ?>">
                    <?php echo csrf_field(); ?>
                    <button type="submit"
                        class="flex items-center w-full px-3 py-2 text-blue-200 hover:text-white hover:bg-blue-800 rounded-lg transition">
                        <i class="fas fa-sign-out-alt mr-3"></i> Déconnexion
                    </button>
                </form>
            </div>
        </div>

        <!-- Contenu principal -->
        <div class="flex-1 overflow-y-auto">
            <div class="bg-white shadow-sm px-6 py-4 sticky top-0 z-10">
                <div class="flex justify-between items-center">
                    <h2 class="text-xl font-semibold text-gray-800"><?php echo $__env->yieldContent('title', 'Espace Collecteur'); ?></h2>
                    <span class="text-sm text-gray-500"><?php echo e(now()->format('d/m/Y H:i')); ?></span>
                </div>
            </div>

            <div class="p-6">
                <?php if(session('success')): ?>
                    <div class="bg-green-500 text-white p-3 rounded mb-4"><?php echo e(session('success')); ?></div>
                <?php endif; ?>
                <?php if(session('error')): ?>
                    <div class="bg-red-500 text-white p-3 rounded mb-4"><?php echo e(session('error')); ?></div>
                <?php endif; ?>
                <?php echo $__env->yieldContent('content'); ?>
            </div>
        </div>
    </div>
</body>

</html>
<?php /**PATH C:\wamp64\www\gestion-dechets-dakar\resources\views/layouts/collecteur.blade.php ENDPATH**/ ?>