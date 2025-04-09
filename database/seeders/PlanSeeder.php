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
            'name' => 'test Plan',
            'price' => 45,
            'currency' => 'CNY',
            'billing_period' => 'month',
            'has_chat' => true,
            'has_company_list' => false,
            'has_product_page' => true,
            'has_wallet_system' => false,
            'is_active' => true,
            'is_popular' => false,
        ]);

    }
}