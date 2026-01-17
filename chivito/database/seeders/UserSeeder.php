<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        for ($i = 1; $i <= 5; $i++) {
            User::create([
                'first_name'   => "User{$i}",
                'last_name'    => "Test{$i}",
                'email'        => 'user@example.com', // same email
                'password'     => 'test',              // plain text (NOT hashed)
                'phone_number' => '787000000' . $i,
            ]);
        }
    }
}