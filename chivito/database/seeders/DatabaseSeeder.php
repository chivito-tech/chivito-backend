<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Database\Seeders\SubcategorySeeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            CategorySeeder::class,
            UserSeeder::class,
            ProviderSeeder::class,
            SubcategorySeeder::class,
        ]);
     }
}