<?php

namespace Database\Seeders;

use App\Models\Plan;
use Illuminate\Database\Seeder;

class PlanSeeder extends Seeder
{
    public function run(): void
    {
        $plans = [
            [
                'name' => 'Free Starter',
                'slug' => 'free',
                'description' => 'Perfect for quick resume health checks and basic formatting analysis.',
                'price_monthly' => 0.00,
                'price_yearly' => 0.00,
                'price_pkr_monthly' => 0.00,
                'price_pkr_yearly' => 0.00,
                'scan_limit' => 3,
                'jd_limit' => 1,
                'features' => [
                    '3 Resume Scans per month',
                    '1 Job Description match check',
                    'Core ATS formatting check',
                    'Contact & structure score',
                    'Basic PDF text view',
                ],
                'stripe_price_id' => null,
                'is_active' => true,
                'is_popular' => false,
            ],
            [
                'name' => 'Pro Job Seeker',
                'slug' => 'pro',
                'description' => 'Ideal for active applicants optimizing for target tech roles and recruiters.',
                'price_monthly' => 15.00,
                'price_yearly' => 120.00,
                'price_pkr_monthly' => 3500.00,
                'price_pkr_yearly' => 28000.00,
                'scan_limit' => 50,
                'jd_limit' => 50,
                'features' => [
                    '50 Resume Scans per month',
                    '50 Job Match keyword reports',
                    'Detailed missing keyword analysis',
                    'Action verb & metric recommendations',
                    'Priority support',
                    'Manual payment support (JazzCash / Easypaisa)',
                ],
                'stripe_price_id' => 'price_pro_monthly_test',
                'is_active' => true,
                'is_popular' => true,
            ],
            [
                'name' => 'Business / Recruiter',
                'slug' => 'business',
                'description' => 'For career coaches, agencies, and recruiters processing high volumes of CVs.',
                'price_monthly' => 49.00,
                'price_yearly' => 390.00,
                'price_pkr_monthly' => 12000.00,
                'price_pkr_yearly' => 95000.00,
                'scan_limit' => -1, // Unlimited
                'jd_limit' => -1,   // Unlimited
                'features' => [
                    'Unlimited Resume Scans',
                    'Unlimited Job Match checks',
                    'Bulk resume parsing',
                    'Custom ATS rule configuration',
                    'Dedicated Account Manager',
                    'All payment methods (Stripe + Manual Transfer)',
                ],
                'stripe_price_id' => 'price_biz_monthly_test',
                'is_active' => true,
                'is_popular' => false,
            ],
        ];

        foreach ($plans as $plan) {
            Plan::updateOrCreate(
                ['slug' => $plan['slug']],
                $plan
            );
        }
    }
}
