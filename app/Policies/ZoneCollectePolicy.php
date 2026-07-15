<?php

namespace App\Policies;

use App\Models\User;
use App\Models\ZoneCollecte;
use Illuminate\Auth\Access\HandlesAuthorization;

class ZoneCollectePolicy
{
    use HandlesAuthorization;

    public function before(User $user, $ability)
    {
        if ($user->isAdmin()) {
            return true;
        }
    }

    /**
     * Voir les zones (collecteurs uniquement)
     */
    public function view(User $user, ZoneCollecte $zone)
    {
        // Un collecteur peut voir les zones où il travaille
        if ($user->isCollecteur()) {
            return $zone->collecteurs->contains($user->collecteur->id);
        }

        return false;
    }

    /**
     * Créer une zone (admin uniquement)
     */
    public function create(User $user)
    {
        return $user->isAdmin();
    }

    /**
     * Modifier une zone (admin uniquement)
     */
    public function update(User $user, ZoneCollecte $zone)
    {
        return $user->isAdmin();
    }

    /**
     * Supprimer une zone (admin uniquement)
     */
    public function delete(User $user, ZoneCollecte $zone)
    {
        return $user->isAdmin();
    }
}
