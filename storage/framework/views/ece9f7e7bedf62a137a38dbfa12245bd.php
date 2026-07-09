<?php $__env->startSection('title', 'Offres de déchets'); ?>

<?php $__env->startSection('content'); ?>
    <div class="space-y-6">
        <div class="bg-white rounded-xl shadow-soft p-6">
            <h1 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
                <i class="fas fa-boxes text-emerald-500"></i> Offres de déchets disponibles
            </h1>
            <p class="text-gray-500 mt-1">Consultez les quantités disponibles par catégorie de déchet.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <?php $__empty_1 = true; $__currentLoopData = $stocks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $stock): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="bg-white rounded-xl shadow-soft border p-4 hover:shadow-lg transition">
                    <div class="flex items-center gap-2 mb-2">
                        <?php if($stock->categorie): ?>
                            <span class="text-3xl"><?php echo e($stock->categorie->icone ?? ''); ?></span>
                            <h3 class="text-lg font-bold text-gray-800"><?php echo e($stock->categorie->nom); ?></h3>
                        <?php else: ?>
                            <span class="text-3xl"></span>
                            <h3 class="text-lg font-bold text-gray-800">Catégorie inconnue</h3>
                        <?php endif; ?>
                    </div>

                    <div class="mt-2">
                        <p class="text-2xl font-bold text-emerald-600"><?php echo e(number_format($stock->quantite, 1)); ?> kg</p>
                        <p class="text-sm text-gray-500">Prix : <?php echo e(number_format($stock->prix_unitaire ?? 0, 0)); ?> FCFA/kg
                        </p>
                    </div>

                    <?php if($stock->quantite > 0): ?>
                        <div class="mt-3">
                            <button
                                onclick="openCommandeForm('<?php echo e($stock->categorie->id); ?>', '<?php echo e($stock->categorie->nom); ?>', <?php echo e($stock->quantite); ?>, <?php echo e($stock->prix_unitaire); ?>)"
                                class="btn-primary w-full text-center block">
                                <i class="fas fa-cart-plus mr-2"></i> Commander
                            </button>
                        </div>
                    <?php else: ?>
                        <div class="mt-3">
                            <button disabled class="btn-gray w-full text-center block cursor-not-allowed">
                                <i class="fas fa-times-circle mr-2"></i> Rupture de stock
                            </button>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="col-span-3 text-center py-12 text-gray-500">
                    <i class="fas fa-box-open text-4xl mb-2 block"></i>
                    <p>Aucune offre disponible pour le moment.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Modal de commande -->
    <div id="commandeModal" class="fixed inset-0 bg-gray-900/50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-xl shadow-2xl p-6 max-w-md w-full mx-4">
            <h2 class="text-xl font-bold text-gray-800 mb-4"> Passer commande</h2>
            <form method="POST" action="<?php echo e(route('partenaire.commander.store')); ?>">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="categorie_id" id="modal_categorie_id">

                <div class="mb-4">
                    <label class="block text-gray-700">Catégorie</label>
                    <p id="modal_categorie_nom" class="text-lg font-semibold capitalize text-emerald-600"></p>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700">Quantité (kg)</label>
                    <input type="number" step="0.01" name="quantite" id="modal_quantite"
                        class="w-full border rounded-lg p-2 focus:ring-2 focus:ring-emerald-500" required>
                    <p class="text-xs text-gray-500 mt-1">Maximum : <span id="modal_max_quantite">0</span> kg</p>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700">Prix unitaire (FCFA/kg)</label>
                    <input type="number" step="0.01" name="prix_unitaire" id="modal_prix_unitaire"
                        class="w-full border rounded-lg p-2 focus:ring-2 focus:ring-emerald-500" required>
                </div>

                <div class="flex gap-3">
                    <button type="submit" class="btn-primary flex-1"> Confirmer</button>
                    <button type="button" onclick="closeCommandeForm()" class="btn-gray flex-1">Annuler</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openCommandeForm(categorieId, nom, quantite, prix) {
            document.getElementById('modal_categorie_id').value = categorieId;
            document.getElementById('modal_categorie_nom').textContent = nom;
            document.getElementById('modal_quantite').max = quantite;
            document.getElementById('modal_max_quantite').textContent = quantite;
            document.getElementById('modal_prix_unitaire').value = prix || 0;
            document.getElementById('commandeModal').classList.remove('hidden');
            document.getElementById('commandeModal').classList.add('flex');
        }

        function closeCommandeForm() {
            document.getElementById('commandeModal').classList.add('hidden');
            document.getElementById('commandeModal').classList.remove('flex');
        }

        document.getElementById('commandeModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeCommandeForm();
            }
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.partenaire', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\gestion-dechets-dakar\resources\views/partenaire/offres.blade.php ENDPATH**/ ?>