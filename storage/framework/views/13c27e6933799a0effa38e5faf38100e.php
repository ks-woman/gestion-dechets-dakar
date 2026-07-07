<?php $__env->startSection('title', 'Tableau de bord'); ?>

<?php $__env->startSection('content'); ?>
    <div class="space-y-6">
        <!-- En-tête -->
        <div class="bg-gradient-to-r from-blue-600 to-blue-800 rounded-xl shadow-lg p-6 text-white">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-bold">Bonjour <?php echo e(auth()->user()->prenom); ?> !</h1>
                    <p class="text-blue-100 mt-1">Tableau de bord collecteur</p>
                </div>
                <div class="text-5xl"></div>
            </div>
        </div>

        <!-- Cartes statistiques -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="bg-white rounded-xl shadow p-4 border-l-4 border-blue-500">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-gray-500 text-sm">Collectes aujourd'hui</p>
                        <p class="text-2xl font-bold"><?php echo e($collectesAujourdhui ?? 0); ?></p>
                    </div>
                    <i class="fas fa-calendar-day text-2xl text-blue-500"></i>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow p-4 border-l-4 border-green-500">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-gray-500 text-sm">Collectes ce mois</p>
                        <p class="text-2xl font-bold"><?php echo e($collectesMois ?? 0); ?></p>
                    </div>
                    <i class="fas fa-calendar-alt text-2xl text-green-500"></i>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow p-4 border-l-4 border-yellow-500">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-gray-500 text-sm">Kits à livrer</p>
                        <p class="text-2xl font-bold"><?php echo e($kitsALivrer->count() ?? 0); ?></p>
                    </div>
                    <i class="fas fa-box text-2xl text-yellow-500"></i>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow p-4 border-l-4 border-purple-500">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-gray-500 text-sm">Notifications</p>
                        <p class="text-2xl font-bold"><?php echo e($nonLues ?? 0); ?></p>
                    </div>
                    <i class="fas fa-bell text-2xl text-purple-500"></i>
                </div>
            </div>
        </div>

        <!-- Actions rapides -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
            <a href="<?php echo e(route('collecteur.tournee')); ?>"
                class="bg-blue-500 hover:bg-blue-600 text-white p-3 rounded-lg text-center transition">
                <i class="fas fa-map-marker-alt mr-2"></i> Ma tournée
            </a>
            <a href="<?php echo e(route('collecteur.scanner')); ?>"
                class="bg-emerald-500 hover:bg-emerald-600 text-white p-3 rounded-lg text-center transition">
                <i class="fas fa-qrcode mr-2"></i> Scanner kit
            </a>
        </div>

        <!-- Kits à livrer -->
        <div class="bg-white rounded-xl shadow">
            <div class="p-5 border-b">
                <h2 class="text-lg font-semibold flex items-center gap-2">
                    <i class="fas fa-box text-emerald-500"></i> Kits à livrer
                </h2>
            </div>
            <div class="p-5">
                <?php if($kitsALivrer->count() > 0): ?>
                    <div class="space-y-3">
                        <?php $__currentLoopData = $kitsALivrer; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $kit): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                                <div>
                                    <p class="font-medium"><?php echo e($kit->user->prenom); ?> <?php echo e($kit->user->nom); ?></p>
                                    <p class="text-sm text-gray-500"><?php echo e($kit->user->adresse); ?></p>
                                    <p class="text-xs text-gray-400">Type: <?php echo e($kit->type_kit); ?></p>
                                </div>
                                <a href="<?php echo e(route('collecteur.scanner')); ?>"
                                    class="bg-blue-500 text-white px-3 py-1 rounded text-sm">
                                    <i class="fas fa-qrcode mr-1"></i> Scanner
                                </a>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                <?php else: ?>
                    <div class="text-center py-8 text-gray-500">
                        <i class="fas fa-box-open text-4xl mb-2 block"></i>
                        <p>Aucun kit à livrer</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.collecteur', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\gestion-dechets-dakar\resources\views/collecteur/dashboard.blade.php ENDPATH**/ ?>