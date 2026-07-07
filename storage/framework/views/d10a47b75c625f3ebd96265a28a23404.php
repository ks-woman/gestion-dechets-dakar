<?php $__env->startSection('title', 'Historique des collectes'); ?>

<?php $__env->startSection('content'); ?>
    <div class="space-y-6">
        <div class="bg-white rounded-xl shadow p-6">
            <h1 class="text-2xl font-bold text-gray-800"> Historique des collectes</h1>
            <p class="text-gray-500 mt-1">Consultez toutes vos collectes passées.</p>
        </div>

        <!-- Stats -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-white rounded-xl shadow p-4 border-l-4 border-blue-500">
                <p class="text-gray-500 text-sm">Total collectes</p>
                <p class="text-2xl font-bold text-blue-600"><?php echo e($stats['total']); ?></p>
            </div>
            <div class="bg-white rounded-xl shadow p-4 border-l-4 border-green-500">
                <p class="text-gray-500 text-sm">Poids total</p>
                <p class="text-2xl font-bold text-green-600"><?php echo e(number_format($stats['total_poids'], 1)); ?> kg</p>
            </div>
            <div class="bg-white rounded-xl shadow p-4 border-l-4 border-yellow-500">
                <p class="text-gray-500 text-sm">Points générés</p>
                <p class="text-2xl font-bold text-yellow-600"><?php echo e(number_format($stats['total_points'])); ?></p>
            </div>
        </div>

        <!-- Filtres -->
        <div class="bg-white rounded-xl shadow p-4">
            <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-gray-600 text-sm">Date début</label>
                    <input type="date" name="date_debut" value="<?php echo e(request('date_debut')); ?>"
                        class="w-full border rounded-lg p-2">
                </div>
                <div>
                    <label class="block text-gray-600 text-sm">Date fin</label>
                    <input type="date" name="date_fin" value="<?php echo e(request('date_fin')); ?>"
                        class="w-full border rounded-lg p-2">
                </div>
                <div>
                    <label class="block text-gray-600 text-sm">Client</label>
                    <input type="text" name="client" placeholder="Nom ou prénom" value="<?php echo e(request('client')); ?>"
                        class="w-full border rounded-lg p-2">
                </div>
                <div>
                    <label class="block text-gray-600 text-sm">Statut</label>
                    <select name="statut" class="w-full border rounded-lg p-2">
                        <option value="">Tous</option>
                        <option value="planifiee" <?php echo e(request('statut') == 'planifiee' ? 'selected' : ''); ?>>Planifiée
                        </option>
                        <option value="realisee" <?php echo e(request('statut') == 'realisee' ? 'selected' : ''); ?>>Réalisée</option>
                        <option value="en_cours" <?php echo e(request('statut') == 'en_cours' ? 'selected' : ''); ?>>En cours</option>
                        <option value="annulee" <?php echo e(request('statut') == 'annulee' ? 'selected' : ''); ?>>Annulée</option>
                    </select>
                </div>
                <div class="md:col-span-4 flex gap-2">
                    <button type="submit"
                        class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg">Filtrer</button>
                    <a href="<?php echo e(route('collecteur.historique')); ?>"
                        class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-4 py-2 rounded-lg">Réinitialiser</a>
                </div>
            </form>
        </div>

        <!-- Liste -->
        <div class="bg-white rounded-xl shadow overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Date</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Client</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Poids</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Points</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Statut</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $collectes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $collecte): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr class="border-b hover:bg-gray-50">
                                <td class="px-4 py-3">
                                    <?php echo e(\Carbon\Carbon::parse($collecte->date_collecte)->format('d/m/Y H:i')); ?></td>
                                <td class="px-4 py-3"><?php echo e($collecte->user->prenom ?? ''); ?> <?php echo e($collecte->user->nom ?? ''); ?>

                                </td>
                                <td class="px-4 py-3">
                                    <?php echo e(number_format($collecte->poids_recyclable + $collecte->poids_organique + $collecte->poids_residuel, 1)); ?>

                                    kg</td>
                                <td class="px-4 py-3 font-semibold text-yellow-600"><?php echo e($collecte->points_obtenus); ?></td>
                                <td class="px-4 py-3">
                                    <?php if($collecte->statut == 'realisee'): ?>
                                        <span class="px-2 py-1 rounded text-xs bg-green-100 text-green-800">
                                            Réalisée</span>
                                    <?php elseif($collecte->statut == 'planifiee'): ?>
                                        <span class="px-2 py-1 rounded text-xs bg-blue-100 text-blue-800"> Planifiée</span>
                                    <?php else: ?>
                                        <span
                                            class="px-2 py-1 rounded text-xs bg-gray-100 text-gray-800"><?php echo e($collecte->statut); ?></span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="5" class="px-4 py-8 text-center text-gray-500">Aucune collecte trouvée.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <div class="px-4 py-3 border-t">
                <?php echo e($collectes->links()); ?>

            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.collecteur', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\gestion-dechets-dakar\resources\views/collecteur/historique.blade.php ENDPATH**/ ?>