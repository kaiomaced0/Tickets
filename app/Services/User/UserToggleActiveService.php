<?php

namespace App\Services\User;

use App\Models\User;

class UserToggleActiveService
{
    public function handle(User $user, User $currentUser): User
    {
        // Não permitir desativar o próprio usuário
        if ($user->id === $currentUser->id) {
            throw new \Exception('Você não pode desativar seu próprio usuário!');
        }

        $user->active = !$user->active;
        $user->save();

        return $user;
    }
}
