<!-- Modal d'affectation d'un collecteur à une commande -->
<div id="affectationModal" class="fixed inset-0 bg-gray-900/50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-xl shadow-2xl p-6 max-w-2xl w-full mx-4 max-h-[90vh] overflow-y-auto">
        <!-- En-tête -->
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-bold text-gray-800 flex items-center gap-2">
                <i class="fas fa-user-plus text-purple-500"></i> Affecter un collecteur
            </h2>
            <button onclick="closeAffectationModal()" class="text-gray-400 hover:text-gray-600 transition">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>

        <!-- Informations commande -->
        <div id="commandeInfo" class="bg-gray-50 p-4 rounded-lg mb-4">
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <p class="text-xs text-gray-500">Commande</p>
                    <p class="font-semibold">#<span id="commandeId">-</span></p>
                </div>
                <div>
                    <p class="text-xs text-gray-500">Partenaire</p>
                    <p class="font-semibold" id="commandeClient">-</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500">Type</p>
                    <p class="font-semibold" id="commandeType">-</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500">Quantité</p>
                    <p class="font-semibold" id="commandeQuantite">-</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500">Montant</p>
                    <p class="font-semibold text-emerald-600" id="commandeMontant">-</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500"> Quartier</p>
                    <p class="font-semibold text-blue-600" id="commandeQuartier">-</p>
                </div>
            </div>
        </div>

        <!-- Options de filtrage -->
        <div class="flex justify-between items-center mb-3">
            <h3 class="font-semibold text-gray-700 flex items-center gap-2">
                <i class="fas fa-users text-emerald-500"></i> Collecteurs disponibles
                <span id="nbCollecteurs" class="text-xs text-gray-400 font-normal"></span>
            </h3>
            <div>
                <button id="voirTousBtn" class="text-blue-500 hover:text-blue-700 text-sm font-medium hidden">
                    <i class="fas fa-expand mr-1"></i> Voir tous
                </button>
                <button id="voirZoneBtn" class="text-emerald-500 hover:text-emerald-700 text-sm font-medium hidden">
                    <i class="fas fa-filter mr-1"></i> Voir ma zone
                </button>
            </div>
        </div>

        <!-- Message zone vide -->
        <div id="zoneVideMessage"
            class="hidden bg-yellow-50 border border-yellow-200 rounded-lg p-3 mb-3 text-sm text-yellow-700">
            <i class="fas fa-exclamation-triangle mr-2"></i>
            Le partenaire est dans <strong id="quartierPartenaireMsg">-</strong> mais aucune zone ne couvre ce quartier.
            <br>Cliquez sur <strong>"Voir tous"</strong> pour afficher tous les collecteurs disponibles.
        </div>

        <!-- Liste des collecteurs -->
        <div id="collecteursList" class="space-y-3">
            <div class="text-center py-8 text-gray-500">
                <i class="fas fa-spinner fa-spin text-2xl mr-2"></i>
                Chargement des collecteurs disponibles...
            </div>
        </div>

        <!-- Actions -->
        <div class="flex gap-3 border-t pt-4 mt-4">
            <button onclick="closeAffectationModal()" class="btn-gray flex-1">Annuler</button>
            <button onclick="rafraichirCollecteurs()" class="btn-primary flex-1">
                <i class="fas fa-sync-alt mr-1"></i> Rafraîchir
            </button>
        </div>
    </div>
</div>

