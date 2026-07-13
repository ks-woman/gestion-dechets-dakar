<?php $__env->startSection('title', 'Mon abonnement'); ?>

<?php $__env->startSection('content'); ?>
    <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
        <div class="bg-gradient-to-r from-emerald-600 to-emerald-800 px-6 py-6 text-white">
            <div class="flex items-center gap-4">
                <div class="text-4xl"></div>
                <div>
                    <h1 class="text-2xl font-bold">Mon abonnement</h1>
                    <p class="text-emerald-100 text-sm">Gérez votre abonnement et vos paiements</p>
                </div>
            </div>
        </div>

        <div class="p-6">
            <!-- Statut actuel -->
            <div class="mb-6">
                <div class="bg-gray-50 rounded-xl p-4 border">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-500">Statut actuel</p>
                            <p class="text-xl font-bold">
                                <?php if($abonnement && $abonnement->estActif()): ?>
                                    <span class="text-emerald-600"> Actif</span>
                                <?php elseif($abonnement && $abonnement->estEnEssai()): ?>
                                    <span class="text-blue-600"> Période d'essai</span>
                                <?php else: ?>
                                    <span class="text-red-600"> Inactif</span>
                                <?php endif; ?>
                            </p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm text-gray-500">Prochain paiement</p>
                            <p class="font-semibold">
                                <?php if($abonnement && $abonnement->date_debut_abonnement): ?>
                                    <?php echo e($abonnement->date_debut_abonnement->addMonth()->format('d/m/Y')); ?>

                                <?php else: ?>
                                    <span class="text-gray-400">--</span>
                                <?php endif; ?>
                            </p>
                        </div>
                    </div>

                    <?php if($abonnement && $abonnement->estEnEssai()): ?>
                        <div class="mt-3 bg-blue-50 border border-blue-200 rounded-lg p-3">
                            <p class="text-sm text-blue-700">
                                Il vous reste <strong><?php echo e(now()->diffInDays($abonnement->date_fin_essai)); ?></strong> jours
                                d'essai gratuit.
                                <br>Abonnez-vous pour continuer le service.
                            </p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Offre d'abonnement -->
            <div class="grid md:grid-cols-2 gap-6 mb-8">
                <div class="border-2 border-emerald-500 rounded-2xl p-6 bg-emerald-50">
                    <h3 class="text-xl font-bold text-gray-800"> Abonnement mensuel</h3>
                    <p class="text-3xl font-bold text-emerald-600 mt-2">5 000 FCFA</p>
                    <p class="text-sm text-gray-500">par mois</p>
                    <ul class="mt-4 space-y-2 text-sm">
                        <li class="flex items-center gap-2"><span class="text-emerald-500"></span> Collectes illimitées
                        </li>
                        <li class="flex items-center gap-2"><span class="text-emerald-500"></span> Points de fidélité</li>
                        <li class="flex items-center gap-2"><span class="text-emerald-500"></span> Accès prioritaire</li>
                        <li class="flex items-center gap-2"><span class="text-emerald-500"></span> Réductions exclusives
                        </li>
                    </ul>
                    <?php if(!($abonnement && $abonnement->estActif())): ?>
                        <form method="POST" action="<?php echo e(route('abonnement.souscrire')); ?>" class="mt-4">
                            <?php echo csrf_field(); ?>
                            <button type="submit"
                                class="w-full bg-emerald-500 text-white py-2 rounded-lg hover:bg-emerald-600 transition">
                                <i class="fas fa-credit-card mr-2"></i> S'abonner maintenant
                            </button>
                        </form>
                    <?php else: ?>
                        <div class="mt-4 p-3 bg-emerald-100 rounded-lg text-center text-emerald-800">
                            Vous êtes abonné
                        </div>
                    <?php endif; ?>
                </div>

                <div class="border-2 border-gray-200 rounded-2xl p-6">
                    <h3 class="text-xl font-bold text-gray-800"> Pourquoi s'abonner ?</h3>
                    <ul class="mt-4 space-y-3 text-sm">
                        <li class="flex items-start gap-3">
                            <span class="text-emerald-500"></span>
                            <div><strong>Collectes à volonté</strong><br><span class="text-gray-500">Plus de limite,
                                    demandez autant de collectes que vous voulez.</span></div>
                        </li>
                        <li class="flex items-start gap-3">
                            <span class="text-emerald-500"></span>
                            <div><strong>Récompenses exclusives</strong><br><span class="text-gray-500">Accédez à des offres
                                    réservées aux abonnés.</span></div>
                        </li>
                        <li class="flex items-start gap-3">
                            <span class="text-emerald-500"></span>
                            <div><strong>Service prioritaire</strong><br><span class="text-gray-500">Vos collectes sont
                                    traitées en priorité.</span></div>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Derniers paiements -->
            <div class="border-t pt-6">
                <h3 class="font-semibold text-gray-800 mb-4"> Historique des paiements</h3>
                <?php if($paiements->count() > 0): ?>
                    <div class="space-y-2">
                        <?php $__currentLoopData = $paiements; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $paiement): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                                <div>
                                    <p class="font-medium"><?php echo e($paiement->date_paiement->format('d/m/Y')); ?></p>
                                    <p class="text-xs text-gray-500"><?php echo e($paiement->mode_paiement); ?></p>
                                </div>
                                <div class="text-right">
                                    <p class="font-semibold text-emerald-600"><?php echo e(number_format($paiement->montant, 0)); ?>

                                        FCFA</p>
                                    <span class="text-xs text-emerald-600"> Validé</span>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                    <div class="mt-3">
                        <a href="<?php echo e(route('abonnement.historique')); ?>" class="text-emerald-600 hover:underline text-sm">
                            Voir tout l'historique →
                        </a>
                    </div>
                <?php else: ?>
                    <div class="text-center py-4 text-gray-500">
                        <p>Aucun paiement enregistré.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.menage', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\gestion-dechets-dakar\resources\views/abonnement/index.blade.php ENDPATH**/ ?>