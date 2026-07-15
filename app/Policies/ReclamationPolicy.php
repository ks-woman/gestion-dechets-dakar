<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Reclamation;
use Illuminate\Auth\Access\HandlesAuthorization;

class ReclamationPolicy
{
    use HandlesAuthorization;

    public function before(User $user, $ability)
    {
        if ($user->isAdmin()) {
            return true;
        }
    }

    /**
     * Voir une réclamation
     */
    public function view(User $user, Reclamation $reclamation)
    {
        return $user->id === $reclamation->user_id;
    }

    /**
     * Créer une réclamation
     */
    public function create(User $user)
    {
        return $user->isMenage() || $user->isEntreprise() || $user->isCollecteur();
    }

    /**
     * Répondre à une réclamation
     */
    public function repondre(User $user, Reclamation $reclamation)
    {
        return $user->isAdmin();
    }
}
