<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Provider;
use App\Models\User;
use App\Models\Category;

class ProviderSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();
        $categories = Category::all();

        foreach ($users as $user) {
            $provider = Provider::create([
                'user_id' => $user->id,
                'name' => $user->first_name . ' ' . $user->last_name,
                'company_name' => $user->first_name . ' Services',
                'phone' => $user->phone_number,
                'bio' => 'Professional service provider',
                'city' => 'Metro',
                'zip' => '00901',
                'status' => 'pending',
                'price' => rand(25, 100),
            ]);

            // Attach 1–2 random categories
            $provider->categories()->attach(
                $categories->random(rand(1, 2))->pluck('id')->toArray()
            );
        }
    }
}