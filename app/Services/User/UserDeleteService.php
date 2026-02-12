<?php

namespace App\Services\User;

use App\Models\User;
use Illuminate\Support\Facades\Gate;

class UserDeleteService
{
    public function handle(User $user, User $currentUser): bool
    {
        Gate::forUser($currentUser)->authorize('delete', $user);

        return $user->delete();
    }
}
