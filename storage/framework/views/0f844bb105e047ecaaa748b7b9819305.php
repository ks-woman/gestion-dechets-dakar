<?php $__env->startSection('title', 'Gestion des réclamations'); ?>

<?php $__env->startSection('content'); ?>
    <div class="space-y-6">
        <div class="bg-white rounded-xl shadow-soft p-6">
            <h1 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
                <i class="fas fa-exclamation-triangle text-red-500"></i> Gestion des réclamations
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
            <div class="card-danger">
                <div>
                    <p class="text-gray-500 text-sm">Ouvertes</p>
                    <p class="text-2xl font-bold text-red-600"><?php echo e($stats['ouvertes']); ?></p>
                </div>
            </div>
            <div class="card-secondary">
                <div>
                    <p class="text-gray-500 text-sm">En cours</p>
                    <p class="text-2xl font-bold text-yellow-600"><?php echo e($stats['en_cours']); ?></p>
                </div>
            </div>
            <div class="card">
                <div>
                    <p class="text-gray-500 text-sm">Résolues</p>
                    <p class="text-2xl font-bold text-emerald-600"><?php echo e($stats['resolues']); ?></p>
                </div>
            </div>
            <div class="card">
                <div>
                    <p class="text-gray-500 text-sm">Fermées</p>
                    <p class="text-2xl font-bold text-gray-600"><?php echo e($stats['fermees']); ?></p>
                </div>
            </div>
        </div>

        <!-- Filtre -->
        <div class="bg-white rounded-xl shadow-soft p-4">
            <form method="GET" class="flex gap-3 items-center">
                <label class="text-sm text-gray-600">Filtrer par statut :</label>
                <select name="statut" class="border rounded-lg p-2 focus:ring-2 focus:ring-emerald-500">
                    <option value="">Tous</option>
                    <option value="ouverte" <?php echo e(request('statut') == 'ouverte' ? 'selected' : ''); ?>>Ouverte</option>
                    <option value="en_cours" <?php echo e(request('statut') == 'en_cours' ? 'selected' : ''); ?>>En cours</option>
                    <option value="resolue" <?php echo e(request('statut') == 'resolue' ? 'selected' : ''); ?>>Résolue</option>
                    <option value="fermee" <?php echo e(request('statut') == 'fermee' ? 'selected' : ''); ?>>Fermée</option>
                </select>
                <button type="submit" class="btn-primary text-sm">Filtrer</button>
                <a href="<?php echo e(route('admin.reclamations.index')); ?>" class="btn-gray text-sm">Réinitialiser</a>
            </form>
        </div>

        <!-- Liste -->
        <div class="bg-white rounded-xl shadow-soft overflow-hidden">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">ID</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Utilisateur</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Sujet</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Statut</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Date</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $reclamations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $reclamation): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr class="border-b hover:bg-gray-50">
                            <td class="px-4 py-3"><?php echo e($reclamation->id); ?></td>
                            <td class="px-4 py-3">
                                <?php echo e($reclamation->user->prenom); ?> <?php echo e($reclamation->user->nom); ?>

                                <br><span class="text-xs text-gray-400"><?php echo e($reclamation->user->email); ?></span>
                            </td>
                            <td class="px-4 py-3 font-medium"><?php echo e(Str::limit($reclamation->sujet, 40)); ?></td>
                            <td class="px-4 py-3">
                                <?php if($reclamation->statut == 'ouverte'): ?>
                                    <span class="badge-danger"> Ouverte</span>
                                <?php elseif($reclamation->statut == 'en_cours'): ?>
                                    <span class="badge-warning"> En cours</span>
                                <?php elseif($reclamation->statut == 'resolue'): ?>
                                    <span class="badge-success"> Résolue</span>
                                <?php else: ?>
                                    <span class="badge-gray"> Fermée</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-4 py-3 text-gray-500"><?php echo e($reclamation->created_at->format('d/m/Y H:i')); ?></td>
                            <td class="px-4 py-3">
                                <a href="<?php echo e(route('admin.reclamations.show', $reclamation->id)); ?>"
                                    class="text-blue-500 hover:text-blue-700">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="6" class="px-4 py-8 text-center text-gray-500">Aucune réclamation trouvée.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
            <div class="px-4 py-3 border-t"><?php echo e($reclamations->links()); ?></div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\gestion-dechets-dakar\resources\views/admin/reclamations/index.blade.php ENDPATH**/ ?>