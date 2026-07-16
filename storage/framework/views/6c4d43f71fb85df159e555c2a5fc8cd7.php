<?php $__env->startSection('title', 'Historique des commandes'); ?>

<?php $__env->startSection('content'); ?>
    <div class="space-y-6">
        <div class="bg-white rounded-xl shadow-soft p-6">
            <h1 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
                <i class="fas fa-history text-emerald-500"></i> Historique des commandes
            </h1>
        </div>

        <!-- Statistiques -->
        <div class="grid grid-cols-5 gap-4">
            <div class="card">
                <div>
                    <p class="text-gray-500 text-sm">Total</p>
                    <p class="text-2xl font-bold"><?php echo e($stats['total']); ?></p>
                </div>
            </div>
            <div class="card-secondary">
                <div>
                    <p class="text-gray-500 text-sm">En attente</p>
                    <p class="text-2xl font-bold text-yellow-600"><?php echo e($stats['en_attente']); ?></p>
                </div>
            </div>
            <div class="card-info">
                <div>
                    <p class="text-gray-500 text-sm">Validées</p>
                    <p class="text-2xl font-bold text-blue-600"><?php echo e($stats['validees']); ?></p>
                </div>
            </div>
            <div class="card-success">
                <div>
                    <p class="text-gray-500 text-sm">Livrées</p>
                    <p class="text-2xl font-bold text-emerald-600"><?php echo e($stats['livrees']); ?></p>
                </div>
            </div>
            <div class="card-success">
                <div>
                    <p class="text-gray-500 text-sm">Réceptionnées</p>
                    <p class="text-2xl font-bold text-purple-600"><?php echo e($stats['recues'] ?? 0); ?></p>
                </div>
            </div>
        </div>

        <!-- Liste -->
        <div class="bg-white rounded-xl shadow-soft overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full min-w-[700px]">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">ID</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600 hidden sm:table-cell">
                                Collecte</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600 hidden md:table-cell">
                                Catégorie</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Quantité</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600 hidden lg:table-cell">Montant
                            </th>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Statut</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $commandes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $commande): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr class="border-b hover:bg-gray-50">
                                <td class="px-4 py-3 font-medium">#<?php echo e($commande->id); ?></td>
                                <td class="px-4 py-3 hidden sm:table-cell"><?php echo e($commande->collecte->user->prenom ?? ''); ?>

                                </td>
                                <td class="px-4 py-3 hidden md:table-cell"><?php echo e($commande->categorie->nom ?? 'Non définie'); ?>

                                </td>
                                <td class="px-4 py-3"><?php echo e(number_format($commande->quantite, 1)); ?> kg</td>
                                <td class="px-4 py-3 font-semibold hidden lg:table-cell">
                                    <?php echo e(number_format($commande->montant_total, 0, ',', ' ')); ?> FCFA</td>
                                <td class="px-4 py-3">
                                    <?php if($commande->statut == 'en_attente'): ?>
                                        <span class="badge-warning">En attente</span>
                                    <?php elseif($commande->statut == 'validee'): ?>
                                        <span class="badge-info">Validée</span>
                                    <?php elseif($commande->statut == 'affectee'): ?>
                                        <span class="badge-info">Affectée</span>
                                    <?php elseif($commande->statut == 'livree'): ?>
                                        <span class="badge-success">Livrée</span>
                                    <?php elseif($commande->statut == 'recue'): ?>
                                        <span class="badge-success">
                                            <i class="fas fa-check-circle mr-1"></i> Réceptionnée
                                        </span>
                                    <?php else: ?>
                                        <span class="badge-danger">Annulée</span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex flex-wrap items-center gap-2">
                                        <!--  Confirmer réception (pour commandes livrées) -->
                                        <?php if($commande->statut == 'livree'): ?>
                                            <form method="POST"
                                                action="<?php echo e(route('partenaire.commande.confirmer-reception', $commande->id)); ?>"
                                                class="inline"
                                                onsubmit="return confirm('Confirmer la réception de cette commande ?')">
                                                <?php echo csrf_field(); ?>
                                                <button type="submit"
                                                    class="bg-emerald-500 hover:bg-emerald-600 text-white px-2 md:px-3 py-1 rounded text-[10px] md:text-xs transition">
                                                    <i class="fas fa-check mr-1"></i> Confirmer
                                                </button>
                                            </form>
                                        <?php elseif($commande->statut == 'recue'): ?>
                                            <span class="badge-success text-xs">
                                                <i class="fas fa-check-circle mr-1"></i> Réceptionnée
                                            </span>
                                        <?php endif; ?>

                                        <!-- Certificat -->
                                        <?php if($commande->statut == 'livree' || $commande->statut == 'recue'): ?>
                                            <?php $certificat = $commande->certificat; ?>
                                            <?php if($certificat): ?>
                                                <a href="<?php echo e(route('partenaire.certificat.show', $certificat->id)); ?>"
                                                    class="text-purple-500 hover:text-purple-700 text-sm"
                                                    title="Certificat">
                                                    <i class="fas fa-file-pdf"></i>
                                                </a>
                                            <?php else: ?>
                                                <a href="<?php echo e(route('partenaire.certificat.generer', $commande->id)); ?>"
                                                    class="text-blue-500 hover:text-blue-700 text-sm"
                                                    title="Générer certificat">
                                                    <i class="fas fa-file-pdf"></i>
                                                </a>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="7" class="px-4 py-8 text-center text-gray-500">Aucune commande passée.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <div class="px-4 py-3 border-t"><?php echo e($commandes->links()); ?></div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.partenaire', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\gestion-dechets-dakar\resources\views/partenaire/historique.blade.php ENDPATH**/ ?>