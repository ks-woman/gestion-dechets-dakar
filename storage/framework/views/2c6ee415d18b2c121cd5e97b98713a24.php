<!DOCTYPE html>
<html lang="fr" class="h-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $__env->yieldContent('title', 'Gestion Déchets Dakar'); ?></title>
    <?php echo app('Illuminate\Foundation\Vite')('resources/css/app.css'); ?>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>

<body class="h-full bg-gray-100">
    <div class="min-h-screen flex items-center justify-center bg-cover bg-center bg-no-repeat"
        style="background-image: url('<?php echo e(asset('images/arrierePlan.png')); ?>'); background-attachment: fixed;">
        <!-- Overlay sombre -->
        <div class="absolute inset-0 bg-black/60"></div>

        <div class="relative z-10 w-full max-w-md px-4">
            <?php echo $__env->yieldContent('content'); ?>
        </div>
    </div>
</body>

</html>
<?php /**PATH C:\wamp64\www\gestion-dechets-dakar\resources\views/layouts/auth.blade.php ENDPATH**/ ?>