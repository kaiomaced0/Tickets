<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->createMany([
            [
                'name' => 'User One',
                'email' => 'user1@example.com',
                'api_token' => 'token-user-1',
            ],
            [
                'name' => 'User Two',
                'email' => 'user2@example.com',
                'api_token' => 'token-user-2',
            ],
        ]);

        User::factory()->admin()->createMany([
            [
                'name' => 'Admin One',
                'email' => 'admin1@example.com',
                'api_token' => 'token-admin-1',
            ],
            [
                'name' => 'Admin Two',
                'email' => 'admin2@example.com',
                'api_token' => 'token-admin-2',
            ],
        ]);
    }
}
