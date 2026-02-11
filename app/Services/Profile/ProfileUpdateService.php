<?php

namespace App\Services\Profile;

use App\Models\User;

class ProfileUpdateService
{
    /**
     * Atualiza as informações do perfil do usuário.
     *
     * @param User $user
     * @param array $data
     * @return User
     */
    public function handle(User $user, array $data): User
    {
        $user->fill($data);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        return $user->fresh();
    }
}
