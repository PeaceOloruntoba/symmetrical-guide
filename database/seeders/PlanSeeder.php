<?php

namespace Database\Seeders;

use App\Models\Plan;
use Illuminate\Database\Seeder;

class PlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Plan::create([
            'name' => 'Basic Plan',
            'price' => 9.99,
            'currency' => 'USD',
            'billing_period' => 'month',
            'has_chat' => true,
            'has_company_list' => false,
            'has_product_page' => true,
            'has_wallet_system' => false,
            'is_active' => true,
            'is_popular' => false,
        ]);

        Plan::create([
            'name' => 'Standard Plan',
            'price' => 19.99,
            'currency' => 'USD',
            'billing_period' => 'month',
            'has_chat' => true,
            'has_company_list' => true,
            'has_product_page' => true,
            'has_wallet_system' => true,
            'is_active' => true,
            'is_popular' => true,
        ]);

        Plan::create([
            'name' => 'Premium Plan',
            'price' => 39.99,
            'currency' => 'USD',
            'billing_period' => 'month',
            'has_chat' => true,
            'has_company_list' => true,
            'has_product_page' => true,
            'has_wallet_system' => true,
            'is_active' => true,
            'is_popular' => false,
        ]);
    }
}