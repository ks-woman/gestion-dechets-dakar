<?php $__env->startSection('title', 'Tableau de bord'); ?>

<?php $__env->startSection('content'); ?>
    <div class="space-y-6">
        <!-- Bannière -->
        <div class="bg-gradient-to-r from-emerald-600 to-emerald-800 rounded-xl shadow-soft p-6 text-white">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-bold">Bonjour <?php echo e(auth()->user()->prenom); ?> !</h1>
                    <p class="text-emerald-100 mt-1">Centre de valorisation - Tableau de bord</p>
                </div>
                <div class="text-5xl"></div>
            </div>
        </div>

        <!-- 3 indicateurs principaux (supprimés : Points générés) -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="card">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-gray-500 text-sm">Collectes reçues</p>
                        <p class="text-2xl font-bold text-emerald-600"><?php echo e($totalCollectes ?? 0); ?></p>
                    </div>
                    <i class="fas fa-truck text-3xl text-emerald-500"></i>
                </div>
            </div>
            <div class="card">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-gray-500 text-sm">Total valorisé</p>
                        <p class="text-2xl font-bold text-emerald-600"><?php echo e(number_format($totalPoids ?? 0, 0)); ?> kg</p>
                    </div>
                    <i class="fas fa-recycle text-3xl text-emerald-500"></i>
                </div>
            </div>
            <div class="card">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-gray-500 text-sm">En attente</p>
                        <p class="text-2xl font-bold text-emerald-600"><?php echo e($enAttente ?? 0); ?></p>
                    </div>
                    <i class="fas fa-clock text-3xl text-emerald-500"></i>
                </div>
            </div>
            <!-- Points générés supprimés -->
        </div>

        <!-- Offres disponibles (rappel) -->
        <?php if(isset($offresDisponibles) && $offresDisponibles > 0): ?>
            <div class="bg-emerald-50 border border-emerald-200 rounded-xl p-4 text-center">
                <p class="text-emerald-800">
                    <span class="font-bold"><?php echo e($offresDisponibles); ?></span> offre(s) disponible(s)
                    <a href="<?php echo e(route('partenaire.offres')); ?>"
                        class="text-emerald-600 font-medium hover:underline ml-2">Consulter →</a>
                </p>
            </div>
        <?php endif; ?>

        <!-- Actions rapides -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
            <a href="<?php echo e(route('partenaire.offres')); ?>" class="btn-primary text-center">
                <i class="fas fa-boxes mr-2"></i> Voir les offres
            </a>
            <a href="<?php echo e(route('partenaire.dechets')); ?>" class="btn-primary text-center">
                <i class="fas fa-boxes mr-2"></i> Déchets reçus
            </a>
            <a href="<?php echo e(route('partenaire.historique')); ?>" class="btn-primary text-center">
                <i class="fas fa-history mr-2"></i> Historique
            </a>
        </div>

        <!-- Dernières commandes -->
        <div class="bg-white rounded-xl shadow-soft p-6">
            <h2 class="text-lg font-semibold text-gray-800 flex items-center gap-2 mb-4">
                <i class="fas fa-shopping-cart text-emerald-500"></i> Dernières commandes
            </h2>
            <?php if(isset($dernieresCommandes) && $dernieresCommandes->count() > 0): ?>
                <div class="space-y-3">
                    <?php $__currentLoopData = $dernieresCommandes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $commande): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                            <div>
                                <p class="font-medium">Commande #<?php echo e($commande->id); ?></p>
                                <p class="text-xs text-gray-400"><?php echo e($commande->created_at->format('d/m/Y')); ?></p>
                            </div>
                            <div class="text-right">
                                <p class="font-semibold text-emerald-600"><?php echo e(number_format($commande->quantite, 1)); ?> kg
                                </p>
                                <p class="text-sm text-gray-500"><?php echo e(number_format($commande->montant_total, 0, ',', ' ')); ?>

                                    FCFA</p>
                                <?php if($commande->statut == 'livree'): ?>
                                    <span class="badge-success">Livrée</span>
                                <?php elseif($commande->statut == 'en_attente'): ?>
                                    <span class="badge-warning">En attente</span>
                                <?php elseif($commande->statut == 'validee'): ?>
                                    <span class="badge-info">Validée</span>
                                <?php else: ?>
                                    <span class="badge-danger">Annulée</span>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            <?php else: ?>
                <div class="text-center py-8 text-gray-500">
                    <i class="fas fa-box-open text-4xl mb-2 block"></i>
                    <p>Aucune commande passée pour le moment.</p>
                </div>
            <?php endif; ?>
        </div>

        <!-- Notifications (supprimées car vides) -->
        
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.partenaire', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\gestion-dechets-dakar\resources\views/partenaire/dashboard.blade.php ENDPATH**/ ?>