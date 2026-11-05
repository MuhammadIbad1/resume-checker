<?php

namespace App\Services\Ats\Rules;

use App\Models\RuleSetting;

class KeywordRule implements RuleContract
{
    public function evaluate(array $parsedData, ?string $jobDescription = null, ?RuleSetting $ruleSetting = null): array
    {
        $maxPoints = $ruleSetting ? $ruleSetting->max_points : 20;

        if (empty($jobDescription)) {
            return [
                'rule_code' => 'KW_MATCH_RATE',
                'rule_name' => 'Job Keyword Match Density',
                'category' => 'keywords',
                'passed' => true,
                'score_awarded' => intval($maxPoints * 0.75), // default partial score when no JD provided
                'max_score' => $maxPoints,
                'message' => 'No Job Description provided for targeted keyword matching.',
                'recommendation' => 'Provide a target Job Description to compare your resume keywords against specific role requirements.',
                'metadata' => [
                    'matched_keywords' => [],
                    'missing_keywords' => [],
                ],
            ];
        }

        // Tokenize Job Description into technical keywords / key terms
        $jdKeywords = $this->extractKeywords($jobDescription);
        $resumeText = strtolower($parsedData['raw_text'] ?? '');

        $matched = [];
        $missing = [];

        foreach ($jdKeywords as $kw) {
            if (preg_match('/\b' . preg_quote($kw, '/') . '\b/i', $resumeText)) {
                $matched[] = ucfirst($kw);
            } else {
                $missing[] = ucfirst($kw);
            }
        }

        $totalJdWords = count($jdKeywords);
        $matchRatio = $totalJdWords > 0 ? count($matched) / $totalJdWords : 1.0;
        $score = intval(round($matchRatio * $maxPoints));
        $passed = $matchRatio >= 0.7;

        return [
            'rule_code' => 'KW_MATCH_RATE',
            'rule_name' => 'Job Keyword Match Density',
            'category' => 'keywords',
            'passed' => $passed,
            'score_awarded' => $score,
            'max_score' => $maxPoints,
            'message' => 'Matched ' . count($matched) . ' of ' . $totalJdWords . ' key terms from the job description (' . round($matchRatio * 100) . '% match rate).',
            'recommendation' => empty($missing) 
                ? 'Excellent keyword alignment with the target job posting!' 
                : 'Consider incorporating missing key skills: ' . implode(', ', array_slice($missing, 0, 5)) . '.',
            'metadata' => [
                'matched_keywords' => array_slice($matched, 0, 15),
                'missing_keywords' => array_slice($missing, 0, 15),
                'match_percentage' => round($matchRatio * 100),
            ],
        ];
    }

    protected function extractKeywords(string $text): array
    {
        $text = strtolower($text);
        // Common stop words to exclude
        $stopWords = ['the', 'and', 'for', 'with', 'that', 'this', 'from', 'have', 'will', 'your', 'are', 'our', 'you', 'was', 'were', 'been', 'about', 'into', 'through', 'during', 'before', 'after', 'above', 'below', 'to', 'in', 'is', 'it', 'of', 'or', 'by', 'as', 'at', 'an', 'be', 'on', 'required', 'experience', 'ability', 'work', 'job', 'role', 'team', 'years', 'looking', 'candidate', 'strong', 'good', 'must', 'skills'];

        $words = preg_split('/[\s,.\/\(\)\:\;]+/', $text);
        $filtered = [];

        foreach ($words as $w) {
            $w = trim($w);
            if (strlen($w) >= 3 && !in_array($w, $stopWords) && !is_numeric($w)) {
                $filtered[] = $w;
            }
        }

        // Count frequencies and pick top terms
        $counts = array_count_values($filtered);
        arsort($counts);

        return array_keys(array_slice($counts, 0, 15));
    }
}
