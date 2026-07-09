<?php $__env->startSection('title', 'Demander mon kit de tri'); ?>

<?php $__env->startSection('content'); ?>
    <div class="bg-white rounded-xl shadow-lg p-6">
        <div class="flex items-center gap-3 mb-6">
            <div class="text-3xl"></div>
            <h1 class="text-2xl font-bold text-gray-800">Demander mon kit de tri</h1>
        </div>

        <?php
            $kit = auth()->user()->kitTri;
            $statutCompte = auth()->user()->statut_compte;
        ?>

        <?php if($kit): ?>
            <!-- Message : Kit déjà commandé -->
            <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-6">
                <div class="flex items-center gap-3">
                    <div class="text-2xl"></div>
                    <div>
                        <p class="font-semibold text-green-800">Kit déjà commandé</p>
                        <p class="text-sm text-green-600">Votre kit est en cours de traitement. Un collecteur vous
                            contactera.</p>
                        <p class="text-xs text-green-500 mt-1">
                            Statut :
                            <?php if($kit->statut_kit == 'en_attente'): ?>
                                En attente de livraison
                            <?php elseif($kit->statut_kit == 'actif'): ?>
                                Actif
                            <?php elseif($kit->statut_kit == 'livre'): ?>
                                Livré
                            <?php else: ?>
                                <?php echo e($kit->statut_kit); ?>

                            <?php endif; ?>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Message : Période d'essai active -->
            <?php if($statutCompte == 'essai_15j'): ?>
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                    <div class="flex items-center gap-3">
                        <div class="text-2xl"></div>
                        <div>
                            <p class="font-semibold text-blue-800">Période d'essai active !</p>
                            <p class="text-sm text-blue-600">Vous pouvez maintenant demander des collectes gratuitement
                                pendant 15 jours.</p>
                            <a href="<?php echo e(route('collecte.demander')); ?>"
                                class="text-blue-600 text-sm underline mt-1 inline-block">
                                → Demander une collecte
                            </a>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Bouton retour -->
            <div class="text-center">
                <a href="<?php echo e(route('menage.dashboard')); ?>"
                    class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600">
                    Retour au tableau de bord
                </a>
            </div>
        <?php else: ?>
            <!-- Formulaire de demande de kit (pas encore de kit) -->
            <form method="POST" action="<?php echo e(route('kit.demander.post')); ?>">
                <?php echo csrf_field(); ?>

                <!-- Type de kit -->
                <div class="mb-6">
                    <label class="block text-gray-700 mb-2">Type de kit</label>
                    <select name="type_kit" class="w-full border rounded-lg p-2 focus:ring-2 focus:ring-emerald-500"
                        required>
                        <option value="standard">Standard - Kit de base (sacs, bio-seau)</option>
                        <option value="renforce">Renforcé - Pour grandes familles (sacs, bacs, bio-seau)</option>
                        <option value="compact">Compact - Pour petits espaces (mini-sacs, bio-seau)</option>
                    </select>
                </div>

                <!-- Contenu du kit -->
                <div class="bg-emerald-50 rounded-lg p-4 mb-6">
                    <h3 class="font-semibold text-emerald-800 mb-2"> Ce que comprend le kit</h3>
                    <ul class="text-sm text-emerald-700 space-y-1">
                        <li>✓ Sacs de tri pour recyclables</li>
                        <li>✓ Bio-seau pour déchets organiques</li>
                        <li>✓ Guide de tri illustré</li>
                        <li>✓ QR code d'activation</li>
                    </ul>
                </div>

                <!-- Information kit offert -->
                <div class="bg-yellow-50 rounded-lg p-4 mb-6">
                    <h3 class="font-semibold text-yellow-800 mb-2"> Kit offert</h3>
                    <p class="text-sm text-yellow-700">Le kit de tri vous est offert. Vous bénéficierez de 15 jours d'essai
                        gratuit après activation.</p>
                </div>

                <!-- Bouton de validation -->
                <button type="submit"
                    class="w-full bg-emerald-500 text-white py-3 rounded-lg font-semibold hover:bg-emerald-600 transition">
                    Confirmer ma demande
                </button>
            </form>
        <?php endif; ?>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.menage', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\gestion-dechets-dakar\resources\views/kit/demander.blade.php ENDPATH**/ ?>