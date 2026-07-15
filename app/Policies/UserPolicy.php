<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Vérifier si l'utilisateur est admin (super permission)
     */
    public function before(User $user, $ability)
    {
        if ($user->isAdmin()) {
            return true;
        }
    }

    /**
     * Voir un utilisateur spécifique
     */
    public function view(User $user, User $targetUser)
    {
        // Un admin peut voir tout le monde
        if ($user->isAdmin()) return true;

        // Un utilisateur ne peut voir que son propre profil
        return $user->id === $targetUser->id;
    }

    /**
     * Créer un utilisateur
     */
    public function create(User $user)
    {
        return $user->isAdmin();
    }

    /**
     * Modifier un utilisateur
     */
    public function update(User $user, User $targetUser)
    {
        if ($user->isAdmin()) return true;

        // Un utilisateur ne peut modifier que son propre profil
        // (sauf le rôle et le statut qui sont réservés à l'admin)
        return $user->id === $targetUser->id;
    }

    /**
     * Supprimer un utilisateur
     */
    public function delete(User $user, User $targetUser)
    {
        return $user->isAdmin() && $user->id !== $targetUser->id;
    }

    /**
     * Voir la liste des utilisateurs
     */
    public function viewAny(User $user)
    {
        return $user->isAdmin();
    }
}
