<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon espace - Gestion Déchets Dakar</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">
    <div class="flex h-screen">
        <!-- ============================================= -->
        <!-- SIDEBAR - Menu latéral gauche                 -->
        <!-- ============================================= -->
        <div class="w-72 bg-gradient-to-b from-emerald-700 to-emerald-900 text-white flex flex-col shadow-xl">
            <!-- Logo / En-tête -->
            <div class="p-5 border-b border-emerald-600">
                <h1 class="text-xl font-bold"> Gestion Déchets</h1>
                <p class="text-xs text-emerald-300 mt-1">Dakar, Sénégal</p>
            </div>

            <!-- Informations utilisateur -->
            <div class="p-4 mx-3 mt-4 bg-emerald-800/50 rounded-lg">
                <div class="flex items-center gap-3">
                    <div
                        class="w-10 h-10 bg-emerald-600 rounded-full flex items-center justify-center text-lg font-bold">
                        <?php echo e(substr(auth()->user()->prenom, 0, 1)); ?><?php echo e(substr(auth()->user()->nom, 0, 1)); ?>

                    </div>
                    <div>
                        <p class="font-semibold"><?php echo e(auth()->user()->prenom); ?> <?php echo e(auth()->user()->nom); ?></p>
                        <p class="text-xs text-emerald-300"><?php echo e(auth()->user()->email); ?></p>
                    </div>
                </div>
            </div>

            <!-- Navigation principale -->
            <nav class="flex-1 mt-6">
                <a href="<?php echo e(route('menage.dashboard')); ?>"
                    class="flex items-center px-5 py-3 text-emerald-100 hover:bg-emerald-800 transition
                    <?php echo e(request()->routeIs('menage.dashboard') ? 'bg-emerald-800 border-r-4 border-white' : ''); ?>">
                    <span class="text-xl mr-3"></span> Tableau de bord
                </a>

                <a href="<?php echo e(route('kit.demander')); ?>"
                    class="flex items-center px-5 py-3 text-emerald-100 hover:bg-emerald-800 transition
                    <?php echo e(request()->routeIs('kit*') ? 'bg-emerald-800 border-r-4 border-white' : ''); ?>">
                    <span class="text-xl mr-3"></span> Mon kit de tri
                </a>

                <a href="<?php echo e(route('collecte.demander')); ?>"
                    class="flex items-center px-5 py-3 text-emerald-100 hover:bg-emerald-800 transition
                    <?php echo e(request()->routeIs('collecte.demander') ? 'bg-emerald-800 border-r-4 border-white' : ''); ?>">
                    <span class="text-xl mr-3"></span> Demander une collecte
                </a>

                <a href="<?php echo e(route('collectes')); ?>"
                    class="flex items-center px-5 py-3 text-emerald-100 hover:bg-emerald-800 transition
                    <?php echo e(request()->routeIs('collectes') ? 'bg-emerald-800 border-r-4 border-white' : ''); ?>">
                    <span class="text-xl mr-3"></span> Mon historique
                </a>

                <a href="<?php echo e(route('statistiques')); ?>"
                    class="flex items-center px-5 py-3 text-emerald-100 hover:bg-emerald-800 transition
                    <?php echo e(request()->routeIs('statistiques') ? 'bg-emerald-800 border-r-4 border-white' : ''); ?>">
                    <span class="text-xl mr-3"></span> Mes statistiques
                </a>

                <!-- Mes préférences -->
                <a href="<?php echo e(route('menage.preferences')); ?>"
                    class="flex items-center px-5 py-3 text-emerald-100 hover:bg-emerald-800 transition
                    <?php echo e(request()->routeIs('menage.preferences') ? 'bg-emerald-800 border-r-4 border-white' : ''); ?>">
                    <span class="text-xl mr-3"></span> Mes préférences
                </a>

                <a href="<?php echo e(route('profil')); ?>"
                    class="flex items-center px-5 py-3 text-emerald-100 hover:bg-emerald-800 transition
                    <?php echo e(request()->routeIs('profil') ? 'bg-emerald-800 border-r-4 border-white' : ''); ?>">
                    <span class="text-xl mr-3"></span> Mon profil
                </a>

                <!-- ===== NOUVEAU LIEN MODE D'EMPLOI ===== -->
                <a href="<?php echo e(route('menage.mode-emploi')); ?>"
                    class="flex items-center px-5 py-3 text-emerald-100 hover:bg-emerald-800 transition
                    <?php echo e(request()->routeIs('menage.mode-emploi') ? 'bg-emerald-800 border-r-4 border-white' : ''); ?>">
                    <i class="fas fa-book mr-3"></i> Mode d'emploi
                </a>
            </nav>

            <!-- Pied de sidebar -->
            <div class="p-4 border-t border-emerald-600">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-2">
                        <span class="text-sm"> <?php echo e(auth()->user()->score_total); ?> pts</span>
                        <span class="text-xs px-2 py-1 bg-yellow-500 text-gray-900 rounded-full">
                            <?php echo e(auth()->user()->niveau); ?>

                        </span>
                    </div>
                </div>

                <form method="POST" action="<?php echo e(route('logout')); ?>">
                    <?php echo csrf_field(); ?>
                    <button type="submit"
                        class="flex items-center w-full px-3 py-2 text-emerald-200 hover:text-white hover:bg-emerald-800 rounded-lg transition">
                        <span class="text-xl mr-3"></span> Déconnexion
                    </button>
                </form>
            </div>
        </div>

        <!-- ============================================= -->
        <!-- CONTENU PRINCIPAL                             -->
        <!-- ============================================= -->
        <div class="flex-1 overflow-y-auto">
            <!-- Barre supérieure -->
            <div class="bg-white shadow-sm px-6 py-4 sticky top-0 z-10">
                <div class="flex justify-between items-center">
                    <h2 class="text-xl font-semibold text-gray-800">
                        <?php echo $__env->yieldContent('title', 'Mon espace'); ?>
                    </h2>
                    <div class="flex items-center gap-4">
                        <span class="text-sm text-gray-500"><?php echo e(now()->format('d/m/Y H:i')); ?></span>
                    </div>
                </div>
            </div>

            <!-- Messages de notification -->
            <div class="px-6 pt-6">
                <?php if(session('success')): ?>
                    <div class="bg-green-500 text-white p-3 rounded-lg mb-4 shadow">
                        <?php echo e(session('success')); ?>

                    </div>
                <?php endif; ?>
                <?php if(session('error')): ?>
                    <div class="bg-red-500 text-white p-3 rounded-lg mb-4 shadow">
                        <?php echo e(session('error')); ?>

                    </div>
                <?php endif; ?>
            </div>

            <!-- Contenu principal -->
            <div class="p-6">
                <?php echo $__env->yieldContent('content'); ?>
            </div>
        </div>
    </div>
</body>

</html>
<?php /**PATH C:\wamp64\www\gestion-dechets-dakar\resources\views/layouts/menage.blade.php ENDPATH**/ ?>