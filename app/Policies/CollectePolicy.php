<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Collecte;
use Illuminate\Auth\Access\HandlesAuthorization;

class CollectePolicy
{
    use HandlesAuthorization;

    public function before(User $user, $ability)
    {
        if ($user->isAdmin()) {
            return true;
        }
    }

    /**
     * Voir une collecte spécifique
     */
    public function view(User $user, Collecte $collecte)
    {
        // Le ménage/entreprise propriétaire peut voir sa collecte
        if ($user->id === $collecte->user_id) {
            return true;
        }

        // Le collecteur affecté peut voir la collecte
        if ($collecte->collecteur_id && $user->collecteur && $user->collecteur->id === $collecte->collecteur_id) {
            return true;
        }

        // Le partenaire qui a reçu les déchets peut voir
        if ($collecte->partenaire_id && $user->id === $collecte->partenaire_id) {
            return true;
        }

        return false;
    }

    /**
     * Voir toutes les collectes (admin uniquement)
     */
    public function viewAny(User $user)
    {
        return $user->isAdmin();
    }

    /**
     * Demander une nouvelle collecte
     */
    public function create(User $user)
    {
        return $user->isMenage() || $user->isEntreprise();
    }

    /**
     * Modifier une collecte
     */
    public function update(User $user, Collecte $collecte)
    {
        // Seul un collecteur affecté peut modifier (enregistrer une collecte)
        if ($user->isCollecteur() && $collecte->collecteur_id === $user->collecteur->id) {
            return true;
        }

        return false;
    }

    /**
     * Supprimer une collecte
     */
    public function delete(User $user, Collecte $collecte)
    {
        // Seul un admin peut supprimer une collecte
        return $user->isAdmin();
    }
}
