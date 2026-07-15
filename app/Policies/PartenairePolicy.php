<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PartenairePolicy
{
    use HandlesAuthorization;

    public function before(User $user, $ability)
    {
        if ($user->isAdmin()) {
            return true;
        }
    }

    /**
     * Voir un partenaire
     */
    public function view(User $user, $partenaire)
    {
        return $user->id === $partenaire->user_id;
    }

    /**
     * Modifier un partenaire
     */
    public function update(User $user, $partenaire)
    {
        return $user->id === $partenaire->user_id;
    }

    /**
     * Valider une réception de déchets
     */
    public function validerReception(User $user)
    {
        return $user->isPartenaire();
    }
}
