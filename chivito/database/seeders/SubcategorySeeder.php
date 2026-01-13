<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Subcategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class SubcategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $map = [
            'Car Detailer' => [
                'Interior Detailing',
                'Exterior Wash & Wax',
                'Full Detail (Interior + Exterior)',
                'Paint Correction & Ceramic Coating',
            ],
            'Catering' => [
                'Corporate Events',
                'Private Parties',
                'Weddings',
                'Meal Prep Services',
            ],
            'Construction' => [
                'Residential Construction',
                'Commercial Construction',
                'Renovations & Remodeling',
                'Concrete & Masonry',
            ],
            'Electrician' => [
                'Residential Electrical',
                'Commercial Electrical',
                'Panel Upgrades',
                'Lighting Installation',
            ],
            'HVAC' => [
                'AC Installation & Repair',
                'Heating Systems',
                'Maintenance & Tune-Ups',
                'Ductwork & Ventilation',
            ],
            'Hair Stylist' => [
                "Women's Haircuts & Styling",
                'Coloring & Highlights',
                'Blowouts & Treatments',
                'Bridal & Event Hair',
            ],
            'Handyman' => [
                'Home Repairs',
                'Furniture Assembly',
                'Mounting (TVs, Shelves)',
                'Minor Renovations',
            ],
            'House Cleaner' => [
                'Standard Cleaning',
                'Deep Cleaning',
                'Move-In / Move-Out',
                'Airbnb / Short-Term Rental',
            ],
            'Landscaping' => [
                'Lawn Care & Maintenance',
                'Tree Trimming',
                'Garden Design',
                'Irrigation Systems',
            ],
            'Mechanic' => [
                'General Auto Repair',
                'Diagnostics',
                'Brake & Suspension',
                'Oil Changes & Maintenance',
            ],
            'Nail Tech' => [
                'Manicure & Pedicure',
                'Acrylic & Gel Nails',
                'Nail Art',
                'Mobile Nail Services',
            ],
            'Painting' => [
                'Interior Painting',
                'Exterior Painting',
                'Commercial Painting',
                'Touch-Ups & Refinishing',
            ],
            'Personal Trainer' => [
                'Weight Loss Training',
                'Strength & Muscle Building',
                'Online Coaching',
                'Home / Outdoor Training',
            ],
            'Photographer' => [
                'Portrait Photography',
                'Event Photography',
                'Product Photography',
                'Real Estate Photography',
            ],
            'Plumber' => [
                'Leak Repairs',
                'Drain Cleaning',
                'Water Heater Services',
                'Installations & Remodeling',
            ],
            'Security' => [
                'Home Security Systems',
                'Surveillance Cameras',
                'Event Security',
                'Alarm Installation',
            ],
            'Towing' => [
                'Emergency Towing',
                'Roadside Assistance',
                'Long-Distance Towing',
                'Vehicle Recovery',
            ],
            'Videographer' => [
                'Event Videography',
                'Promotional / Business Videos',
                'Social Media Content',
                'Drone Videography',
            ],
            'Windows & Doors' => [
                'Window Installation',
                'Door Installation',
                'Repairs & Replacements',
                'Impact / Hurricane-Rated',
            ],
        ];

        foreach ($map as $categoryName => $subcategories) {
            $category = Category::where('name', $categoryName)->first();
            if (!$category) {
                continue;
            }

            foreach ($subcategories as $name) {
                Subcategory::updateOrCreate(
                    [
                        'category_id' => $category->id,
                        'slug' => Str::slug($name),
                    ],
                    ['name' => $name]
                );
            }
        }
    }
}