<!-- JavaScript -->
<script>
    let currentCommandeId = null;
    let currentVoirTous = false;

    function openAffectationModal(commandeId) {
        currentCommandeId = commandeId;
        currentVoirTous = false;

        const modal = document.getElementById('affectationModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');

        document.getElementById('voirTousBtn').classList.add('hidden');
        document.getElementById('voirZoneBtn').classList.add('hidden');
        document.getElementById('zoneVideMessage').classList.add('hidden');

        document.getElementById('collecteursList').innerHTML = `
            <div class="text-center py-8 text-gray-500">
                <i class="fas fa-spinner fa-spin text-2xl mr-2"></i>
                Chargement des collecteurs disponibles...
            </div>
        `;

        // Charger les infos de la commande
        fetch(`/admin/commandes/${commandeId}/infos`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('commandeId').textContent = data.id;
                document.getElementById('commandeClient').textContent = data.partenaire;
                document.getElementById('commandeType').textContent = data.categorie;
                document.getElementById('commandeQuantite').textContent = data.quantite + ' kg';
                document.getElementById('commandeMontant').textContent = data.montant + ' FCFA';
                document.getElementById('commandeQuartier').textContent = data.quartier || 'Non renseigné';
                document.getElementById('quartierPartenaireMsg').textContent = data.quartier || 'Non renseigné';
            })
            .catch(error => console.error('Erreur chargement infos:', error));

        // Charger les collecteurs
        chargerCollecteurs(commandeId, false);
    }

    function chargerCollecteurs(commandeId, tous = false) {
        currentVoirTous = tous;
        const listContainer = document.getElementById('collecteursList');

        listContainer.innerHTML = `
            <div class="text-center py-8 text-gray-500">
                <i class="fas fa-spinner fa-spin text-2xl mr-2"></i>
                Chargement des collecteurs...
            </div>
        `;

        const url = `/admin/commandes/${commandeId}/collecteurs-disponibles?tous=${tous ? 1 : 0}`;

        fetch(url)
            .then(response => response.json())
            .then(data => {
                if (data.zone_vide) {
                    document.getElementById('zoneVideMessage').classList.remove('hidden');
                    document.getElementById('voirTousBtn').classList.remove('hidden');
                    document.getElementById('nbCollecteurs').textContent = '';
                } else {
                    document.getElementById('zoneVideMessage').classList.add('hidden');
                    if (data.nb_disponibles === 0 && !tous) {
                        document.getElementById('voirTousBtn').classList.remove('hidden');
                    } else {
                        document.getElementById('voirTousBtn').classList.add('hidden');
                    }
                }

                if (tous) {
                    document.getElementById('voirZoneBtn').classList.remove('hidden');
                } else {
                    document.getElementById('voirZoneBtn').classList.add('hidden');
                }

                const nb = data.collecteurs.length;
                document.getElementById('nbCollecteurs').textContent = nb > 0 ?
                    `(${nb} disponible${nb > 1 ? 's' : ''})` : '';

                if (data.collecteurs.length === 0) {
                    let message = '';
                    if (tous) {
                        message = 'Aucun collecteur disponible pour le moment.';
                    } else if (data.zone_vide) {
                        message = 'Aucune zone ne couvre ce quartier. Cliquez sur "Voir tous".';
                    } else {
                        message = 'Aucun collecteur disponible dans cette zone.';
                    }
                    listContainer.innerHTML = `
                        <div class="text-center py-8 text-gray-500 bg-gray-50 rounded-lg">
                            <i class="fas fa-user-slash text-4xl mb-2 block"></i>
                            <p>${message}</p>
                            ${!tous && !data.zone_vide ? '<p class="text-xs text-gray-400 mt-1">Cliquez sur "Voir tous" pour voir tous les collecteurs.</p>' : ''}
                        </div>
                    `;
                    return;
                }

                let html = '';
                data.collecteurs.forEach(collecteur => {
                    const zone = collecteur.zone_nom || 'Non définie';
                    const matricule = collecteur.matricule || 'N/A';
                    const telephone = collecteur.user.telephone || 'Non renseigné';
                    const nomComplet = collecteur.user.prenom + ' ' + collecteur.user.nom;
                    const activites = collecteur.activites_aujourdhui || 0;

                    let badgeActivite = '';
                    if (activites === 0) {
                        badgeActivite =
                            '<span class="text-xs text-emerald-600 bg-emerald-100 px-2 py-0.5 rounded-full">✅ Libre</span>';
                    } else if (activites < 3) {
                        badgeActivite =
                            '<span class="text-xs text-yellow-600 bg-yellow-100 px-2 py-0.5 rounded-full">⚠️ Partiel</span>';
                    } else {
                        badgeActivite =
                            '<span class="text-xs text-red-600 bg-red-100 px-2 py-0.5 rounded-full">❌ Occupé</span>';
                    }

                    // Vérifier si le collecteur est dans la zone du partenaire
                    let zoneMatch = false;
                    if (collecteur.zones && data.zone_partenaire) {
                        zoneMatch = collecteur.zones.some(z =>
                            z.quartiers && z.quartiers.includes(data.zone_partenaire)
                        );
                    }

                    html += `
                        <div class="flex flex-col md:flex-row justify-between items-start md:items-center p-3 bg-gray-50 rounded-lg hover:bg-emerald-50 transition border-l-4 ${zoneMatch ? 'border-emerald-500' : 'border-blue-400'}">
                            <div class="mb-3 md:mb-0">
                                <p class="font-medium text-gray-800">${nomComplet}</p>
                                <p class="text-sm text-gray-500"><i class="fas fa-phone mr-1"></i> ${telephone}</p>
                                <p class="text-xs text-gray-400">
                                    <i class="fas fa-map-marker-alt mr-1"></i> Zone : ${zone}
                                    - <i class="fas fa-id-card mr-1"></i> Matricule : ${matricule}
                                </p>
                                <div class="mt-1 flex flex-wrap items-center gap-2">
                                    ${badgeActivite}
                                    <span class="text-xs text-gray-400">(${activites} activité${activites > 1 ? 's' : ''} aujourd'hui)</span>
                                    ${zoneMatch ? '<span class="text-xs text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-full">📌 Dans la zone</span>' : '<span class="text-xs text-blue-500 bg-blue-50 px-2 py-0.5 rounded-full">📍 Hors zone</span>'}
                                </div>
                            </div>
                            <form method="POST" action="/admin/commandes/${commandeId}/affecter" class="w-full md:w-auto">
                                @csrf
                                <input type="hidden" name="collecteur_id" value="${collecteur.id}">
                                <button type="submit"
                                        onclick="return confirm('Affecter ${nomComplet} à la commande #${commandeId} ?')"
                                        class="w-full md:w-auto bg-purple-500 hover:bg-purple-600 text-white px-4 py-2 rounded-lg text-sm transition font-medium">
                                    <i class="fas fa-user-check mr-1"></i> Affecter
                                </button>
                            </form>
                        </div>
                    `;
                });

                listContainer.innerHTML = html;
            })
            .catch(error => {
                console.error('Erreur:', error);
                listContainer.innerHTML = `
                    <div class="text-center py-8 text-red-500 bg-red-50 rounded-lg">
                        <i class="fas fa-exclamation-circle text-4xl mb-2 block"></i>
                        <p>Erreur lors du chargement des collecteurs.</p>
                        <button onclick="chargerCollecteurs(${commandeId}, ${tous})" class="mt-2 bg-blue-500 text-white px-4 py-1 rounded text-sm hover:bg-blue-600">Réessayer</button>
                    </div>
                `;
            });
    }

    function rafraichirCollecteurs() {
        if (currentCommandeId) {
            chargerCollecteurs(currentCommandeId, currentVoirTous);
        }
    }

    document.getElementById('voirTousBtn').addEventListener('click', function() {
        if (currentCommandeId) chargerCollecteurs(currentCommandeId, true);
    });

    document.getElementById('voirZoneBtn').addEventListener('click', function() {
        if (currentCommandeId) chargerCollecteurs(currentCommandeId, false);
    });

    function closeAffectationModal() {
        const modal = document.getElementById('affectationModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        currentCommandeId = null;
        document.getElementById('voirTousBtn').classList.add('hidden');
        document.getElementById('voirZoneBtn').classList.add('hidden');
        document.getElementById('zoneVideMessage').classList.add('hidden');
    }

    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('affectationModal').addEventListener('click', function(e) {
            if (e.target === this) closeAffectationModal();
        });
    });
</script>
