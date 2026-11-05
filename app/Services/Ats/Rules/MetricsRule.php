<?php

namespace App\Services\Ats\Rules;

use App\Models\RuleSetting;

class MetricsRule implements RuleContract
{
    public function evaluate(array $parsedData, ?string $jobDescription = null, ?RuleSetting $ruleSetting = null): array
    {
        $maxPoints = $ruleSetting ? $ruleSetting->max_points : 10;
        $metrics = $parsedData['metrics'] ?? [];
        $count = count($metrics);

        $passed = $count >= 2;
        $score = $passed ? $maxPoints : intval(($count / 2) * $maxPoints);

        return [
            'rule_code' => 'MT_METRICS',
            'rule_name' => 'Measurable Achievements & Data',
            'category' => 'metrics',
            'passed' => $passed,
            'score_awarded' => $score,
            'max_score' => $maxPoints,
            'message' => $passed
                ? 'Found ' . $count . ' quantifiable achievement metrics (e.g. ' . implode(', ', array_slice($metrics, 0, 4)) . ').'
                : 'Found ' . ($count > 0 ? $count : 'no') . ' quantifiable data points or percentage metrics.',
            'recommendation' => $passed ? null : 'Quantify your accomplishments using numbers, percentages, dollar values, or team sizes (e.g. "Increased sales by 35%").',
        ];
    }
}
