<?php $__env->startSection('title', 'Ajouter une récompense'); ?>

<?php $__env->startSection('content'); ?>
    <div class="bg-white rounded-xl shadow-soft p-6 max-w-2xl mx-auto">
        <h1 class="text-2xl font-bold text-gray-800 mb-4">Ajouter une récompense</h1>

        <form method="POST" action="<?php echo e(route('admin.recompenses.store')); ?>">
            <?php echo csrf_field(); ?>
            <div class="grid grid-cols-2 gap-4">
                <div class="col-span-2">
                    <label class="block text-gray-700">Nom</label>
                    <input type="text" name="nom_recompense"
                        class="w-full border rounded-lg p-2 focus:ring-2 focus:ring-emerald-500" required>
                </div>
                <div>
                    <label class="block text-gray-700">Points requis</label>
                    <input type="number" name="points_requis"
                        class="w-full border rounded-lg p-2 focus:ring-2 focus:ring-emerald-500" required>
                </div>
                <div>
                    <label class="block text-gray-700">Type</label>
                    <select name="type_recompense" class="w-full border rounded-lg p-2 focus:ring-2 focus:ring-emerald-500"
                        required>
                        <option value="bon_achat">Bon d'achat</option>
                        <option value="article_physique">Article</option>
                        <option value="reduction">Réduction</option>
                        <option value="cadeau">Cadeau</option>
                    </select>
                </div>
                <div>
                    <label class="block text-gray-700">Quantité</label>
                    <input type="number" name="quantite_disponible"
                        class="w-full border rounded-lg p-2 focus:ring-2 focus:ring-emerald-500" required>
                </div>
                <div>
                    <label class="block text-gray-700">Valeur (optionnel)</label>
                    <input type="number" step="0.01" name="valeur" class="w-full border rounded-lg p-2">
                </div>
                <div class="col-span-2">
                    <label class="block text-gray-700">Description</label>
                    <textarea name="description" rows="3" class="w-full border rounded-lg p-2 focus:ring-2 focus:ring-emerald-500"></textarea>
                </div>
                <div class="col-span-2">
                    <label class="block text-gray-700">Date d'expiration</label>
                    <input type="date" name="date_expiration"
                        class="w-full border rounded-lg p-2 focus:ring-2 focus:ring-emerald-500">
                </div>
            </div>
            <div class="mt-6 flex gap-3">
                <button type="submit" class="btn-primary">Enregistrer</button>
                <a href="<?php echo e(route('admin.recompenses.index')); ?>" class="btn-gray">Annuler</a>
            </div>
        </form>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\gestion-dechets-dakar\resources\views/admin/recompenses/create.blade.php ENDPATH**/ ?>