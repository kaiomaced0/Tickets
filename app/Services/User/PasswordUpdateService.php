<?php

namespace App\Services\User;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class PasswordUpdateService
{
    /**
     * Atualiza a senha do usuário após validar a senha atual.
     *
     * @throws ValidationException
     */
    public function handle(User $user, string $currentPassword, string $newPassword): User
    {
        if (! Hash::check($currentPassword, $user->password)) {
            throw ValidationException::withMessages([
                'current_password' => 'A senha atual está incorreta.',
            ]);
        }

        $user->update([
            'password' => Hash::make($newPassword),
        ]);

        return $user;
    }
}
