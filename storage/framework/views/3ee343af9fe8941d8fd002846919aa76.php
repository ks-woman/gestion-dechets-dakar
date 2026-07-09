<?php $__env->startSection('title', 'Scanner un kit'); ?>

<?php $__env->startSection('content'); ?>
    <div class="bg-white rounded-xl shadow-soft p-6 max-w-md mx-auto">
        <h1 class="text-2xl font-bold text-gray-800 mb-4"> Scanner un kit</h1>

        <div id="reader" style="width:100%; max-width:500px; margin:0 auto;"></div>
        <div id="resultat" class="mt-4 hidden"></div>

        <div class="mt-6 pt-4 border-t">
            <p class="text-gray-500 text-sm text-center">Ou saisissez manuellement :</p>
            <div class="flex gap-2 mt-2 justify-center">
                <input type="text" id="code_manuel" class="border rounded-lg p-2 w-64 focus:ring-2 focus:ring-emerald-500"
                    placeholder="KIT-ABC123">
                <button onclick="activerKitManuel()" class="btn-primary">Activer</button>
            </div>
        </div>
        <div class="mt-4 text-center"><a href="<?php echo e(route('collecteur.dashboard')); ?>" class="text-gray-500">← Retour</a></div>
    </div>

    <script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
    <script>
        let html5QrCode;

        function onScanSuccess(decodedText) {
            html5QrCode.stop().then(() => activerKit(decodedText));
        }
        document.addEventListener('DOMContentLoaded', function() {
            html5QrCode = new Html5Qrcode("reader");
            html5QrCode.start({
                facingMode: "environment"
            }, {
                fps: 10,
                qrbox: {
                    width: 250,
                    height: 250
                }
            }, onScanSuccess);
        });

        function activerKit(code) {
            const div = document.getElementById('resultat');
            div.classList.remove('hidden');
            div.innerHTML = '<div class="alert-info"> Activation...</div>';
            fetch('<?php echo e(route('collecteur.kit.activer')); ?>', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
                    },
                    body: JSON.stringify({
                        code_kit: code
                    })
                })
                .then(r => r.json())
                .then(data => {
                    if (data.success) {
                        div.innerHTML = `<div class="alert-success"> ${data.message}</div>`;
                        setTimeout(() => window.location.href = '<?php echo e(route('collecteur.dashboard')); ?>', 2000);
                    } else {
                        div.innerHTML = `<div class="alert-danger"> ${data.error}</div>`;
                    }
                });
        }

        function activerKitManuel() {
            let code = document.getElementById('code_manuel').value.trim();
            if (!code) return alert('Saisissez un code.');
            activerKit(code);
        }
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.collecteur', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\gestion-dechets-dakar\resources\views/collecteur/scanner.blade.php ENDPATH**/ ?>