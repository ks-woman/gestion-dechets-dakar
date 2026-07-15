<?php

namespace App\Policies;

use App\Models\User;
use App\Models\KitTri;
use Illuminate\Auth\Access\HandlesAuthorization;

class KitTriPolicy
{
    use HandlesAuthorization;

    public function before(User $user, $ability)
    {
        if ($user->isAdmin()) {
            return true;
        }
    }

    /**
     * Voir un kit spécifique
     */
    public function view(User $user, KitTri $kit)
    {
        // Le propriétaire peut voir son kit
        if ($user->id === $kit->user_id) {
            return true;
        }

        // Le collecteur qui a activé le kit peut le voir
        if ($kit->collecteur_id && $user->collecteur && $user->collecteur->id === $kit->collecteur_id) {
            return true;
        }

        return false;
    }

    /**
     * Demander un kit
     */
    public function create(User $user)
    {
        return $user->isMenage() || $user->isEntreprise();
    }

    /**
     * Activer un kit (collecteur uniquement)
     */
    public function activer(User $user)
    {
        return $user->isCollecteur();
    }

    /**
     * Supprimer un kit
     */
    public function delete(User $user, KitTri $kit)
    {
        return $user->isAdmin();
    }
}
