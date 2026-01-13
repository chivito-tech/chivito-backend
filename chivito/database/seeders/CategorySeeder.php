<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Plumber', 'slug' => 'plumber'],
            ['name' => 'Electrician', 'slug' => 'electrician'],
            ['name' => 'Security', 'slug' => 'security'],
            ['name' => 'Car Detailer', 'slug' => 'detailer'],
            ['name' => 'Handyman', 'slug' => 'handyman'],
            ['name' => 'Painting', 'slug' => 'painting'],
            ['name' => 'House Cleaner', 'slug' => 'house cleaner'],
            ['name' => 'Mechanic', 'slug' => 'mechanic'],
            ['name' => 'Towing', 'slug' => 'towing'],
            ['name' => 'Landscaping', 'slug' => 'landscaping'],
            ['name' => 'Nail Tech', 'slug' => 'nail tech'],
            ['name' => 'Construction', 'slug' => 'construction'],
            ['name' => 'Windows & Doors', 'slug' => 'windows & doors'],
            ['name' => 'Catering', 'slug' => 'catering'],
            ['name' => 'HVAC', 'slug' => 'hvac'],
            ['name' => 'Personal Trainer', 'slug' => 'personal Trainer'],
            ['name' => 'Photographer', 'slug' => 'photographer'],
            ['name' => 'Videographer', 'slug' => 'videographer'],
            ['name' => 'Hair Stylist', 'slug' => 'hair stylist'],
        ];

        foreach ($categories as $category) {
            Category::updateOrCreate(
                ['slug' => $category['slug']], // prevent duplicates
                ['name' => $category['name']]
            );
        }
    }
}