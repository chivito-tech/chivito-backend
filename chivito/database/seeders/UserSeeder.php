<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
            User::create([
                'first_name'   => "User}",
                'last_name'    => "Test",
                'email'        => 'usertest@example.com', // same email
                'password'     => 'test',              // plain text (NOT hashed)
                'phone_number' => '787000000',
            ]);   
    }
}