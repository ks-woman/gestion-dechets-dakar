<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion Déchets Dakar</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">
    <nav class="bg-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between items-center h-16">
                <div class="text-xl font-bold">Gestion Déchets Dakar</div>

                <?php if(auth()->guard()->check()): ?>
                    <div class="flex items-center gap-6">
                        <!-- Menu selon le rôle -->
                        <?php if(auth()->user()->isAdmin()): ?>
                            <a href="<?php echo e(route('admin.dashboard')); ?>" class="text-gray-600 hover:text-gray-900">Admin</a>
                        <?php elseif(auth()->user()->isCollecteur()): ?>
                            <a href="<?php echo e(route('collecteur.dashboard')); ?>" class="text-gray-600 hover:text-gray-900">Mes
                                tournées</a>
                        <?php elseif(auth()->user()->isPartenaire()): ?>
                            <a href="<?php echo e(route('partenaire.dashboard')); ?>" class="text-gray-600 hover:text-gray-900">Espace
                                partenaire</a>
                        <?php else: ?>
                            <a href="<?php echo e(route('menage.dashboard')); ?>" class="text-gray-600 hover:text-gray-900">Mon
                                espace</a>
                        <?php endif; ?>

                        <span class="text-gray-600">Bonjour <?php echo e(auth()->user()->prenom); ?></span>

                        <form method="POST" action="<?php echo e(route('logout')); ?>" class="inline">
                            <?php echo csrf_field(); ?>
                            <button type="submit" class="text-red-500 hover:text-red-700">Déconnexion</button>
                        </form>
                    </div>
                <?php else: ?>
                    <div class="flex gap-4">
                        <a href="<?php echo e(route('login')); ?>" class="text-blue-500 hover:text-blue-700">Connexion</a>
                        <a href="<?php echo e(route('register')); ?>" class="text-green-500 hover:text-green-700">Inscription</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto px-4 py-8">
        <?php if(session('success')): ?>
            <div class="bg-green-500 text-white p-3 rounded mb-4"><?php echo e(session('success')); ?></div>
        <?php endif; ?>
        <?php if(session('error')): ?>
            <div class="bg-red-500 text-white p-3 rounded mb-4"><?php echo e(session('error')); ?></div>
        <?php endif; ?>

        <?php echo $__env->yieldContent('content'); ?>
    </main>
</body>

</html>
<?php /**PATH C:\wamp64\www\gestion-dechets-dakar\resources\views/layouts/app.blade.php ENDPATH**/ ?>