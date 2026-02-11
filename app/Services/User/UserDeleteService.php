<?php

namespace App\Services\User;

use App\Models\User;

class UserDeleteService
{
    public function handle(User $user, User $currentUser): bool
    {
        // Não permitir deletar o próprio usuário
        if ($user->id === $currentUser->id) {
            throw new \Exception('Você não pode deletar seu próprio usuário!');
        }

        return $user->delete();
    }
}
