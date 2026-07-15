<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Commande;
use Illuminate\Auth\Access\HandlesAuthorization;

class CommandePolicy
{
    use HandlesAuthorization;

    public function before(User $user, $ability)
    {
        if ($user->isAdmin()) {
            return true;
        }
    }

    /**
     * Voir une commande
     */
    public function view(User $user, Commande $commande)
    {
        // Le partenaire qui a commandé peut voir
        if ($user->id === $commande->partenaire_id) {
            return true;
        }

        return false;
    }

    /**
     * Créer une commande
     */
    public function create(User $user)
    {
        return $user->isPartenaire();
    }

    /**
     * Valider une commande (admin ou partenaire)
     */
    public function update(User $user, Commande $commande)
    {
        if ($user->isAdmin()) {
            return true;
        }

        return $user->id === $commande->partenaire_id;
    }
}
