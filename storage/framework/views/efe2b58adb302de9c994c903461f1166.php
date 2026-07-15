<?php
    $user = auth()->user();
    $layout = $user->isAdmin()
        ? 'layouts.admin'
        : ($user->isCollecteur()
            ? 'layouts.collecteur'
            : ($user->isPartenaire()
                ? 'layouts.partenaire'
                : 'layouts.menage'));
?>


<?php $__env->startSection('title', 'Mes notifications'); ?>

<?php $__env->startSection('content'); ?>
    <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
        <div
            class="bg-gradient-to-r from-emerald-600 to-emerald-800 px-6 py-6 text-white flex justify-between items-center flex-wrap gap-4">
            <div class="flex items-center gap-4">
                <div class="text-4xl"></div>
                <div>
                    <h1 class="text-2xl font-bold">Mes notifications</h1>
                    <p class="text-emerald-100 text-sm"><?php echo e($nonLues ?? 0); ?> non lue(s) sur <?php echo e($notifications->total()); ?></p>
                </div>
            </div>
            <div class="flex gap-2">
                <?php if($nonLues > 0): ?>
                    <form method="POST" action="<?php echo e(route('notifications.mark-all-read')); ?>">
                        <?php echo csrf_field(); ?>
                        <button type="submit"
                            class="bg-white/20 hover:bg-white/30 text-white px-4 py-2 rounded-lg text-sm transition">
                            <i class="fas fa-check-double mr-1"></i> Tout marquer comme lu
                        </button>
                    </form>
                <?php endif; ?>
            </div>
        </div>

        <div class="p-6">
            <?php if($notifications->count() > 0): ?>
                <div class="space-y-3">
                    <?php $__currentLoopData = $notifications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $notification): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div
                            class="flex items-start justify-between p-4 bg-gray-50 rounded-lg border hover:shadow-md transition
                        <?php echo e($notification->est_lu ? 'border-gray-200' : 'border-l-4 border-emerald-500'); ?>">
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2 flex-wrap">
                                    <h3 class="font-semibold text-gray-800"><?php echo e($notification->titre); ?></h3>
                                    <?php if(!$notification->est_lu): ?>
                                        <span
                                            class="bg-emerald-100 text-emerald-800 text-xs px-2 py-0.5 rounded-full">Nouveau</span>
                                    <?php endif; ?>
                                </div>
                                <p class="text-sm text-gray-600 mt-1"><?php echo e($notification->message); ?></p>
                                <p class="text-xs text-gray-400 mt-1"><?php echo e($notification->created_at->diffForHumans()); ?></p>
                            </div>
                            <div class="flex items-center gap-2 flex-shrink-0 ml-4">
                                <?php if(!$notification->est_lu): ?>
                                    <form method="POST" action="<?php echo e(route('notifications.mark-read', $notification->id)); ?>">
                                        <?php echo csrf_field(); ?>
                                        <button type="submit" class="text-emerald-600 hover:text-emerald-800 text-sm"
                                            title="Marquer comme lu">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    </form>
                                <?php endif; ?>
                                <?php if($notification->lien): ?>
                                    <a href="<?php echo e($notification->lien); ?>" class="text-blue-500 hover:text-blue-700 text-sm"
                                        title="Voir">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                <?php endif; ?>
                                <form method="POST" action="<?php echo e(route('notifications.destroy', $notification->id)); ?>"
                                    onsubmit="return confirm('Supprimer cette notification ?')">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="text-red-400 hover:text-red-600 text-sm"
                                        title="Supprimer">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
                <div class="mt-4">
                    <?php echo e($notifications->links()); ?>

                </div>
            <?php else: ?>
                <div class="text-center py-12 text-gray-500">
                    <i class="fas fa-bell-slash text-4xl mb-3 block"></i>
                    <p class="text-lg font-medium">Aucune notification</p>
                    <p class="text-sm">Vous serez notifié dès qu’un événement important se produira.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make($layout, array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\gestion-dechets-dakar\resources\views/notifications/index.blade.php ENDPATH**/ ?>