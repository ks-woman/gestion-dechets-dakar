<?php $__env->startSection('title', 'Demander mon kit de tri'); ?>

<?php $__env->startSection('content'); ?>
    <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
        <!-- Bannière -->
        <div class="bg-gradient-to-r from-emerald-600 to-emerald-800 px-6 py-6 text-white">
            <div class="flex items-center gap-4">
                <div class="text-5xl"></div>
                <div>
                    <h1 class="text-2xl font-bold">Demander mon kit de tri</h1>
                    <p class="text-emerald-100 text-sm">Recevez tout le nécessaire pour bien trier vos déchets</p>
                </div>
            </div>
        </div>

        <div class="p-6">
            <?php
                $kit = auth()->user()->kitTri;
                $statutCompte = auth()->user()->statut_compte;
            ?>

            <?php if($kit && $kit->statut == 'en_attente'): ?>
                <!-- Cas 1 : Kit en attente -->
                <div class="text-center py-8">
                    <div class="w-24 h-24 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <span class="text-5xl"></span>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800">Kit en préparation</h3>
                    <p class="text-gray-500 mt-2 max-w-md mx-auto">
                        Votre kit de tri est en cours de traitement. Un collecteur vous contactera pour la livraison.
                    </p>
                    <div class="mt-4 inline-block bg-gray-100 px-4 py-2 rounded-lg">
                        <span class="text-sm text-gray-500">Code :</span>
                        <span class="font-mono text-sm font-semibold"><?php echo e($kit->code_qr); ?></span>
                    </div>
                    <div class="mt-6">
                        <a href="<?php echo e(route('menage.dashboard')); ?>"
                            class="inline-flex items-center gap-2 bg-emerald-600 text-white px-6 py-2 rounded-lg hover:bg-emerald-700 transition">
                            <i class="fas fa-arrow-left"></i> Retour
                        </a>
                    </div>
                </div>
            <?php elseif($kit && in_array($kit->statut, ['livre', 'actif'])): ?>
                <!-- Cas 2 : Kit livré ou actif -->
                <?php if($statutCompte == 'essai_15j'): ?>
                    <div class="bg-gradient-to-r from-emerald-50 to-green-50 rounded-xl p-6 border border-emerald-200">
                        <div class="flex items-start gap-4">
                            <div
                                class="w-14 h-14 bg-emerald-500 rounded-full flex items-center justify-center flex-shrink-0">
                                <span class="text-3xl"></span>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-emerald-800">Période d'essai active !</h3>
                                <p class="text-emerald-700 text-sm mt-1">
                                    Vous bénéficiez de <strong>15 jours d'essai gratuit</strong>.
                                </p>
                                <div class="mt-3 flex flex-wrap gap-2">
                                    <span class="bg-emerald-200 text-emerald-800 text-xs px-3 py-1 rounded-full"> Kit
                                        activé</span>
                                    <span class="bg-emerald-200 text-emerald-800 text-xs px-3 py-1 rounded-full">
                                        Collectes incluses</span>
                                    <span class="bg-emerald-200 text-emerald-800 text-xs px-3 py-1 rounded-full"> Points
                                        gagnés</span>
                                </div>
                                <div class="mt-4">
                                    <a href="<?php echo e(route('collecte.demander')); ?>"
                                        class="inline-flex items-center gap-2 bg-emerald-600 text-white px-6 py-2 rounded-lg hover:bg-emerald-700 transition">
                                        <i class="fas fa-truck"></i> Demander une collecte
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php elseif($statutCompte == 'abonne_actif'): ?>
                    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl p-6 border border-blue-200">
                        <div class="flex items-start gap-4">
                            <div class="w-14 h-14 bg-blue-500 rounded-full flex items-center justify-center flex-shrink-0">
                                <span class="text-3xl"></span>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-blue-800">Kit actif</h3>
                                <p class="text-blue-700 text-sm mt-1">
                                    Votre kit de tri est actif. Vous pouvez demander des collectes à tout moment.
                                </p>
                                <div class="mt-4">
                                    <a href="<?php echo e(route('collecte.demander')); ?>"
                                        class="inline-flex items-center gap-2 bg-emerald-600 text-white px-6 py-2 rounded-lg hover:bg-emerald-700 transition">
                                        <i class="fas fa-truck"></i> Demander une collecte
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="text-center py-8">
                        <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <span class="text-5xl"></span>
                        </div>
                        <h3 class="text-xl font-bold text-gray-800">Kit disponible</h3>
                        <p class="text-gray-500 mt-2">Votre kit est prêt à être utilisé.</p>
                        <div class="mt-6">
                            <a href="<?php echo e(route('menage.dashboard')); ?>"
                                class="inline-flex items-center gap-2 bg-emerald-600 text-white px-6 py-2 rounded-lg hover:bg-emerald-700 transition">
                                <i class="fas fa-arrow-left"></i> Retour
                            </a>
                        </div>
                    </div>
                <?php endif; ?>
            <?php else: ?>
                <!-- Cas 3 : Pas de kit → Formulaire -->
                <div>
                    <!-- Présentation du kit -->
                    <div class="grid md:grid-cols-2 gap-8 mb-8">
                        <div
                            class="bg-gradient-to-br from-emerald-50 to-emerald-100 rounded-2xl p-8 flex items-center justify-center border-2 border-emerald-200">
                            <div class="text-center">
                                <div class="text-8xl mb-4"></div>
                                <p class="text-emerald-800 font-semibold">Kit de tri</p>
                                <p class="text-emerald-600 text-sm">Édition 2025</p>
                                <div class="mt-4 flex justify-center gap-2">
                                    <span class="bg-emerald-500 text-white text-xs px-3 py-1 rounded-full">4
                                        compartiments</span>
                                    <span class="bg-emerald-500 text-white text-xs px-3 py-1 rounded-full">Guide
                                        inclus</span>
                                </div>
                            </div>
                        </div>

                        <div class="space-y-4">
                            <h3 class="text-xl font-bold text-gray-800"> Kit offert</h3>
                            <p class="text-gray-600 text-sm">Le kit de tri vous est offert. Vous bénéficierez de <strong
                                    class="text-emerald-600">15 jours d'essai gratuit</strong> après activation.</p>

                            <div class="bg-gray-50 rounded-xl p-4 space-y-2">
                                <div class="flex items-center gap-3">
                                    <span
                                        class="w-8 h-8 bg-emerald-100 rounded-full flex items-center justify-center text-emerald-600"></span>
                                    <span class="text-sm text-gray-700">Sacs de tri pour recyclables</span>
                                </div>
                                <div class="flex items-center gap-3">
                                    <span
                                        class="w-8 h-8 bg-emerald-100 rounded-full flex items-center justify-center text-emerald-600"></span>
                                    <span class="text-sm text-gray-700">Bio-seau pour déchets organiques</span>
                                </div>
                                <div class="flex items-center gap-3">
                                    <span
                                        class="w-8 h-8 bg-emerald-100 rounded-full flex items-center justify-center text-emerald-600"></span>
                                    <span class="text-sm text-gray-700">Guide de tri illustré</span>
                                </div>
                                <div class="flex items-center gap-3">
                                    <span
                                        class="w-8 h-8 bg-emerald-100 rounded-full flex items-center justify-center text-emerald-600"></span>
                                    <span class="text-sm text-gray-700">QR code d'activation</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Formulaire -->
                    <form method="POST" action="<?php echo e(route('kit.demander.post')); ?>" class="border-t pt-6">
                        <?php echo csrf_field(); ?>
                        <div class="grid md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-gray-700 text-sm font-medium mb-2">Type de kit</label>
                                <select name="type_kit"
                                    class="w-full border rounded-lg p-3 focus:ring-2 focus:ring-emerald-500" required>
                                    <option value="standard"> Standard - Kit de base</option>
                                    <option value="renforce"> Renforcé - Pour grandes familles</option>
                                    <option value="compact"> Compact - Pour petits espaces</option>
                                </select>
                            </div>
                            <div class="flex items-end">
                                <button type="submit"
                                    class="w-full bg-gradient-to-r from-emerald-500 to-emerald-600 text-white px-6 py-3 rounded-lg font-semibold hover:from-emerald-600 hover:to-emerald-700 transition shadow-md hover:shadow-lg">
                                    <i class="fas fa-check mr-2"></i> Confirmer ma demande
                                </button>
                            </div>
                        </div>
                    </form>

                    <!-- Info livraison -->
                    <div class="mt-6 bg-blue-50 border border-blue-200 rounded-xl p-4 flex items-start gap-3">
                        <span class="text-blue-500 text-xl"></span>
                        <div>
                            <p class="text-sm text-blue-800 font-medium">Livraison à domicile</p>
                            <p class="text-xs text-blue-600">Un collecteur vous contactera pour la livraison de votre kit.
                            </p>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.menage', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\gestion-dechets-dakar\resources\views/kit/demander.blade.php ENDPATH**/ ?>