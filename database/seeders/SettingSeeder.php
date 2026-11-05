<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            [
                'key' => 'manual_payment_jazzcash',
                'value' => json_encode([
                    'title' => 'JazzCash Mobile Account',
                    'account_title' => 'TechDost Software Solutions',
                    'account_number' => '0300-1234567',
                    'instructions' => 'Send exact plan amount to JazzCash account above. Take a screenshot of the transaction receipt and upload it during checkout.',
                ]),
                'description' => 'JazzCash account details for manual payments',
            ],
            [
                'key' => 'manual_payment_easypaisa',
                'value' => json_encode([
                    'title' => 'Easypaisa Account',
                    'account_title' => 'TechDost Software Solutions',
                    'account_number' => '0312-9876543',
                    'instructions' => 'Transfer amount to Easypaisa number. Keep your Transaction ID (TRX ID) and upload receipt screenshot.',
                ]),
                'description' => 'Easypaisa account details for manual payments',
            ],
            [
                'key' => 'manual_payment_bank',
                'value' => json_encode([
                    'title' => 'Meezan Bank Limited',
                    'account_title' => 'TechDost Solutions Pvt Ltd',
                    'account_number' => '01020104882194',
                    'iban' => 'PK36MEZN0001020104882194',
                    'instructions' => 'Perform IBFT online bank transfer. Attach transaction screenshot or reference code for instant verification by admin.',
                ]),
                'description' => 'Bank account details for manual IBFT payments',
            ],
            [
                'key' => 'stripe_enabled',
                'value' => 'true',
                'description' => 'Enable or disable Stripe Credit Card Checkout',
            ],
            [
                'key' => 'manual_enabled',
                'value' => 'true',
                'description' => 'Enable or disable Manual Local Payment Proof Uploads',
            ],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
    }
}
