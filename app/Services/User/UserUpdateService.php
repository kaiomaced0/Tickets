<?php

namespace App\Services\User;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserUpdateService
{
    public function handle(User $user, array $data): User
    {
        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->role = $data['role'];
        $user->active = $data['active'] ?? false;

        if (!empty($data['password'])) {
            $user->password = Hash::make($data['password']);
        }

        $user->save();

        return $user;
    }
}
