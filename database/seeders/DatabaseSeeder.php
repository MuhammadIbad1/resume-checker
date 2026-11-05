<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Plan;
use App\Models\Subscription;
use App\Models\Payment;
use App\Models\Scan;
use App\Models\ScanResult;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RuleSettingSeeder::class,
            PlanSeeder::class,
            SettingSeeder::class,
        ]);

        // 1. Create Admin Account
        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'System Admin',
                'password' => Hash::make('password'),
                'is_admin' => true,
            ]
        );

        // 2. Create Demo Job Seeker
        $user = User::firstOrCreate(
            ['email' => 'user@example.com'],
            [
                'name' => 'Alex Johnson',
                'password' => Hash::make('password'),
                'is_admin' => false,
            ]
        );

        // Assign Pro Plan subscription to Alex
        $proPlan = Plan::where('slug', 'pro')->first();
        if ($proPlan) {
            $sub = Subscription::create([
                'user_id' => $user->id,
                'plan_id' => $proPlan->id,
                'provider' => 'manual',
                'status' => 'active',
                'billing_cycle' => 'monthly',
                'starts_at' => Carbon::now(),
                'ends_at' => Carbon::now()->addMonth(),
            ]);

            // Create a paid payment record
            Payment::create([
                'user_id' => $user->id,
                'subscription_id' => $sub->id,
                'plan_id' => $proPlan->id,
                'provider' => 'manual',
                'method_details' => 'JazzCash Transfer',
                'amount' => 3500.00,
                'currency' => 'PKR',
                'status' => 'paid',
                'reference' => 'TRX-9821448',
                'user_notes' => 'Sent via JazzCash from 0300-1122334',
                'admin_notes' => 'Verified on bank portal.',
                'reviewed_by' => $admin->id,
                'reviewed_at' => Carbon::now(),
            ]);
        }

        // 3. Create Demo Pending Payment (for Admin Approval Queue testing)
        $user2 = User::firstOrCreate(
            ['email' => 'sarah@example.com'],
            [
                'name' => 'Sarah Connor',
                'password' => Hash::make('password'),
                'is_admin' => false,
            ]
        );

        Payment::create([
            'user_id' => $user2->id,
            'plan_id' => $proPlan->id,
            'provider' => 'manual',
            'method_details' => 'Easypaisa',
            'amount' => 3500.00,
            'currency' => 'PKR',
            'status' => 'pending',
            'reference' => 'EP-44910293',
            'user_notes' => 'Transferred 3500 PKR for Pro Plan. Please approve.',
        ]);

        // 4. Create Sample Scan History for Alex
        $sampleScan = Scan::create([
            'user_id' => $user->id,
            'file_name' => 'Alex_Johnson_Senior_Dev_CV.pdf',
            'file_path' => 'resumes/demo_cv.pdf',
            'job_description' => 'Senior Full Stack Developer required with PHP, Laravel, Livewire, MySQL, React, and REST API experience.',
            'raw_text' => "Alex Johnson\nEmail: alex.johnson@email.com | Phone: +1-555-0192 | LinkedIn: linkedin.com/in/alexjohnson\nLocation: San Francisco, CA\n\nSUMMARY\nPassionate Senior Full Stack Developer with 6+ years of experience engineering scalable web applications using Laravel, PHP, MySQL, and Vue.js.\n\nEXPERIENCE\nSenior Software Engineer — TechCorp (2021 - Present)\n- Architected high-throughput microservices reducing response latency by 42% for 200,000 daily users.\n- Spearheaded Laravel 11 migration, increasing database query efficiency by 35%.\n- Led a team of 5 engineers to deliver payment integration via Stripe and local gateways.\n\nSKILLS\nPHP, Laravel, Livewire, MySQL, REST API, Git, Docker, JavaScript",
            'overall_score' => 88,
            'category_scores' => [
                'structure' => 95,
                'contact' => 100,
                'experience' => 90,
                'metrics' => 80,
                'keywords' => 75,
            ],
            'status' => 'completed',
        ]);

        // Create sample granular results
        $results = [
            [
                'category' => 'structure',
                'rule_code' => 'ST_FILE_TYPE',
                'rule_name' => 'File Extension & Format',
                'passed' => true,
                'score_awarded' => 10,
                'max_score' => 10,
                'message' => 'PDF file format is cleanly readable by standard ATS parsers.',
            ],
            [
                'category' => 'contact',
                'rule_code' => 'CT_EMAIL',
                'rule_name' => 'Professional Email Address',
                'passed' => true,
                'score_awarded' => 10,
                'max_score' => 10,
                'message' => 'Valid professional email address detected: alex.johnson@email.com',
            ],
            [
                'category' => 'experience',
                'rule_code' => 'EX_ACTION_VERBS',
                'rule_name' => 'Strong Action Verbs',
                'passed' => true,
                'score_awarded' => 15,
                'max_score' => 15,
                'message' => 'Found strong impact action verbs: Architected, Spearheaded, Engineered.',
            ],
            [
                'category' => 'metrics',
                'rule_code' => 'MT_METRICS',
                'rule_name' => 'Measurable Achievements & Data',
                'passed' => true,
                'score_awarded' => 10,
                'max_score' => 10,
                'message' => 'Quantifiable metrics found (42%, 200,000 users, 35%). Great job!',
            ],
            [
                'category' => 'keywords',
                'rule_code' => 'KW_MATCH_RATE',
                'rule_name' => 'Job Keyword Match Density',
                'passed' => true,
                'score_awarded' => 15,
                'max_score' => 20,
                'message' => 'Matched 5 of 6 target job keywords (Laravel, PHP, Livewire, MySQL, REST API). Missing keyword: React.',
                'recommendation' => 'Consider adding React or frontend UI framework experience to maximize match rate.',
            ],
        ];

        foreach ($results as $res) {
            ScanResult::create(array_merge(['scan_id' => $sampleScan->id], $res));
        }
    }
}
