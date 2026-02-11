<?php

namespace App\Services\Settings;

use App\Models\User;

class SettingsUpdateThemeService
{
    /**
     * Atualiza o tema do usuário.
     *
     * @param User $user
     * @param string $theme
     * @return User
     */
    public function handle(User $user, string $theme): User
    {
        $user->theme = $theme;
        $user->save();

        return $user->fresh();
    }
}
