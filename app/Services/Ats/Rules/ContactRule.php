<?php

namespace App\Services\Ats\Rules;

use App\Models\RuleSetting;

class ContactRule implements RuleContract
{
    public function evaluate(array $parsedData, ?string $jobDescription = null, ?RuleSetting $ruleSetting = null): array
    {
        $code = $ruleSetting ? $ruleSetting->rule_code : 'CT_EMAIL';
        $maxPoints = $ruleSetting ? $ruleSetting->max_points : 10;

        if ($code === 'CT_EMAIL') {
            $email = $parsedData['email'] ?? null;
            $passed = !empty($email);
            return [
                'rule_code' => 'CT_EMAIL',
                'rule_name' => 'Professional Email Address',
                'category' => 'contact',
                'passed' => $passed,
                'score_awarded' => $passed ? $maxPoints : 0,
                'max_score' => $maxPoints,
                'message' => $passed ? "Detected contact email address: {$email}" : 'No valid contact email address found in document header.',
                'recommendation' => $passed ? null : 'Include a clear, professional email address at the top of your resume.',
            ];
        }

        if ($code === 'CT_PHONE') {
            $phone = $parsedData['phone'] ?? null;
            $passed = !empty($phone);
            return [
                'rule_code' => 'CT_PHONE',
                'rule_name' => 'Phone Number Detection',
                'category' => 'contact',
                'passed' => $passed,
                'score_awarded' => $passed ? $maxPoints : 0,
                'max_score' => $maxPoints,
                'message' => $passed ? "Detected phone number: {$phone}" : 'No contact phone number detected.',
                'recommendation' => $passed ? null : 'Add a valid phone number so recruiters can reach you easily.',
            ];
        }

        if ($code === 'CT_LINKEDIN') {
            $linkedin = $parsedData['linkedin'] ?? null;
            $passed = !empty($linkedin);
            return [
                'rule_code' => 'CT_LINKEDIN',
                'rule_name' => 'LinkedIn Profile URL',
                'category' => 'contact',
                'passed' => $passed,
                'score_awarded' => $passed ? $maxPoints : 0,
                'max_score' => $maxPoints,
                'message' => $passed ? "Detected LinkedIn profile link: {$linkedin}" : 'No LinkedIn profile URL found.',
                'recommendation' => $passed ? null : 'Add a customized LinkedIn profile link (e.g., linkedin.com/in/username).',
            ];
        }

        // CT_LOCATION
        $rawText = strtolower($parsedData['raw_text'] ?? '');
        $hasLocation = (bool) preg_match('/\b(?:san francisco|new york|london|lahore|karachi|islamabad|toronto|berlin|remote|ca|ny|tx|uk|pk)\b/i', $rawText);
        
        return [
            'rule_code' => 'CT_LOCATION',
            'rule_name' => 'City & Country Location',
            'category' => 'contact',
            'passed' => $hasLocation,
            'score_awarded' => $hasLocation ? $maxPoints : 0,
            'max_score' => $maxPoints,
            'message' => $hasLocation ? 'Location information identified.' : 'No geographical location or "Remote" designation found.',
            'recommendation' => $hasLocation ? null : 'Specify your current City, Country or "Remote" preference.',
        ];
    }
}
