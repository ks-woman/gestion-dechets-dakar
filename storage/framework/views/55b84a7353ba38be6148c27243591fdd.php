<?php $__env->startSection('title', 'Utilisateurs'); ?>

<?php $__env->startSection('content'); ?>
    <div class="bg-white rounded-xl shadow-soft">
        <div class="p-5 border-b flex justify-between items-center">
            <h3 class="font-semibold text-gray-800 flex items-center gap-2">
                <i class="fas fa-users text-emerald-500"></i> Gestion des utilisateurs
            </h3>
            <div class="flex gap-3">
                <a href="<?php echo e(route('admin.utilisateurs.create')); ?>" class="btn-primary text-sm">
                    <i class="fas fa-plus mr-1"></i> Ajouter
                </a>
                <span class="text-sm text-gray-500">Total : <?php echo e($users->total()); ?></span>
            </div>
        </div>

        <!-- Recherche -->
        <div class="p-4 border-b bg-gray-50">
            <form method="GET" class="flex gap-3">
                <input type="text" name="search" value="<?php echo e(request('search')); ?>"
                    placeholder="Rechercher par nom, email ou téléphone..."
                    class="flex-1 border rounded-lg p-2 focus:ring-2 focus:ring-emerald-500">
                <button type="submit" class="btn-primary text-sm">
                    <i class="fas fa-search mr-1"></i> Rechercher
                </button>
                <?php if(request('search')): ?>
                    <a href="<?php echo e(route('admin.utilisateurs')); ?>" class="btn-gray text-sm">
                        <i class="fas fa-times mr-1"></i> Réinitialiser
                    </a>
                <?php endif; ?>
            </form>
        </div>

        <!-- Tableau -->
        <div class="overflow-x-auto p-5">
            <table class="w-full">
                <thead>
                    <tr class="text-left text-gray-500 border-b">
                        <th class="pb-3">ID</th>
                        <th class="pb-3">Nom</th>
                        <th class="pb-3">Email</th>
                        <th class="pb-3">Rôle</th>
                        <th class="pb-3">Statut</th>
                        <th class="pb-3">Points</th>
                        <th class="pb-3">Inscription</th>
                        <th class="pb-3">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr class="border-b hover:bg-gray-50">
                            <td class="py-3"><?php echo e($user->id); ?></td>
                            <td class="py-3 font-medium"><?php echo e($user->prenom); ?> <?php echo e($user->nom); ?></td>
                            <td class="py-3 text-gray-600"><?php echo e($user->email); ?></td>
                            <td class="py-3">
                                <?php if($user->role == 'admin'): ?>
                                    <span class="badge-danger"><i class="fas fa-crown mr-1"></i> Admin</span>
                                <?php elseif($user->role == 'collecteur'): ?>
                                    <span class="badge-info"><i class="fas fa-truck mr-1"></i> Collecteur</span>
                                <?php elseif($user->role == 'menage'): ?>
                                    <span class="badge-success"><i class="fas fa-home mr-1"></i> Ménage</span>
                                <?php elseif($user->role == 'entreprise'): ?>
                                    <span class="badge-warning"><i class="fas fa-building mr-1"></i> Entreprise</span>
                                <?php else: ?>
                                    <span class="badge-gray"><i class="fas fa-handshake mr-1"></i> Partenaire</span>
                                <?php endif; ?>
                            </td>
                            <td class="py-3">
                                <?php if($user->statut_compte == 'abonne_actif'): ?>
                                    <span class="badge-success"> Actif</span>
                                <?php elseif($user->statut_compte == 'essai_15j'): ?>
                                    <span class="badge-info"> Essai</span>
                                <?php else: ?>
                                    <span class="badge-gray"> <?php echo e($user->statut_compte); ?></span>
                                <?php endif; ?>
                            </td>
                            <td class="py-3 font-semibold"><?php echo e($user->score_total); ?> pts</td>
                            <td class="py-3 text-gray-500"><?php echo e($user->created_at->format('d/m/Y')); ?></td>
                            <td class="py-3">
                                <div class="flex gap-2">
                                    <a href="<?php echo e(route('admin.utilisateurs.edit', $user->id)); ?>"
                                        class="text-blue-500 hover:text-blue-700" title="Modifier">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form method="POST" action="<?php echo e(route('admin.utilisateurs.destroy', $user->id)); ?>"
                                        class="inline" onsubmit="return confirm('Supprimer cet utilisateur ?')">
                                        <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                        <button type="submit" class="text-red-500 hover:text-red-700" title="Supprimer">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="8" class="py-6 text-center text-gray-500">Aucun utilisateur trouvé</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div class="p-5 border-t"><?php echo e($users->links()); ?></div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\gestion-dechets-dakar\resources\views/admin/utilisateurs.blade.php ENDPATH**/ ?>