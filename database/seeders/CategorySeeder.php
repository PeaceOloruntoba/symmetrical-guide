<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Main categories
        $mainCategories = [
            'Electronics',
            'Computers & Office',
            'Home & Kitchen',
            'Toys & Games',
            'Beauty & Personal Care',
            'Clothing, Shoes & Jewelry',
            'Sports & Outdoors',
            'Health & Household',
            'Automotive',
            'Books',
            'Movies, Music & Games',
            'Pet Supplies',
            'Baby Products',
            'Grocery & Gourmet Food',
            'Industrial & Scientific',
            'Amazon Handmade',
            'Amazon Devices',
            'Software & Digital',
            'Home Improvement',
            'Luggage & Travel',
            'Musical Instruments',
            'Collectibles & Fine Art',
            'Amazon Fresh',
            'Amazon Pharmacy',
            'Luxury Stores',
            'Amazon Business',
            'Amazon Custom',
            'Amazon Outlet',
            'Amazon Subscription & Save',
            'Amazon Web Services'
        ];

        $mainCategoryIds = [];

        foreach ($mainCategories as $index => $category) {
            $id = DB::table('categories')->insertGetId([
                'name' => $category,
                'slug' => Str::slug($category),
                'level' => 0,
                'order' => $index,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $mainCategoryIds[$category] = $id;
        }

        // Subcategories
        $subcategories = [
            'Electronics' => [
                'Computers & Accessories',
                'TVs & Home Theater',
                'Cameras & Photography',
                'Headphones & Audio',
                'Smartphones & Accessories',
                'Wearable Tech',
                'Video Games & Consoles'
            ],
            'Computers & Office' => [
                'Printers & Ink',
                'Office Supplies',
                'Software'
            ],
            'Home & Kitchen' => [
                'Furniture',
                'Kitchen Appliances',
                'Home Decor'
            ],
            'Toys & Games' => [
                'Action Figures',
                'Board Games'
            ],
            'Beauty & Personal Care' => [
                'Skincare',
                'Hair Care'
            ],
            'Clothing, Shoes & Jewelry' => [
                'Men\'s Fashion',
                'Women\'s Fashion'
            ],
            'Sports & Outdoors' => [
                'Fitness',
                'Camping'
            ],
            'Health & Household' => [
                'Medical Supplies',
                'Cleaning Supplies'
            ],
            'Automotive' => [
                'Car Parts',
                'Accessories'
            ],
            'Books' => [
                'Fiction',
                'Non-Fiction'
            ],
            'Home Improvement' => [
                'Tools',
                'Plumbing',
                'Electrical',
                'Building Materials'
            ]
        ];

        foreach ($subcategories as $mainCategory => $subs) {
            if (!isset($mainCategoryIds[$mainCategory])) {
                continue;
            }

            foreach ($subs as $index => $subcategory) {
                $slug = Str::slug($subcategory);

                $exists = DB::table('categories')->where('slug', $slug)->exists();
                if ($exists) {
                    $slug = Str::slug($mainCategory . '-' . $subcategory);
                }

                DB::table('categories')->insert([
                    'name' => $subcategory,
                    'slug' => $slug,
                    'parent_id' => $mainCategoryIds[$mainCategory],
                    'level' => 1,
                    'order' => $index,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}