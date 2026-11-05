<?php

namespace App\Services\Ats\Rules;

use App\Models\RuleSetting;

class ExperienceRule implements RuleContract
{
    public function evaluate(array $parsedData, ?string $jobDescription = null, ?RuleSetting $ruleSetting = null): array
    {
        $code = $ruleSetting ? $ruleSetting->rule_code : 'EX_ACTION_VERBS';
        $maxPoints = $ruleSetting ? $ruleSetting->max_points : 15;

        if ($code === 'EX_ACTION_VERBS') {
            $verbs = $parsedData['action_verbs'] ?? [];
            $count = count($verbs);
            $passed = $count >= 3;
            $score = $passed ? $maxPoints : intval(($count / 3) * $maxPoints);

            return [
                'rule_code' => 'EX_ACTION_VERBS',
                'rule_name' => 'Strong Action Verbs',
                'category' => 'experience',
                'passed' => $passed,
                'score_awarded' => $score,
                'max_score' => $maxPoints,
                'message' => $passed
                    ? 'Found ' . $count . ' strong action verbs: ' . implode(', ', array_slice($verbs, 0, 5))
                    : 'Found only ' . $count . ' strong action verbs.',
                'recommendation' => $passed ? null : 'Start bullet points with strong verbs such as Architected, Spearheaded, Optimized, or Engineered.',
            ];
        }

        // EX_BULLET_LENGTH
        $bullets = $parsedData['bullets'] ?? [];
        $bulletCount = count($bullets);
        $passed = $bulletCount >= 3;
        $score = $passed ? $maxPoints : intval(($bulletCount / 3) * $maxPoints);

        return [
            'rule_code' => 'EX_BULLET_LENGTH',
            'rule_name' => 'Bullet Point Structure & Length',
            'category' => 'experience',
            'passed' => $passed,
            'score_awarded' => $score,
            'max_score' => $maxPoints,
            'message' => "Detected {$bulletCount} distinct bulleted achievement items.",
            'recommendation' => $passed ? null : 'Structure work experience using bullet points rather than continuous blocks of text.',
        ];
    }
}
