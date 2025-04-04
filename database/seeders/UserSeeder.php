<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin user
        $adminId = DB::table('users')->insertGetId([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Assign admin role
        $adminRole = Role::where('name', 'admin')->first();
        $adminRole->users()->attach($adminId, [
            'model_type' => 'App\\Models\\User'
        ]);

        // Create 5 company users
        $companies = [
            [
                'name' => 'TechGiant Inc.',
                'email' => 'tech@example.com',
                'company_name' => 'TechGiant Electronics',
                'description' => 'Leading provider of consumer electronics and gadgets',
                'website' => 'https://techgiant.example.com',
                'phone' => '+1-555-123-4567',
                'address' => '123 Tech Blvd, San Francisco, CA 94105'
            ],
            [
                'name' => 'Fashion Forward',
                'email' => 'fashion@example.com',
                'company_name' => 'Fashion Forward Apparel',
                'description' => 'Trendy clothing and accessories for all seasons',
                'website' => 'https://fashionforward.example.com',
                'phone' => '+1-555-234-5678',
                'address' => '456 Style Ave, New York, NY 10001'
            ],
            [
                'name' => 'Home Essentials',
                'email' => 'home@example.com',
                'company_name' => 'Home Essentials Co.',
                'description' => 'Quality furniture and home decor for modern living',
                'website' => 'https://homeessentials.example.com',
                'phone' => '+1-555-345-6789',
                'address' => '789 Comfort St, Chicago, IL 60601'
            ],
            [
                'name' => 'Outdoor Adventures',
                'email' => 'outdoor@example.com',
                'company_name' => 'Outdoor Adventures Gear',
                'description' => 'Equipment and apparel for outdoor enthusiasts',
                'website' => 'https://outdooradventures.example.com',
                'phone' => '+1-555-456-7890',
                'address' => '101 Mountain Rd, Denver, CO 80202'
            ],
            [
                'name' => 'Gourmet Delights',
                'email' => 'gourmet@example.com',
                'company_name' => 'Gourmet Delights Foods',
                'description' => 'Premium food products and specialty ingredients',
                'website' => 'https://gourmetdelights.example.com',
                'phone' => '+1-555-567-8901',
                'address' => '202 Culinary Blvd, Seattle, WA 98101'
            ]
        ];

        $companyRole = Role::where('name', 'company')->first();

        foreach ($companies as $company) {
            // Create user
            $userId = DB::table('users')->insertGetId([
                'name' => $company['name'],
                'email' => $company['email'],
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Assign company role
            $companyRole->users()->attach($userId, [
                'model_type' => 'App\\Models\\User'
            ]);

            // Create company profile
            DB::table('companies')->insert([
                'user_id' => $userId,
                'company_name' => $company['company_name'],
                'description' => $company['description'],
                'website' => $company['website'],
                'phone' => $company['phone'],
                'address' => $company['address'],
                'is_verified' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Create 5 regular users
        $users = [
            ['name' => 'John Doe', 'email' => 'john@example.com'],
            ['name' => 'Jane Smith', 'email' => 'jane@example.com'],
            ['name' => 'Robert Johnson', 'email' => 'robert@example.com'],
            ['name' => 'Emily Davis', 'email' => 'emily@example.com'],
            ['name' => 'Michael Wilson', 'email' => 'michael@example.com']
        ];

        $userRole = Role::where('name', 'user')->first();

        foreach ($users as $user) {
            $userId = DB::table('users')->insertGetId([
                'name' => $user['name'],
                'email' => $user['email'],
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Assign user role
            $userRole->users()->attach($userId, [
                'model_type' => 'App\\Models\\User'
            ]);
        }
    }
}