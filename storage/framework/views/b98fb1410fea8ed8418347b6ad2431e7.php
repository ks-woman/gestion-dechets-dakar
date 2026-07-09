<?php $__env->startSection('title', 'Tableau de bord'); ?>

<?php $__env->startSection('content'); ?>
    <div class="space-y-6">
        <div class="bg-gradient-to-r from-emerald-600 to-emerald-800 rounded-xl shadow-soft p-6 text-white">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-bold">Bonjour <?php echo e(auth()->user()->prenom); ?> !</h1>
                    <p class="text-emerald-100 mt-1">Bienvenue sur votre espace de gestion des déchets</p>
                </div>
                <div class="text-5xl"></div>
            </div>
        </div>

        <!-- Kit de tri -->
        <?php if(auth()->user()->kitTri): ?>
            <?php
                $kit = auth()->user()->kitTri;
                $qrCode = $kit->code_qr
                    ? \SimpleSoftwareIO\QrCode\Facades\QrCode::size(200)->generate($kit->code_qr)
                    : null;
            ?>
            <div
                class="bg-white rounded-xl shadow-soft p-6 border-l-4 <?php if($kit->statut == 'en_attente'): ?> border-yellow-500 <?php elseif($kit->statut == 'actif'): ?> border-emerald-500 <?php else: ?> border-gray-400 <?php endif; ?>">
                <div class="flex justify-between items-start">
                    <div>
                        <h3 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                            Mon kit de tri
                            <span
                                class="text-xs font-normal px-3 py-1 rounded-full <?php if($kit->statut == 'en_attente'): ?> bg-yellow-100 text-yellow-800 <?php elseif($kit->statut == 'actif'): ?> bg-emerald-100 text-emerald-700 <?php else: ?> bg-gray-100 text-gray-600 <?php endif; ?>">
                                <?php if($kit->statut == 'en_attente'): ?>
                                    En attente
                                <?php elseif($kit->statut == 'actif'): ?>
                                    Actif
                                <?php else: ?>
                                    <?php echo e($kit->statut); ?>

                                <?php endif; ?>
                            </span>
                        </h3>
                        <p class="text-sm text-gray-600 mt-1"><strong>Type :</strong>
                            <?php echo e(ucfirst($kit->type_kit ?? 'Standard')); ?></p>
                        <p class="text-sm text-gray-600"><strong>Code :</strong> <span
                                class="font-mono text-xs bg-gray-100 px-2 py-1 rounded"><?php echo e($kit->code_qr); ?></span></p>
                        <?php if($kit->date_distribution): ?>
                            <p class="text-sm text-gray-600"><strong>Livré le :</strong>
                                <?php echo e(\Carbon\Carbon::parse($kit->date_distribution)->format('d/m/Y')); ?></p>
                        <?php endif; ?>
                    </div>
                    <?php if($qrCode): ?>
                        <div class="bg-white p-2 rounded-lg shadow-sm border"><?php echo $qrCode; ?></div>
                    <?php endif; ?>
                </div>
                <?php if($kit->statut == 'en_attente'): ?>
                    <div class="mt-3 bg-yellow-50 border border-yellow-200 rounded-lg p-3 flex items-start gap-2">
                        <span class="text-yellow-600"></span>
                        <div>
                            <p class="text-sm text-yellow-800 font-medium">Votre kit est en préparation</p>
                            <p class="text-xs text-yellow-600">Un collecteur vous contactera pour la livraison.</p>
                        </div>
                    </div>
                <?php elseif($kit->statut == 'actif'): ?>
                    <div class="mt-3 bg-emerald-50 border border-emerald-200 rounded-lg p-3 flex items-start gap-2">
                        <span class="text-emerald-600"></span>
                        <div>
                            <p class="text-sm text-emerald-800 font-medium">Votre kit est actif !</p>
                            <p class="text-xs text-emerald-600">Vous pouvez maintenant trier vos déchets et demander des
                                collectes.</p>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        <?php else: ?>
            <div class="bg-white rounded-xl shadow-soft p-6 border-l-4 border-gray-300">
                <div class="flex justify-between items-center">
                    <div>
                        <h3 class="text-lg font-bold text-gray-800"> Mon kit de tri</h3><span class="badge-gray">Non
                            demandé</span>
                        <p class="text-sm text-gray-500 mt-2">Vous n'avez pas encore demandé votre kit de tri.</p>
                    </div>
                    <a href="<?php echo e(route('kit.demander')); ?>" class="btn-primary"><i class="fas fa-plus mr-1"></i> Demander</a>
                </div>
            </div>
        <?php endif; ?>

        <!-- Mode d'emploi -->
        <?php if(auth()->user()->kitTri && auth()->user()->kitTri->statut == 'actif'): ?>
            <div class="bg-white rounded-xl shadow-soft p-6 border-l-4 border-emerald-500">
                <div class="flex justify-between items-start">
                    <div>
                        <h3 class="text-lg font-bold text-gray-800 flex items-center gap-2"> Mode d'emploi <span
                                class="badge-success">Guide de tri</span></h3>
                        <p class="text-sm text-gray-500">Déposez vos déchets dans le bon compartiment.</p>
                    </div>
                    <button onclick="toggleModeEmploi()"
                        class="text-emerald-600 hover:text-emerald-800 text-sm font-medium">Voir / Cacher</button>
                </div>
                <div id="modeEmploiContent" class="mt-4 hidden">
                    <div class="flex flex-col md:flex-row gap-6 items-center">
                        <div class="flex-1"><img src="<?php echo e(asset('images/prototypePoub.jpeg')); ?>" alt="Prototype"
                                class="w-full max-w-sm rounded-lg shadow-md border"></div>
                        <div class="flex-1 space-y-3">
                            <div class="flex items-center gap-3 p-2 bg-blue-50 rounded-lg border-l-4 border-blue-500"><span
                                    class="text-2xl"></span>
                                <div>
                                    <p class="font-semibold text-blue-800">Plastiques & Métaux</p>
                                    <p class="text-sm text-gray-600">Bouteilles, canettes</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-3 p-2 bg-green-50 rounded-lg border-l-4 border-green-500">
                                <span class="text-2xl"></span>
                                <div>
                                    <p class="font-semibold text-green-800">Organiques</p>
                                    <p class="text-sm text-gray-600">Restes alimentaires</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-3 p-2 bg-yellow-50 rounded-lg border-l-4 border-yellow-500">
                                <span class="text-2xl"></span>
                                <div>
                                    <p class="font-semibold text-yellow-800">Papiers & Cartons</p>
                                    <p class="text-sm text-gray-600">Journaux, cartons</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-3 p-2 bg-gray-50 rounded-lg border-l-4 border-gray-500"><span
                                    class="text-2xl"></span>
                                <div>
                                    <p class="font-semibold text-gray-800">Autres déchets</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mt-4 bg-emerald-50 border border-emerald-200 rounded-lg p-3 text-sm text-emerald-700">
                        Astuce : Rincez les emballages.</div>
                </div>
            </div>
            <script>
                function toggleModeEmploi() {
                    document.getElementById('modeEmploiContent').classList.toggle('hidden');
                }
            </script>
        <?php endif; ?>

        <!-- Stats -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="card">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-gray-500 text-sm">Points</p>
                        <p class="text-2xl font-bold text-emerald-600"><?php echo e(auth()->user()->score_total); ?></p>
                        <p class="text-xs text-gray-400">Niveau <?php echo e(auth()->user()->niveau); ?></p>
                    </div><i class="fas fa-star text-3xl text-emerald-500"></i>
                </div>
            </div>
            <div class="card">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-gray-500 text-sm">Statut</p>
                        <p class="text-xl font-bold text-emerald-600">
                            <?php if(auth()->user()->estEnPeriodeEssai()): ?>
                                Essai gratuit
                            <?php elseif(auth()->user()->estAbonneActif()): ?>
                                Abonné
                            <?php else: ?>
                                <?php echo e(auth()->user()->statut_compte); ?>

                            <?php endif; ?>
                        </p>
                    </div><i class="fas fa-chart-line text-3xl text-emerald-500"></i>
                </div>
            </div>
            <div class="card">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-gray-500 text-sm">Collectes</p>
                        <p class="text-2xl font-bold text-emerald-600"><?php echo e($totalCollectes ?? 0); ?></p>
                    </div><i class="fas fa-truck text-3xl text-emerald-500"></i>
                </div>
            </div>
            <div class="card">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-gray-500 text-sm">Prochaine</p>
                        <p class="text-sm font-medium text-emerald-600">
                            <?php if(isset($prochaineCollecte)): ?>
                                <?php echo e(\Carbon\Carbon::parse($prochaineCollecte->date_collecte)->format('d/m/Y')); ?>

                            <?php else: ?>
                                Aucune
                            <?php endif; ?>
                        </p>
                    </div><i class="fas fa-calendar text-3xl text-emerald-500"></i>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
            <?php if(auth()->user()->kitTri && auth()->user()->kitTri->statut == 'actif'): ?>
                <a href="<?php echo e(route('collecte.demander')); ?>" class="btn-primary text-center"><i
                        class="fas fa-truck mr-2"></i> Demander</a>
            <?php else: ?>
                <a href="<?php echo e(route('kit.demander')); ?>" class="btn-primary text-center"><i class="fas fa-box mr-2"></i>
                    Activer</a>
            <?php endif; ?>
            <a href="<?php echo e(route('collectes')); ?>" class="btn-primary text-center"><i class="fas fa-history mr-2"></i>
                Historique</a>
            <a href="<?php echo e(route('statistiques')); ?>" class="btn-primary text-center"><i class="fas fa-chart-bar mr-2"></i>
                Statistiques</a>
            <a href="<?php echo e(route('menage.preferences')); ?>" class="btn-primary text-center"><i
                    class="fas fa-sliders-h mr-2"></i> Préférences</a>
        </div>

        <!-- Dernières collectes -->
        <div class="bg-white rounded-xl shadow-soft p-6">
            <h2 class="text-lg font-semibold text-gray-800 flex items-center gap-2"><i
                    class="fas fa-clock text-emerald-500"></i> Dernières collectes</h2>
            <?php if(isset($collectes) && $collectes->count() > 0): ?>
                <?php $__currentLoopData = $collectes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $collecte): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg mb-2">
                        <div>
                            <p class="font-medium"><?php echo e(\Carbon\Carbon::parse($collecte->date_collecte)->format('d/m/Y')); ?>

                            </p>
                            <p class="text-sm text-gray-500">
                                <?php echo e($collecte->poids_recyclable + $collecte->poids_organique + $collecte->poids_residuel); ?>

                                kg</p>
                        </div>
                        <div class="text-right"><span class="badge-success">+<?php echo e($collecte->points_obtenus); ?> pts</span>
                            <?php if($collecte->statut == 'realisee'): ?>
                                <p class="text-xs text-emerald-600"> Réalisée</p>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php else: ?>
                <div class="text-center py-8 text-gray-500"><i class="fas fa-box-open text-4xl mb-2 block"></i>
                    <p>Aucune collecte</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.menage', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\gestion-dechets-dakar\resources\views/menage/dashboard.blade.php ENDPATH**/ ?>