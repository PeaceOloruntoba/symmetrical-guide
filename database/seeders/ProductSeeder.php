<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\Company;
use App\Models\Category;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get some companies to assign products to
        $companies = Company::take(5)->get();

        if ($companies->isEmpty()) {
            // Create a company if none exist
            $userId = DB::table('users')->insertGetId([
                'name' => 'Demo Company',
                'email' => 'demo@company.com',
                'password' => bcrypt('password'),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $companyId = DB::table('companies')->insertGetId([
                'user_id' => $userId,
                'company_name' => 'Demo Electronics',
                'description' => 'A demo company selling electronics',
                'is_verified' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Assign company role to user
            $roleId = DB::table('roles')->where('name', 'company')->first()->id;
            DB::table('model_has_roles')->insert([
                'role_id' => $roleId,
                'model_type' => 'App\\Models\\User',
                'model_id' => $userId
            ]);
        } else {
            $companyId = $companies->first()->id;
        }

        // Get electronics category
        $electronicsCategory = DB::table('categories')->where('name', 'Electronics')->first();
        $computersCategory = DB::table('categories')->where('name', 'Computers & Accessories')->first();

        // Sample products
        $products = [
            [
                'name' => 'MacBook Pro M2',
                'description' => 'The latest MacBook Pro with M2 chip, 16GB RAM, and 512GB SSD',
                'price' => 1999.99,
                'category' => 'Computers & Accessories',
                'colors' => ['Space Gray', 'Silver'],
                'images' => ['macbook_1.jpg', 'macbook_2.jpg']
            ],
            [
                'name' => 'iPhone 15 Pro',
                'description' => 'The latest iPhone with A17 Pro chip, 256GB storage, and Pro camera system',
                'price' => 1099.99,
                'category' => 'Smartphones & Accessories',
                'colors' => ['Titanium', 'Black', 'White', 'Blue'],
                'images' => ['iphone_1.jpg', 'iphone_2.jpg']
            ],
            [
                'name' => 'Sony WH-1000XM5',
                'description' => 'Industry-leading noise canceling headphones with premium sound quality',
                'price' => 349.99,
                'category' => 'Headphones & Audio',
                'colors' => ['Black', 'Silver'],
                'images' => ['sony_1.jpg', 'sony_2.jpg']
            ],
            [
                'name' => 'Samsung QLED 4K TV',
                'description' => '65-inch QLED 4K Smart TV with Quantum HDR and Alexa Built-in',
                'price' => 1299.99,
                'category' => 'TVs & Home Theater',
                'colors' => ['Black'],
                'images' => ['samsung_tv_1.jpg', 'samsung_tv_2.jpg']
            ],
            [
                'name' => 'Canon EOS R5',
                'description' => 'Full-frame mirrorless camera with 8K video recording and 45MP sensor',
                'price' => 3899.99,
                'category' => 'Cameras & Photography',
                'colors' => ['Black'],
                'images' => ['canon_1.jpg', 'canon_2.jpg']
            ]
        ];

        foreach ($products as $product) {
            // Insert product
            $productId = DB::table('products')->insertGetId([
                'company_id' => $companyId,
                'name' => $product['name'],
                'description' => $product['description'],
                'price' => $product['price'],
                'category' => $product['category'],
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Link to electronics category
            DB::table('product_category')->insert([
                'product_id' => $productId,
                'category_id' => $electronicsCategory->id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Link to computers category if applicable
            if ($computersCategory && $product['category'] === 'Computers & Accessories') {
                DB::table('product_category')->insert([
                    'product_id' => $productId,
                    'category_id' => $computersCategory->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            // Add colors
            foreach ($product['colors'] as $color) {
                DB::table('product_colors')->insert([
                    'product_id' => $productId,
                    'color' => $color,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            // Add images
            foreach ($product['images'] as $index => $image) {
                DB::table('product_images')->insert([
                    'product_id' => $productId,
                    'image_path' => $image,
                    'order' => $index,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}