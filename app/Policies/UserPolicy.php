<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    /**
     * Somente admin pode listar usuários.
     */
    public function viewAny(User $currentUser): bool
    {
        return $currentUser->isAdmin();
    }

    /**
     * Somente admin pode visualizar outro usuário.
     */
    public function view(User $currentUser, User $user): bool
    {
        return $currentUser->isAdmin();
    }

    /**
     * Somente admin pode criar usuários.
     */
    public function create(User $currentUser): bool
    {
        return $currentUser->isAdmin();
    }

    /**
     * Somente admin pode editar usuários.
     */
    public function update(User $currentUser, User $user): bool
    {
        return $currentUser->isAdmin();
    }

    /**
     * Somente admin pode deletar usuários.
     * Não pode deletar a si mesmo.
     */
    public function delete(User $currentUser, User $user): bool
    {
        return $currentUser->isAdmin() && $currentUser->id !== $user->id;
    }

    /**
     * Somente admin pode ativar/desativar usuários.
     * Não pode desativar a si mesmo.
     */
    public function toggleActive(User $currentUser, User $user): bool
    {
        return $currentUser->isAdmin() && $currentUser->id !== $user->id;
    }
}
