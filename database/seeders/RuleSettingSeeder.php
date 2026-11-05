<?php

namespace Database\Seeders;

use App\Models\RuleSetting;
use Illuminate\Database\Seeder;

class RuleSettingSeeder extends Seeder
{
    public function run(): void
    {
        $rules = [
            // Category 1: Structure & Formatting
            [
                'rule_code' => 'ST_FILE_TYPE',
                'category' => 'structure',
                'name' => 'File Extension & Format',
                'description' => 'Checks if the document is standard PDF or DOCX readable by ATS scanners.',
                'max_points' => 10,
                'is_enabled' => true,
            ],
            [
                'rule_code' => 'ST_SECTIONS',
                'category' => 'structure',
                'name' => 'Standard Section Headings',
                'description' => 'Verifies standard section titles (Experience, Education, Skills, Projects, Summary).',
                'max_points' => 15,
                'is_enabled' => true,
            ],
            [
                'rule_code' => 'ST_WORD_COUNT',
                'category' => 'structure',
                'name' => 'Word Count & Page Length',
                'description' => 'Checks optimal resume length (typically 400 to 1200 words).',
                'max_points' => 10,
                'is_enabled' => true,
            ],

            // Category 2: Contact & Online Presence
            [
                'rule_code' => 'CT_EMAIL',
                'category' => 'contact',
                'name' => 'Professional Email Address',
                'description' => 'Validates email format and flags unprofessional usernames.',
                'max_points' => 10,
                'is_enabled' => true,
            ],
            [
                'rule_code' => 'CT_PHONE',
                'category' => 'contact',
                'name' => 'Phone Number Detection',
                'description' => 'Detects valid contact phone number format.',
                'max_points' => 5,
                'is_enabled' => true,
            ],
            [
                'rule_code' => 'CT_LINKEDIN',
                'category' => 'contact',
                'name' => 'LinkedIn Profile URL',
                'description' => 'Detects presence of a clean LinkedIn profile link.',
                'max_points' => 10,
                'is_enabled' => true,
            ],
            [
                'rule_code' => 'CT_LOCATION',
                'category' => 'contact',
                'name' => 'City & Country Location',
                'description' => 'Ensures candidate location is included for geographic filtering.',
                'max_points' => 5,
                'is_enabled' => true,
            ],

            // Category 3: Experience & Action Verbs
            [
                'rule_code' => 'EX_ACTION_VERBS',
                'category' => 'experience',
                'name' => 'Strong Action Verbs',
                'description' => 'Checks for high-impact action verbs (e.g. Architected, Spearheaded, Optimized, Scaled).',
                'max_points' => 15,
                'is_enabled' => true,
            ],
            [
                'rule_code' => 'EX_BULLET_LENGTH',
                'category' => 'experience',
                'name' => 'Bullet Point Structure & Length',
                'description' => 'Validates bullet length and checks for wall-of-text paragraphs.',
                'max_points' => 10,
                'is_enabled' => true,
            ],

            // Category 4: Quantifiable Metrics
            [
                'rule_code' => 'MT_METRICS',
                'category' => 'metrics',
                'name' => 'Measurable Achievements & Data',
                'description' => 'Detects percentages, metrics, dollar values, and growth numbers in work experience.',
                'max_points' => 10,
                'is_enabled' => true,
            ],

            // Category 5: Job Match & Keyword Alignment
            [
                'rule_code' => 'KW_MATCH_RATE',
                'category' => 'keywords',
                'name' => 'Job Keyword Match Density',
                'description' => 'Compares candidate skills against required target job description terms.',
                'max_points' => 20,
                'is_enabled' => true,
            ],

            // Category 6: Security, Privacy & ATS Fraud Protection
            [
                'rule_code' => 'SEC_PII_PROTECTION',
                'category' => 'security',
                'name' => 'PII & Identity Data Protection',
                'description' => 'Guards against exposing SSNs, national identity IDs, card numbers, or full birth dates on public job portals.',
                'max_points' => 10,
                'is_enabled' => true,
            ],
            [
                'rule_code' => 'SEC_PROMPT_INJECTION',
                'category' => 'security',
                'name' => 'ATS Prompt Injection & Adversarial Evasion',
                'description' => 'Detects hidden prompt injection attempts (e.g., "ignore previous instructions") that trigger enterprise ATS auto-rejection.',
                'max_points' => 10,
                'is_enabled' => true,
            ],
            [
                'rule_code' => 'SEC_SECRET_LEAKS',
                'category' => 'security',
                'name' => 'Secrets & Credential Exposure',
                'description' => 'Scans for hardcoded AWS keys, GitHub tokens, database passwords, and private certificates in code portfolios.',
                'max_points' => 10,
                'is_enabled' => true,
            ],
            [
                'rule_code' => 'SEC_LINK_SAFETY',
                'category' => 'security',
                'name' => 'Link Safety & Phishing Protection',
                'description' => 'Verifies external URLs are safe HTTPS links without executable download links or raw IP addresses.',
                'max_points' => 10,
                'is_enabled' => true,
            ],
            [
                'rule_code' => 'SEC_FILE_INTEGRITY',
                'category' => 'security',
                'name' => 'Document Integrity & Active Content Safety',
                'description' => 'Ensures the document is a safe static file free of active JavaScript streams or launch vectors that trigger ATS firewalls.',
                'max_points' => 10,
                'is_enabled' => true,
            ],
        ];

        foreach ($rules as $rule) {
            RuleSetting::updateOrCreate(
                ['rule_code' => $rule['rule_code']],
                $rule
            );
        }
    }
}
