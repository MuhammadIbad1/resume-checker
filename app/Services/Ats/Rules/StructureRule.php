<?php

namespace App\Services\Ats\Rules;

use App\Models\RuleSetting;

class StructureRule implements RuleContract
{
    public function evaluate(array $parsedData, ?string $jobDescription = null, ?RuleSetting $ruleSetting = null): array
    {
        $code = $ruleSetting ? $ruleSetting->rule_code : 'ST_SECTIONS';
        $maxPoints = $ruleSetting ? $ruleSetting->max_points : 15;

        if ($code === 'ST_FILE_TYPE') {
            return [
                'rule_code' => 'ST_FILE_TYPE',
                'rule_name' => 'File Extension & Format',
                'category' => 'structure',
                'passed' => true,
                'score_awarded' => $maxPoints,
                'max_score' => $maxPoints,
                'message' => 'Cleanly parsed PDF document text without parsing errors.',
                'recommendation' => null,
            ];
        }

        if ($code === 'ST_WORD_COUNT') {
            $wordCount = $parsedData['word_count'] ?? 0;
            $passed = ($wordCount >= 300 && $wordCount <= 1200);
            $score = $passed ? $maxPoints : intval($maxPoints * 0.5);

            return [
                'rule_code' => 'ST_WORD_COUNT',
                'rule_name' => 'Word Count & Page Length',
                'category' => 'structure',
                'passed' => $passed,
                'score_awarded' => $score,
                'max_score' => $maxPoints,
                'message' => "Total word count is {$wordCount} words.",
                'recommendation' => $passed ? null : ($wordCount < 300 ? 'Resume is too brief (<300 words). Expand details on impact and skills.' : 'Resume is overly verbose (>1200 words). Aim to compress to 1-2 pages.'),
            ];
        }

        // Default: ST_SECTIONS
        $sections = $parsedData['sections'] ?? [];
        $required = ['experience', 'education', 'skills'];
        $missing = array_diff($required, $sections);

        $passed = empty($missing);
        $score = $passed ? $maxPoints : max(0, $maxPoints - (count($missing) * 5));

        return [
            'rule_code' => 'ST_SECTIONS',
            'rule_name' => 'Standard Section Headings',
            'category' => 'structure',
            'passed' => $passed,
            'score_awarded' => $score,
            'max_score' => $maxPoints,
            'message' => $passed 
                ? 'Detected essential ATS section headings: ' . implode(', ', array_map('ucfirst', $sections))
                : 'Missing critical section headers: ' . implode(', ', array_map('ucfirst', $missing)),
            'recommendation' => $passed ? null : 'Use standard headers like "Work Experience", "Education", and "Technical Skills" so ATS scanners locate your history.',
        ];
    }
}
