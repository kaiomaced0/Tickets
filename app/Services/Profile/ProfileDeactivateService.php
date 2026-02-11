<?php

namespace App\Services\Profile;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class ProfileDeactivateService
{
    /**
     * Desativa a conta do usuário após validar a senha.
     *
     * @param User $user
     * @param string $password
     * @return User
     * @throws ValidationException
     */
    public function handle(User $user, string $password): User
    {
        // Verificar se a senha está correta
        if (!Hash::check($password, $user->password)) {
            throw ValidationException::withMessages([
                'password' => 'A senha está incorreta.',
            ]);
        }

        // Inativar usuário
        $user->active = false;
        $user->save();

        return $user;
    }
}
