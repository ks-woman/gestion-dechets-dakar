<?php $__env->startSection('title', 'Tableau de bord'); ?>

<?php $__env->startSection('content'); ?>
    <div class="space-y-6">
        <!-- Bannière -->
        <div class="bg-gradient-to-r from-emerald-600 to-emerald-800 rounded-xl shadow-soft p-6 text-white">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-bold">Bonjour <?php echo e(auth()->user()->prenom); ?> !</h1>
                    <p class="text-emerald-100 mt-1">Tableau de bord collecteur</p>
                </div>
                <div class="text-5xl"></div>
            </div>
        </div>

        <!-- Cartes -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="card">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-gray-500 text-sm">Collectes aujourd'hui</p>
                        <p class="text-2xl font-bold text-emerald-600"><?php echo e($collectesAujourdhui ?? 0); ?></p>
                    </div>
                    <i class="fas fa-calendar-day text-3xl text-emerald-500"></i>
                </div>
            </div>
            <div class="card">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-gray-500 text-sm">Collectes ce mois</p>
                        <p class="text-2xl font-bold text-emerald-600"><?php echo e($collectesMois ?? 0); ?></p>
                    </div>
                    <i class="fas fa-calendar-alt text-3xl text-emerald-500"></i>
                </div>
            </div>
            <div class="card">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-gray-500 text-sm">Kits à livrer</p>
                        <p class="text-2xl font-bold text-emerald-600"><?php echo e($kitsALivrer->count() ?? 0); ?></p>
                    </div>
                    <i class="fas fa-box text-3xl text-emerald-500"></i>
                </div>
            </div>
            <div class="card">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-gray-500 text-sm">Notifications</p>
                        <p class="text-2xl font-bold text-emerald-600"><?php echo e($nonLues ?? 0); ?></p>
                    </div>
                    <i class="fas fa-bell text-3xl text-emerald-500"></i>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
            <a href="<?php echo e(route('collecteur.tournee')); ?>" class="btn-primary text-center"><i
                    class="fas fa-map-marked-alt mr-2"></i> Ma tournée</a>
            <a href="<?php echo e(route('collecteur.scanner')); ?>" class="btn-primary text-center"><i class="fas fa-qrcode mr-2"></i>
                Scanner kit</a>
            <a href="<?php echo e(route('collecteur.activer-kit')); ?>" class="btn-primary text-center"><i class="fas fa-box mr-2"></i>
                Activer kit</a>
            <a href="<?php echo e(route('collecteur.enregistrer-collecte')); ?>" class="btn-primary text-center"><i
                    class="fas fa-clipboard-list mr-2"></i> Enregistrer</a>
        </div>

        <!-- Kits à livrer -->
        <div class="bg-white rounded-xl shadow-soft p-6">
            <h2 class="text-lg font-semibold text-gray-800 flex items-center gap-2 mb-4">
                <i class="fas fa-box text-emerald-500"></i> Kits à livrer
            </h2>
            <?php if(isset($kitsALivrer) && $kitsALivrer->count() > 0): ?>
                <div class="space-y-3">
                    <?php $__currentLoopData = $kitsALivrer; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $kit): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                            <div>
                                <p class="font-medium"><?php echo e($kit->user->prenom); ?> <?php echo e($kit->user->nom); ?></p>
                                <p class="text-sm text-gray-500"><?php echo e($kit->user->adresse); ?></p>
                                <p class="text-xs text-gray-400">Type: <?php echo e($kit->type_kit); ?></p>
                            </div>
                            <a href="<?php echo e(route('collecteur.scanner')); ?>" class="btn-primary text-sm"><i
                                    class="fas fa-qrcode mr-1"></i> Scanner</a>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            <?php else: ?>
                <div class="text-center py-8 text-gray-500"><i class="fas fa-box-open text-4xl mb-2 block"></i>
                    <p>Aucun kit à livrer</p>
                </div>
            <?php endif; ?>
        </div>

        <!-- Notifications -->
        <div class="bg-white rounded-xl shadow-soft p-6">
            <h2 class="text-lg font-semibold text-gray-800 flex items-center gap-2 mb-4">
                <i class="fas fa-bell text-emerald-500"></i> Notifications
            </h2>
            <?php if(isset($notifications) && $notifications->count() > 0): ?>
                <div class="space-y-2">
                    <?php $__currentLoopData = $notifications->take(5); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $notification): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div
                            class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg <?php echo e($notification->est_lu ? '' : 'border-l-4 border-emerald-500'); ?>">
                            <div class="flex-1">
                                <p class="text-sm font-medium"><?php echo e($notification->titre); ?></p>
                                <p class="text-xs text-gray-500"><?php echo e($notification->message); ?></p>
                                <p class="text-xs text-gray-400"><?php echo e($notification->created_at->diffForHumans()); ?></p>
                            </div>
                            <?php if(!$notification->est_lu): ?>
                                <span class="badge-success text-xs">Nouveau</span>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            <?php else: ?>
                <div class="text-center py-4 text-gray-500">
                    <p>Aucune notification</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.collecteur', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\gestion-dechets-dakar\resources\views/collecteur/dashboard.blade.php ENDPATH**/ ?>