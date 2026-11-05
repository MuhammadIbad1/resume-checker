<?php

namespace App\Services\Ats;

use App\Models\RuleSetting;
use App\Models\Scan;
use App\Models\ScanResult;
use App\Models\User;
use App\Services\Ats\Rules\ContactRule;
use App\Services\Ats\Rules\ExperienceRule;
use App\Services\Ats\Rules\KeywordRule;
use App\Services\Ats\Rules\MetricsRule;
use App\Services\Ats\Rules\SecurityRule;
use App\Services\Ats\Rules\StructureRule;
use Exception;
use Illuminate\Support\Facades\DB;

class ScoringEngineService
{
    protected ResumeParserService $parser;

    public function __construct(ResumeParserService $parser)
    {
        $this->parser = $parser;
    }

    public function evaluateResume(string $filePath, ?string $jobDescription = null, ?User $user = null, ?string $fileName = null): Scan
    {
        // 1. Extract text & tokens
        $parsedData = $this->parser->parseFile($filePath);
        $fileName = $fileName ?: basename($filePath);

        // 2. Fetch active rule settings from DB
        $enabledRules = RuleSetting::where('is_enabled', true)->get();

        $categoryRuleMap = [
            'structure' => StructureRule::class,
            'contact' => ContactRule::class,
            'experience' => ExperienceRule::class,
            'metrics' => MetricsRule::class,
            'keywords' => KeywordRule::class,
            'security' => SecurityRule::class,
        ];

        $scanResultsData = [];
        $categoryScores = [
            'structure' => ['awarded' => 0, 'max' => 0],
            'contact' => ['awarded' => 0, 'max' => 0],
            'experience' => ['awarded' => 0, 'max' => 0],
            'metrics' => ['awarded' => 0, 'max' => 0],
            'keywords' => ['awarded' => 0, 'max' => 0],
            'security' => ['awarded' => 0, 'max' => 0],
        ];

        $totalScoreAwarded = 0;
        $totalMaxPossible = 0;

        foreach ($enabledRules as $ruleSetting) {
            $cat = $ruleSetting->category;
            $ruleClass = $categoryRuleMap[$cat] ?? StructureRule::class;
            
            /** @var \App\Services\Ats\Rules\RuleContract $evaluator */
            $evaluator = new $ruleClass();
            $result = $evaluator->evaluate($parsedData, $jobDescription, $ruleSetting);

            $awarded = $result['score_awarded'] ?? 0;
            $max = $result['max_score'] ?? $ruleSetting->max_points;

            $totalScoreAwarded += $awarded;
            $totalMaxPossible += $max;

            if (isset($categoryScores[$cat])) {
                $categoryScores[$cat]['awarded'] += $awarded;
                $categoryScores[$cat]['max'] += $max;
            }

            $scanResultsData[] = [
                'category' => $cat,
                'rule_code' => $ruleSetting->rule_code,
                'rule_name' => $ruleSetting->name,
                'passed' => $result['passed'] ?? false,
                'score_awarded' => $awarded,
                'max_score' => $max,
                'message' => $result['message'] ?? '',
                'recommendation' => $result['recommendation'] ?? null,
                'metadata' => isset($result['metadata']) ? json_encode($result['metadata']) : null,
            ];
        }

        // Calculate overall percentage score (0 to 100)
        $overallScore = $totalMaxPossible > 0 ? intval(round(($totalScoreAwarded / $totalMaxPossible) * 100)) : 0;

        // Calculate simplified category percentages for frontend graphs
        $formattedCategoryScores = [];
        foreach ($categoryScores as $cat => $val) {
            $pct = $val['max'] > 0 ? intval(round(($val['awarded'] / $val['max']) * 100)) : 100;
            $formattedCategoryScores[$cat] = $pct;
        }

        return DB::transaction(function () use ($user, $fileName, $filePath, $jobDescription, $parsedData, $overallScore, $formattedCategoryScores, $scanResultsData) {
            $scan = Scan::create([
                'user_id' => $user?->id,
                'file_name' => $fileName,
                'file_path' => $filePath,
                'job_description' => $jobDescription,
                'raw_text' => $parsedData['raw_text'] ?? '',
                'overall_score' => $overallScore,
                'category_scores' => $formattedCategoryScores,
                'status' => 'completed',
            ]);

            foreach ($scanResultsData as $resData) {
                $scan->results()->create([
                    'category' => $resData['category'],
                    'rule_code' => $resData['rule_code'],
                    'rule_name' => $resData['rule_name'],
                    'passed' => $resData['passed'],
                    'score_awarded' => $resData['score_awarded'],
                    'max_score' => $resData['max_score'],
                    'message' => $resData['message'],
                    'recommendation' => $resData['recommendation'],
                    'metadata' => $resData['metadata'] ? json_decode($resData['metadata'], true) : null,
                ]);
            }

            return $scan;
        });
    }
}
