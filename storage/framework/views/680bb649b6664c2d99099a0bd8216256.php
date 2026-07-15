<?php $__env->startSection('title', 'Ajouter un utilisateur'); ?>

<?php $__env->startSection('content'); ?>
    <div class="bg-white rounded-xl shadow-md p-6">
        <div class="flex items-center gap-3 mb-6">
            <i class="fas fa-user-plus text-2xl text-emerald-500"></i>
            <h1 class="text-2xl font-bold text-gray-800">Ajouter un utilisateur</h1>
        </div>

        <form method="POST" action="<?php echo e(route('admin.utilisateurs.store')); ?>">
            <?php echo csrf_field(); ?>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-gray-700 mb-2">Nom</label>
                    <input type="text" name="nom" class="w-full border rounded-lg p-2" required>
                </div>
                <div>
                    <label class="block text-gray-700 mb-2">Prénom</label>
                    <input type="text" name="prenom" class="w-full border rounded-lg p-2" required>
                </div>
                <div>
                    <label class="block text-gray-700 mb-2">Email</label>
                    <input type="email" name="email" class="w-full border rounded-lg p-2" required>
                </div>
                <div>
                    <label class="block text-gray-700 mb-2">Téléphone</label>
                    <input type="tel" name="telephone" class="w-full border rounded-lg p-2" required>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-gray-700 mb-2">Adresse</label>
                    <textarea name="adresse" class="w-full border rounded-lg p-2" required></textarea>
                </div>
                <div>
                    <label class="block text-gray-700 mb-2">Quartier</label>
                    <input type="text" name="quartier" class="w-full border rounded-lg p-2" required>
                </div>
                <div>
                    <label class="block text-gray-700 mb-2">Rôle</label>
                    <select name="role" id="role" class="w-full border rounded-lg p-2" required>
                        <option value="menage">Ménage</option>
                        <option value="entreprise">Entreprise</option>
                        <option value="collecteur">Collecteur</option>
                        <option value="partenaire">Partenaire</option>
                        <option value="admin">Administrateur</option>
                    </select>
                </div>
                <div>
                    <label class="block text-gray-700 mb-2">Mot de passe</label>
                    <input type="password" name="mot_passe" class="w-full border rounded-lg p-2" required>
                </div>
            </div>

            <div class="mt-6 flex gap-3">
                <button type="submit" class="bg-emerald-500 text-white px-6 py-2 rounded-lg hover:bg-emerald-600">
                    <i class="fas fa-save mr-2"></i> Enregistrer
                </button>
                <a href="<?php echo e(route('admin.utilisateurs')); ?>"
                    class="bg-gray-500 text-white px-6 py-2 rounded-lg hover:bg-gray-600">
                    <i class="fas fa-times mr-2"></i> Annuler
                </a>
            </div>
        </form>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\gestion-dechets-dakar\resources\views/admin/utilisateurs-create.blade.php ENDPATH**/ ?>