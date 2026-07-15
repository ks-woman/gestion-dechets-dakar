<?php $__env->startSection('title', 'Gestion des abonnements'); ?>

<?php $__env->startSection('content'); ?>
    <div class="space-y-6">
        <div class="bg-white rounded-xl shadow-soft p-6">
            <h1 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
                <i class="fas fa-crown text-emerald-500"></i> Gestion des abonnements
            </h1>
            <p class="text-gray-500 mt-1">Gérez tous les abonnements des utilisateurs.</p>
        </div>

        <!-- Statistiques -->
        <div class="grid grid-cols-5 gap-4">
            <div class="card">
                <div>
                    <p class="text-gray-500 text-sm">Total</p>
                    <p class="text-2xl font-bold"><?php echo e($stats['total']); ?></p>
                </div>
            </div>
            <div class="card-info">
                <div>
                    <p class="text-gray-500 text-sm">En essai</p>
                    <p class="text-2xl font-bold text-blue-600"><?php echo e($stats['essai']); ?></p>
                </div>
            </div>
            <div class="card-success">
                <div>
                    <p class="text-gray-500 text-sm">Actifs</p>
                    <p class="text-2xl font-bold text-emerald-600"><?php echo e($stats['actif']); ?></p>
                </div>
            </div>
            <div class="card-danger">
                <div>
                    <p class="text-gray-500 text-sm">Expirés</p>
                    <p class="text-2xl font-bold text-red-600"><?php echo e($stats['expire']); ?></p>
                </div>
            </div>
            <div class="card-secondary">
                <div>
                    <p class="text-gray-500 text-sm">Résiliés</p>
                    <p class="text-2xl font-bold text-gray-600"><?php echo e($stats['resilie']); ?></p>
                </div>
            </div>
        </div>

        <!-- Liste -->
        <div class="bg-white rounded-xl shadow-soft overflow-hidden">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">#</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Utilisateur</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Montant</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Début essai</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Fin essai</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Statut</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $abonnements; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $abonnement): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr class="border-b hover:bg-gray-50">
                            <td class="px-4 py-3">#<?php echo e($abonnement->id); ?></td>
                            <td class="px-4 py-3">
                                <span class="font-medium"><?php echo e($abonnement->user->prenom ?? ''); ?>

                                    <?php echo e($abonnement->user->nom ?? ''); ?></span>
                                <br><span class="text-xs text-gray-400"><?php echo e($abonnement->user->email ?? ''); ?></span>
                            </td>
                            <td class="px-4 py-3 font-semibold">
                                <?php echo e(number_format($abonnement->montant_mensuel, 0, ',', ' ')); ?> FCFA</td>
                            <td class="px-4 py-3">
                                <?php echo e($abonnement->date_debut_essai ? $abonnement->date_debut_essai->format('d/m/Y') : '-'); ?>

                            </td>
                            <td class="px-4 py-3">
                                <?php echo e($abonnement->date_fin_essai ? $abonnement->date_fin_essai->format('d/m/Y') : '-'); ?></td>
                            <td class="px-4 py-3">
                                <?php if($abonnement->estActif()): ?>
                                    <span class="badge-success"><i class="fas fa-check-circle mr-1"></i> Actif</span>
                                <?php elseif($abonnement->estEnEssai()): ?>
                                    <span class="badge-info"><i class="fas fa-clock mr-1"></i> Essai</span>
                                <?php elseif($abonnement->statut === 'expire'): ?>
                                    <span class="badge-danger"><i class="fas fa-times-circle mr-1"></i> Expiré</span>
                                <?php else: ?>
                                    <span class="badge-gray"><i class="fas fa-ban mr-1"></i> Résilié</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex gap-2">
                                    <a href="<?php echo e(route('admin.abonnements.show', $abonnement->id)); ?>"
                                        class="text-blue-500 hover:text-blue-700" title="Voir">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <?php if($abonnement->statut !== 'actif' && $abonnement->statut !== 'resilie'): ?>
                                        <form method="POST"
                                            action="<?php echo e(route('admin.abonnements.activer', $abonnement->id)); ?>"
                                            class="inline">
                                            <?php echo csrf_field(); ?>
                                            <button type="submit" class="text-emerald-500 hover:text-emerald-700"
                                                title="Activer">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        </form>
                                    <?php endif; ?>
                                    <?php if($abonnement->statut !== 'resilie' && $abonnement->statut !== 'expire'): ?>
                                        <form method="POST"
                                            action="<?php echo e(route('admin.abonnements.resilier', $abonnement->id)); ?>"
                                            class="inline" onsubmit="return confirm('Confirmer la résiliation ?')">
                                            <?php echo csrf_field(); ?>
                                            <button type="submit" class="text-red-500 hover:text-red-700" title="Résilier">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </form>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="7" class="px-4 py-8 text-center text-gray-500">Aucun abonnement trouvé.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
            <div class="px-4 py-3 border-t"><?php echo e($abonnements->links()); ?></div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\gestion-dechets-dakar\resources\views/admin/abonnements/index.blade.php ENDPATH**/ ?>