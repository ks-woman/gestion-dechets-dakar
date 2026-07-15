<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Abonnement;
use Illuminate\Auth\Access\HandlesAuthorization;

class AbonnementPolicy
{
    use HandlesAuthorization;

    public function before(User $user, $ability)
    {
        if ($user->isAdmin()) {
            return true;
        }
    }

    /**
     * Voir son abonnement
     */
    public function view(User $user, Abonnement $abonnement)
    {
        return $user->id === $abonnement->user_id;
    }

    /**
     * Souscrire à un abonnement
     */
    public function create(User $user)
    {
        // Tout utilisateur authentifié peut souscrire
        // (sauf les admins qui ont déjà un abonnement spécial)
        return !$user->isAdmin();
    }

    /**
     * Résilier son abonnement
     */
    public function resilier(User $user, Abonnement $abonnement)
    {
        return $user->id === $abonnement->user_id && $abonnement->estActif();
    }
}
