<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        for ($i = 1; $i <= 5; $i++) {
            User::create([
                'first_name' => "User{$i}",
                'last_name' => "Test{$i}",
                'email' => "user{$i}@example.com",
                'password' => Hash::make('test'),
                'phone_number' => '787000000' . $i,
            ]);
        }
    }
}