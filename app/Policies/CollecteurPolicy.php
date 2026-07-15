<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Collecteur;
use Illuminate\Auth\Access\HandlesAuthorization;

class CollecteurPolicy
{
    use HandlesAuthorization;

    public function before(User $user, $ability)
    {
        if ($user->isAdmin()) {
            return true;
        }
    }

    /**
     * Voir un collecteur
     */
    public function view(User $user, Collecteur $collecteur)
    {
        return $user->id === $collecteur->user_id;
    }

    /**
     * Modifier un collecteur
     */
    public function update(User $user, Collecteur $collecteur)
    {
        return $user->id === $collecteur->user_id;
    }

    /**
     * Voir la tournée
     */
    public function viewTournee(User $user)
    {
        return $user->isCollecteur() && $user->collecteur->disponibilite;
    }
}
