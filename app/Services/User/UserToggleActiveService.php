<?php

namespace App\Services\User;

use App\Models\User;
use Illuminate\Support\Facades\Gate;

class UserToggleActiveService
{
    public function handle(User $user, User $currentUser): User
    {
        Gate::forUser($currentUser)->authorize('toggleActive', $user);

        if ($user->trashed()) {
            $user->restore();
        } else {
            $user->delete();
        }

        return $user->fresh();
    }
}
