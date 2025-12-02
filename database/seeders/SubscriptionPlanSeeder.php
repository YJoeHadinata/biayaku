<?php

namespace Database\Seeders;

use App\Models\SubscriptionPlan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SubscriptionPlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $plans = [
            [
                'name' => 'Free',
                'slug' => 'free',
                'description' => 'Paket gratis untuk memulai',
                'price' => 0,
                'currency' => 'IDR',
                'interval' => 'month',
                'features' => [
                    'basic_materials',
                    'basic_products',
                    'basic_reports',
                    'basic_costs'
                ],
                'limits' => [
                    'materials' => 10,
                    'products' => 5,
                    'production_batches' => 10,
                    'users_per_branch' => 1
                ],
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'Pro',
                'slug' => 'pro',
                'description' => 'Paket lengkap untuk bisnis menengah',
                'price' => 99000,
                'currency' => 'IDR',
                'interval' => 'month',
                'features' => [
                    'basic_materials',
                    'basic_products',
                    'basic_reports',
                    'basic_costs',
                    'advanced_reports',
                    'export_pdf',
                    'export_excel',
                    'multi_branch',
                    'priority_support'
                ],
                'limits' => [
                    'materials' => 200,
                    'products' => 100,
                    'production_batches' => 200,
                    'users_per_branch' => 10
                ],
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'Enterprise',
                'slug' => 'enterprise',
                'description' => 'Solusi lengkap untuk perusahaan besar',
                'price' => 299000,
                'currency' => 'IDR',
                'interval' => 'month',
                'features' => [
                    'basic_materials',
                    'basic_products',
                    'basic_reports',
                    'basic_costs',
                    'advanced_reports',
                    'export_pdf',
                    'export_excel',
                    'export_all',
                    'api_access',
                    'multi_branch',
                    'priority_support',
                    'custom_reports'
                ],
                'limits' => [
                    'materials' => 0, // unlimited
                    'products' => 0, // unlimited
                    'production_batches' => 0, // unlimited
                    'users_per_branch' => 0 // unlimited
                ],
                'is_active' => true,
                'sort_order' => 3,
            ],
        ];

        foreach ($plans as $plan) {
            SubscriptionPlan::create($plan);
        }
    }
}
