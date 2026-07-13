<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon espace - Gestion Déchets Dakar</title>
    <?php echo app('Illuminate\Foundation\Vite')('resources/css/app.css'); ?>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>

<body class="bg-gray-100">
    <div class="flex h-screen overflow-hidden">
        <!-- ============================================= -->
        <!-- SIDEBAR - Menu latéral gauche                 -->
        <!-- ============================================= -->
        <div class="w-72 bg-gradient-to-b from-emerald-800 to-emerald-950 text-white flex flex-col shadow-2xl h-screen">
            <!-- Logo / En-tête (fixe) -->
            <div class="p-5 border-b border-emerald-700 flex-shrink-0">
                <div class="flex items-center gap-2">
                    <div class="text-2xl"></div>
                    <div>
                        <h1 class="text-xl font-bold text-white">Gestion Déchets</h1>
                        <p class="text-xs text-white/70">Dakar, Sénégal</p>
                    </div>
                </div>
            </div>

            <!-- Informations utilisateur (fixe) -->
            <div class="p-4 mx-3 mt-4 bg-emerald-700/50 rounded-lg flex-shrink-0">
                <div class="flex items-center gap-3">
                    <div
                        class="w-10 h-10 bg-emerald-600 rounded-full flex items-center justify-center text-lg font-bold text-white">
                        <?php echo e(substr(auth()->user()->prenom, 0, 1)); ?><?php echo e(substr(auth()->user()->nom, 0, 1)); ?>

                    </div>
                    <div>
                        <p class="font-semibold text-white"><?php echo e(auth()->user()->prenom); ?> <?php echo e(auth()->user()->nom); ?></p>
                        <p class="text-xs text-white/70"><?php echo e(auth()->user()->email); ?></p>
                    </div>
                </div>
            </div>

            <!-- Navigation (scrollable) -->
            <nav class="flex-1 overflow-y-auto mt-4 px-2">
                <a href="<?php echo e(route('menage.dashboard')); ?>"
                    class="flex items-center px-4 py-2.5 text-white hover:bg-emerald-700 rounded-lg transition
                    <?php echo e(request()->routeIs('menage.dashboard') ? 'bg-emerald-700' : ''); ?>">
                    <i class="fas fa-tachometer-alt w-5 mr-3"></i> Tableau de bord
                </a>
                <a href="<?php echo e(route('kit.demander')); ?>"
                    class="flex items-center px-4 py-2.5 text-white hover:bg-emerald-700 rounded-lg transition
                    <?php echo e(request()->routeIs('kit*') ? 'bg-emerald-700' : ''); ?>">
                    <i class="fas fa-box w-5 mr-3"></i> Mon kit de tri
                </a>
                <a href="<?php echo e(route('collecte.demander')); ?>"
                    class="flex items-center px-4 py-2.5 text-white hover:bg-emerald-700 rounded-lg transition
                    <?php echo e(request()->routeIs('collecte.demander') ? 'bg-emerald-700' : ''); ?>">
                    <i class="fas fa-truck w-5 mr-3"></i> Demander une collecte
                </a>
                <a href="<?php echo e(route('collectes')); ?>"
                    class="flex items-center px-4 py-2.5 text-white hover:bg-emerald-700 rounded-lg transition
                    <?php echo e(request()->routeIs('collectes') ? 'bg-emerald-700' : ''); ?>">
                    <i class="fas fa-history w-5 mr-3"></i> Mon historique
                </a>
                <a href="<?php echo e(route('statistiques')); ?>"
                    class="flex items-center px-4 py-2.5 text-white hover:bg-emerald-700 rounded-lg transition
                    <?php echo e(request()->routeIs('statistiques') ? 'bg-emerald-700' : ''); ?>">
                    <i class="fas fa-chart-bar w-5 mr-3"></i> Mes statistiques
                </a>
                <a href="<?php echo e(route('menage.preferences')); ?>"
                    class="flex items-center px-4 py-2.5 text-white hover:bg-emerald-700 rounded-lg transition
                    <?php echo e(request()->routeIs('menage.preferences') ? 'bg-emerald-700' : ''); ?>">
                    <i class="fas fa-sliders-h w-5 mr-3"></i> Mes préférences
                </a>
                <a href="<?php echo e(route('profil')); ?>"
                    class="flex items-center px-4 py-2.5 text-white hover:bg-emerald-700 rounded-lg transition
                    <?php echo e(request()->routeIs('profil') ? 'bg-emerald-700' : ''); ?>">
                    <i class="fas fa-user w-5 mr-3"></i> Mon profil
                </a>
                <a href="<?php echo e(route('menage.mode-emploi')); ?>"
                    class="flex items-center px-4 py-2.5 text-white hover:bg-emerald-700 rounded-lg transition
                    <?php echo e(request()->routeIs('menage.mode-emploi') ? 'bg-emerald-700' : ''); ?>">
                    <i class="fas fa-book w-5 mr-3"></i> Mode d'emploi
                </a>
                <a href="<?php echo e(route('recompenses.catalogue')); ?>"
                    class="flex items-center px-4 py-2.5 text-white hover:bg-emerald-700 rounded-lg transition
                    <?php echo e(request()->routeIs('recompenses*') ? 'bg-emerald-700' : ''); ?>">
                    <i class="fas fa-gift w-5 mr-3"></i> Récompenses
                </a>

                <a href="<?php echo e(route('abonnement.index')); ?>"
                    class="flex items-center px-4 py-2.5 text-white hover:bg-emerald-700 rounded-lg transition
    <?php echo e(request()->routeIs('abonnement*') ? 'bg-emerald-700' : ''); ?>">
                    <i class="fas fa-credit-card w-5 mr-3"></i> Mon abonnement
                </a>

            </nav>

            <!-- Pied de sidebar (collé en bas) -->
            <div class="p-4 border-t border-emerald-700 mt-auto flex-shrink-0">
                <div class="flex items-center justify-between mb-4">
                    <span class="text-sm text-white/80"><?php echo e(auth()->user()->score_total); ?> pts</span>
                    <span class="badge-warning text-xs"><?php echo e(auth()->user()->niveau); ?></span>
                </div>

                <form method="POST" action="<?php echo e(route('logout')); ?>">
                    <?php echo csrf_field(); ?>
                    <button type="submit"
                        class="flex items-center w-full px-3 py-2 text-white/70 hover:text-white hover:bg-emerald-700 rounded-lg transition">
                        <i class="fas fa-sign-out-alt w-5 mr-3"></i> Déconnexion
                    </button>
                </form>
            </div>
        </div>

        <!-- ============================================= -->
        <!-- CONTENU PRINCIPAL                             -->
        <!-- ============================================= -->
        <div class="flex-1 overflow-y-auto">
            <!-- Header -->
            <div class="header-primary">
                <div class="flex justify-between items-center">
                    <h2 class="text-xl font-semibold text-white"><?php echo $__env->yieldContent('title', 'Mon espace'); ?></h2>
                    <span class="text-sm text-white/80"><?php echo e(now()->format('d/m/Y H:i')); ?></span>
                </div>
            </div>

            <div class="p-6">
                <?php if(session('success')): ?>
                    <div class="alert-success"><?php echo e(session('success')); ?></div>
                <?php endif; ?>
                <?php if(session('error')): ?>
                    <div class="alert-danger"><?php echo e(session('error')); ?></div>
                <?php endif; ?>
                <?php echo $__env->yieldContent('content'); ?>
            </div>
        </div>
    </div>
</body>

</html>
<?php /**PATH C:\wamp64\www\gestion-dechets-dakar\resources\views/layouts/menage.blade.php ENDPATH**/ ?>