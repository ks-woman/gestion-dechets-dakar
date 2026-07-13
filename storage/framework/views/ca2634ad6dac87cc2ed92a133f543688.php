<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Gestion Déchets Dakar</title>
    <?php echo app('Illuminate\Foundation\Vite')('resources/css/app.css'); ?>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>

<body class="bg-gray-100">
    <div class="flex h-screen">
        <!-- Sidebar -->
        <div class="w-72 bg-gradient-to-b from-emerald-800 to-emerald-950 text-white flex flex-col shadow-2xl">
            <div class="p-5 border-b border-emerald-700">
                <div class="flex items-center gap-2">
                    <div class="text-2xl"></div>
                    <div>
                        <h1 class="text-xl font-bold text-white">Gestion Déchets</h1>
                        <p class="text-xs text-white/70">Administration</p>
                    </div>
                </div>
            </div>

            <div class="p-4 mx-3 mt-4 bg-emerald-700/50 rounded-lg">
                <div class="flex items-center gap-3">
                    <div
                        class="w-10 h-10 bg-emerald-600 rounded-full flex items-center justify-center text-lg font-bold text-white">
                        <?php echo e(substr(auth()->user()->prenom, 0, 1)); ?><?php echo e(substr(auth()->user()->nom, 0, 1)); ?>

                    </div>
                    <div>
                        <p class="font-semibold text-white"><?php echo e(auth()->user()->prenom); ?> <?php echo e(auth()->user()->nom); ?></p>
                        <p class="text-xs text-white/70">Administrateur</p>
                    </div>
                </div>
            </div>

            <nav class="flex-1 mt-6">
                <a href="<?php echo e(route('admin.dashboard')); ?>"
                    class="flex items-center px-5 py-3 text-white hover:bg-emerald-700 transition
                    <?php echo e(request()->routeIs('admin.dashboard') ? 'sidebar-link-active' : ''); ?>">
                    <i class="fas fa-tachometer-alt w-5 mr-3"></i> Tableau de bord
                </a>
                <a href="<?php echo e(route('admin.utilisateurs')); ?>"
                    class="flex items-center px-5 py-3 text-white hover:bg-emerald-700 transition
                    <?php echo e(request()->routeIs('admin.utilisateurs*') ? 'sidebar-link-active' : ''); ?>">
                    <i class="fas fa-users w-5 mr-3"></i> Utilisateurs
                </a>
                <a href="<?php echo e(route('admin.collectes')); ?>"
                    class="flex items-center px-5 py-3 text-white hover:bg-emerald-700 transition
                    <?php echo e(request()->routeIs('admin.collectes*') ? 'sidebar-link-active' : ''); ?>">
                    <i class="fas fa-truck w-5 mr-3"></i> Collectes
                </a>
                <a href="<?php echo e(route('admin.kits')); ?>"
                    class="flex items-center px-5 py-3 text-white hover:bg-emerald-700 transition
                    <?php echo e(request()->routeIs('admin.kits*') ? 'sidebar-link-active' : ''); ?>">
                    <i class="fas fa-box w-5 mr-3"></i> Kits commandés
                </a>
                <a href="<?php echo e(route('admin.statistiques')); ?>"
                    class="flex items-center px-5 py-3 text-white hover:bg-emerald-700 transition
                    <?php echo e(request()->routeIs('admin.statistiques*') ? 'sidebar-link-active' : ''); ?>">
                    <i class="fas fa-chart-line w-5 mr-3"></i> Statistiques
                </a>
                <a href="<?php echo e(route('admin.recompenses.index')); ?>"
                    class="flex items-center px-5 py-3 text-white hover:bg-emerald-700 transition
                    <?php echo e(request()->routeIs('admin.recompenses*') ? 'sidebar-link-active' : ''); ?>">
                    <i class="fas fa-gift w-5 mr-3"></i> Récompenses
                </a>
                <a href="<?php echo e(route('admin.zones.index')); ?>"
                    class="flex items-center px-5 py-3 text-white hover:bg-emerald-700 transition
                    <?php echo e(request()->routeIs('admin.zones*') ? 'sidebar-link-active' : ''); ?>">
                    <i class="fas fa-map-marked-alt w-5 mr-3"></i> Zones
                </a>

                <a href="<?php echo e(route('admin.reclamations.index')); ?>"
                    class="flex items-center px-5 py-3 text-white hover:bg-emerald-700 transition
   <?php echo e(request()->routeIs('admin.reclamations*') ? 'bg-emerald-700' : ''); ?>">
                    <i class="fas fa-exclamation-triangle w-5 mr-3"></i> Réclamations
                </a>

                <!-- Stocks -->
                <a href="<?php echo e(route('admin.stocks.index')); ?>"
                    class="flex items-center px-5 py-3 text-white hover:bg-emerald-700 transition
   <?php echo e(request()->routeIs('admin.stocks*') ? 'bg-emerald-700' : ''); ?>">
                    <i class="fas fa-warehouse w-5 mr-3"></i> Stocks
                </a>

                <!-- Commandes -->
                <a href="<?php echo e(route('admin.commandes.index')); ?>"
                    class="flex items-center px-5 py-3 text-white hover:bg-emerald-700 transition
   <?php echo e(request()->routeIs('admin.commandes*') ? 'bg-emerald-700' : ''); ?>">
                    <i class="fas fa-shopping-cart w-5 mr-3"></i> Commandes
                </a>

                <a href="<?php echo e(route('admin.collecteurs')); ?>"
                    class="flex items-center px-5 py-3 text-white hover:bg-emerald-700 transition
    <?php echo e(request()->routeIs('admin.collecteurs*') ? 'bg-emerald-700' : ''); ?>">
                    <i class="fas fa-users w-5 mr-3"></i> Collecteurs
                </a>

                <a href="<?php echo e(route('admin.abonnements.index')); ?>"
                    class="flex items-center px-5 py-3 text-white hover:bg-emerald-700 transition
    <?php echo e(request()->routeIs('admin.abonnements*') ? 'bg-emerald-700' : ''); ?>">
                    <i class="fas fa-credit-card w-5 mr-3"></i> Abonnements
                </a>
            </nav>

            <div class="p-4 border-t border-emerald-700">
                <form method="POST" action="<?php echo e(route('logout')); ?>">
                    <?php echo csrf_field(); ?>
                    <button type="submit"
                        class="flex items-center w-full px-3 py-2 text-white/70 hover:text-white hover:bg-emerald-700 rounded-lg transition">
                        <i class="fas fa-sign-out-alt w-5 mr-3"></i> Déconnexion
                    </button>
                </form>
            </div>
        </div>

        <!-- Contenu principal -->
        <div class="flex-1 overflow-y-auto">
            <div class="header-primary">
                <div class="flex justify-between items-center">
                    <h2 class="text-xl font-semibold text-white"><?php echo $__env->yieldContent('title', 'Administration'); ?></h2>
                    <span class="text-sm text-white/80"><i class="far fa-calendar-alt mr-1"></i>
                        <?php echo e(now()->format('d/m/Y H:i')); ?></span>
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
<?php /**PATH C:\wamp64\www\gestion-dechets-dakar\resources\views/layouts/admin.blade.php ENDPATH**/ ?>