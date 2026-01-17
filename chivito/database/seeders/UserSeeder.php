<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // 🔹 Fixed user (for testing / login)
        User::create([
            'first_name'   => 'User',
            'last_name'    => 'Test',
            'email'        => 'usertest@example.com',
            'password'     => 'test', // plain text (dev only)
            'phone_number' => '7870000000',
        ]);

        // 🔹 4 random users
        for ($i = 1; $i <= 4; $i++) {
            User::create([
                'first_name'   => 'User' . $i,
                'last_name'    => 'Random',
                'email'        => 'user' . Str::random(5) . '@example.com',
                'password'     => 'test', // same password
                'phone_number' => '787' . rand(1000000, 9999999),
            ]);
        }
    }
}