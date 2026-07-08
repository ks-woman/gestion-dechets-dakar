<?php $__env->startSection('content'); ?>
    <div class="max-w-md mx-auto bg-white p-6 rounded-lg shadow">
        <h2 class="text-2xl font-bold mb-6 text-center">Inscription - Entreprise</h2>

        <form method="POST" action="<?php echo e(route('register.entreprise')); ?>">
            <?php echo csrf_field(); ?>

            <div class="mb-4">
                <label class="block mb-2">Nom de l'entreprise</label>
                <input type="text" name="nom" value="<?php echo e(old('nom')); ?>" class="w-full border rounded p-2" required>
                <?php $__errorArgs = ['nom'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <div class="text-red-500 mt-1"><?php echo e($message); ?></div>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div class="mb-4">
                <label class="block mb-2">Nom du responsable</label>
                <input type="text" name="prenom" value="<?php echo e(old('prenom')); ?>" class="w-full border rounded p-2" required>
                <?php $__errorArgs = ['prenom'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <div class="text-red-500 mt-1"><?php echo e($message); ?></div>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div class="mb-4">
                <label class="block mb-2">Email</label>
                <input type="email" name="email" value="<?php echo e(old('email')); ?>" class="w-full border rounded p-2" required>
                <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <div class="text-red-500 mt-1"><?php echo e($message); ?></div>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div class="mb-4">
                <label class="block mb-2">Téléphone</label>
                <input type="tel" name="telephone" value="<?php echo e(old('telephone')); ?>" class="w-full border rounded p-2"
                    required>
                <?php $__errorArgs = ['telephone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <div class="text-red-500 mt-1"><?php echo e($message); ?></div>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div class="mb-4">
                <label class="block mb-2">Adresse</label>
                <textarea name="adresse" class="w-full border rounded p-2" required><?php echo e(old('adresse')); ?></textarea>
                <?php $__errorArgs = ['adresse'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <div class="text-red-500 mt-1"><?php echo e($message); ?></div>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <!-- ===== QUARTIER AVEC GÉOLOCALISATION ===== -->
            <div class="mb-4">
                <label class="block mb-2">Quartier</label>
                <input type="text" name="quartier" id="quartier" class="w-full border rounded p-2" required>
                <input type="hidden" name="latitude" id="latitude">
                <input type="hidden" name="longitude" id="longitude">
                <div id="geo_status" class="text-xs mt-1"></div>
                <p class="text-xs text-gray-500 mt-1">Ex: Yoff, Pikine, Guediawaye, Parcelles Assainies</p>
            </div>

            <div class="mb-4">
                <label class="block mb-2">Numéro registre de commerce</label>
                <input type="text" name="numero_registre_commerce" value="<?php echo e(old('numero_registre_commerce')); ?>"
                    class="w-full border rounded p-2" required>
                <?php $__errorArgs = ['numero_registre_commerce'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <div class="text-red-500 mt-1"><?php echo e($message); ?></div>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div class="mb-4">
                <label class="block mb-2">Type d'activité</label>
                <select name="type_activite" class="w-full border rounded p-2" required>
                    <option value="">Sélectionner</option>
                    <option value="restaurant">Restaurant</option>
                    <option value="hotel">Hôtel</option>
                    <option value="commerce">Commerce</option>
                    <option value="marche">Marché</option>
                    <option value="bureau">Bureau</option>
                    <option value="autre">Autre</option>
                </select>
                <?php $__errorArgs = ['type_activite'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <div class="text-red-500 mt-1"><?php echo e($message); ?></div>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div class="mb-4">
                <label class="block mb-2">Volume moyen de déchets (kg/semaine)</label>
                <input type="number" name="volume_moyen_dechet" value="<?php echo e(old('volume_moyen_dechet', 0)); ?>"
                    class="w-full border rounded p-2">
                <?php $__errorArgs = ['volume_moyen_dechet'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <div class="text-red-500 mt-1"><?php echo e($message); ?></div>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div class="mb-4">
                <label class="block mb-2">Mot de passe</label>
                <input type="password" name="mot_passe" class="w-full border rounded p-2" required>
                <?php $__errorArgs = ['mot_passe'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <div class="text-red-500 mt-1"><?php echo e($message); ?></div>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div class="mb-4">
                <label class="block mb-2">Confirmer le mot de passe</label>
                <input type="password" name="mot_passe_confirmation" class="w-full border rounded p-2" required>
            </div>

            <button type="submit" class="w-full bg-green-500 text-white p-2 rounded">S'inscrire</button>
        </form>

        <p class="mt-4 text-center">Déjà inscrit ? <a href="<?php echo e(route('login')); ?>" class="text-blue-500">Se connecter</a>
        </p>
    </div>

    <!-- Script de géolocalisation -->
    <script>
        document.getElementById('quartier').addEventListener('blur', function() {
            let quartier = this.value;
            let statusDiv = document.getElementById('geo_status');

            if (!quartier) {
                statusDiv.innerHTML = '';
                return;
            }

            statusDiv.innerHTML = ' Recherche du quartier...';
            statusDiv.style.color = 'blue';

            fetch(
                    `https://nominatim.openstreetmap.org/search?q=${encodeURIComponent(quartier)}, Dakar, Sénégal&format=json&limit=1`
                )
                .then(response => response.json())
                .then(data => {
                    if (data.length > 0) {
                        document.getElementById('latitude').value = data[0].lat;
                        document.getElementById('longitude').value = data[0].lon;
                        statusDiv.innerHTML = ' Quartier localisé avec succès !';
                        statusDiv.style.color = 'green';
                    } else {
                        statusDiv.innerHTML = ' Quartier non trouvé. Vérifiez l\'orthographe.';
                        statusDiv.style.color = 'red';
                    }
                })
                .catch(error => {
                    console.error('Erreur:', error);
                    statusDiv.innerHTML = ' Erreur de connexion. Réessayez.';
                    statusDiv.style.color = 'red';
                });
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\gestion-dechets-dakar\resources\views/auth/register-entreprise.blade.php ENDPATH**/ ?>