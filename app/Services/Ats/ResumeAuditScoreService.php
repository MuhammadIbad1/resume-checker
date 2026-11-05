<?php

namespace App\Services\Ats;

use App\Models\UserResume;

class ResumeAuditScoreService
{
    /**
     * Compute comprehensive ATS Friendliness evaluation for a structured UserResume.
     */
    public function calculateScore(UserResume $resume): array
    {
        $info = $resume->personal_info ?? [];
        $work = $resume->work_experience ?? [];
        $education = $resume->education ?? [];
        $skills = $resume->skills ?? [];
        $projects = $resume->projects ?? [];
        $certs = $resume->certifications ?? [];
        $custom = $resume->custom_sections ?? [];

        $checks = [];
        $totalPoints = 0;
        $maxPoints = 100;
        $tips = [];

        // 1. Contact & Identity Completeness (Max 20 pts)
        $hasName = !empty(trim($info['full_name'] ?? ''));
        $hasEmail = !empty(trim($info['email'] ?? '')) && filter_var($info['email'], FILTER_VALIDATE_EMAIL);
        $hasPhone = !empty(trim($info['phone'] ?? ''));
        $hasLocation = !empty(trim($info['location'] ?? ''));
        $hasLinkedin = !empty(trim($info['linkedin'] ?? ''));

        $contactScore = 0;
        if ($hasName) $contactScore += 5;
        if ($hasEmail) $contactScore += 5;
        if ($hasPhone) $contactScore += 4;
        if ($hasLocation) $contactScore += 3;
        if ($hasLinkedin) $contactScore += 3;
        $totalPoints += $contactScore;

        $checks[] = [
            'name' => 'Contact & Identity Completeness',
            'score' => $contactScore,
            'max' => 20,
            'passed' => $contactScore >= 17,
            'details' => ($hasEmail && $hasPhone && $hasLinkedin) 
                ? 'Full contact channels detected (Email, Phone, Location, LinkedIn).'
                : 'Missing recommended contact details for recruiter outreach.',
        ];
        if (!$hasLinkedin) {
            $tips[] = 'Add a clean LinkedIn URL to increase ATS credibility.';
        }
        if (!$hasLocation) {
            $tips[] = 'Include city/country or "Remote" to pass geographic screening filters.';
        }

        // 2. Summary & Executive Profile (Max 10 pts)
        $summaryText = trim($info['summary'] ?? '');
        $summaryWords = str_word_count($summaryText);
        $summaryScore = 0;
        if ($summaryWords >= 25 && $summaryWords <= 120) {
            $summaryScore = 10;
        } elseif ($summaryWords > 0) {
            $summaryScore = 6;
        }
        $totalPoints += $summaryScore;

        $checks[] = [
            'name' => 'Professional Executive Summary',
            'score' => $summaryScore,
            'max' => 10,
            'passed' => $summaryScore === 10,
            'details' => $summaryScore === 10 
                ? "Optimal summary length ({$summaryWords} words)."
                : ($summaryWords === 0 ? 'No professional summary found.' : "Summary is {$summaryWords} words (optimal is 30-80 words)."),
        ];
        if ($summaryScore < 10) {
            $tips[] = 'Write a concise 30-80 word summary highlighting your top skills and key accomplishments.';
        }

        // 3. Work Experience & Bullet Point Quality (Max 25 pts)
        $expCount = count($work);
        $totalBullets = 0;
        $allBulletsText = '';
        foreach ($work as $w) {
            $bullets = array_filter(explode("\n", $w['bullets'] ?? ''));
            $totalBullets += count($bullets);
            $allBulletsText .= ' ' . ($w['bullets'] ?? '') . ' ' . ($w['role'] ?? '');
        }

        $expScore = 0;
        if ($expCount >= 2) $expScore += 10;
        elseif ($expCount === 1) $expScore += 6;

        if ($totalBullets >= 4) $expScore += 8;
        elseif ($totalBullets >= 2) $expScore += 5;

        // Action verbs check
        $strongVerbs = ['architected', 'spearheaded', 'engineered', 'optimized', 'developed', 'scaled', 'implemented', 'designed', 'orchestrated', 'built', 'automated', 'delivered', 'integrated', 'lead', 'managed'];
        $foundVerbs = 0;
        $lowerExpText = strtolower($allBulletsText);
        foreach ($strongVerbs as $v) {
            if (str_contains($lowerExpText, $v)) $foundVerbs++;
        }
        if ($foundVerbs >= 3) $expScore += 7;
        elseif ($foundVerbs >= 1) $expScore += 4;

        $totalPoints += $expScore;
        $checks[] = [
            'name' => 'Work Experience & Action Verbs',
            'score' => $expScore,
            'max' => 25,
            'passed' => $expScore >= 20,
            'details' => "Found {$expCount} work roles with {$totalBullets} bullet points and {$foundVerbs} high-impact action verbs.",
        ];
        if ($foundVerbs < 3) {
            $tips[] = 'Start work experience bullets with powerful action verbs like "Architected", "Spearheaded", or "Optimized".';
        }

        // 4. Quantifiable Metrics & Business Results (Max 15 pts)
        // Check for numbers, %, $, k, m in bullets
        preg_match_all('/\b(?:\$\d+(?:\.\d+)?(?:k|m|b)?|\d+(?:\.\d+)?%|\d+x|\d+\s*(?:users|clients|projects|million|thousand))\b/i', $allBulletsText, $metricsMatches);
        $metricCount = count($metricsMatches[0] ?? []);
        $metricScore = 0;
        if ($metricCount >= 3) $metricScore = 15;
        elseif ($metricCount >= 1) $metricScore = 10;
        $totalPoints += $metricScore;

        $checks[] = [
            'name' => 'Quantifiable Metrics & Data',
            'score' => $metricScore,
            'max' => 15,
            'passed' => $metricScore >= 10,
            'details' => $metricCount > 0 
                ? "Identified {$metricCount} measurable results (percentages, scale figures, or growth metrics)."
                : 'No quantifiable data found in work experience bullets.',
        ];
        if ($metricScore < 15) {
            $tips[] = 'Quantify your impact with measurable data (e.g., "boosted efficiency by 35%", "handled 200k+ users").';
        }

        // 5. Skills & Competencies (Max 15 pts)
        $skillsScore = 0;
        $skillTotal = 0;
        if (is_array($skills)) {
            foreach ($skills as $sList) {
                if (is_array($sList)) $skillTotal += count($sList);
                elseif (is_string($sList)) $skillTotal += count(explode(',', $sList));
            }
        }
        if ($skillTotal >= 8) $skillsScore = 15;
        elseif ($skillTotal >= 4) $skillsScore = 10;
        elseif ($skillTotal > 0) $skillsScore = 5;
        $totalPoints += $skillsScore;

        $checks[] = [
            'name' => 'Technical & Professional Skills',
            'score' => $skillsScore,
            'max' => 15,
            'passed' => $skillsScore >= 10,
            'details' => "Categorized {$skillTotal} skills for ATS keyword matching.",
        ];
        if ($skillsScore < 15) {
            $tips[] = 'List at least 8-12 relevant technical skills and tools categorized clearly.';
        }

        // 6. Additional Sections & Richness (Projects, Certs, Custom) (Max 15 pts)
        $additionalScore = 0;
        $addedSections = [];
        if (!empty($education)) {
            $additionalScore += 5;
            $addedSections[] = 'Education (' . count($education) . ')';
        }
        if (!empty($projects)) {
            $additionalScore += 4;
            $addedSections[] = 'Key Projects (' . count($projects) . ')';
        }
        if (!empty($certs)) {
            $additionalScore += 3;
            $addedSections[] = 'Certifications (' . count($certs) . ')';
        }
        if (!empty($custom)) {
            $additionalScore += 3;
            $addedSections[] = 'Custom Sections (' . count($custom) . ')';
        }
        $additionalScore = min(15, $additionalScore);
        $totalPoints += $additionalScore;

        $checks[] = [
            'name' => 'Section Breadth & Enrichment',
            'score' => $additionalScore,
            'max' => 15,
            'passed' => $additionalScore >= 12,
            'details' => 'Sections present: ' . implode(', ', $addedSections),
        ];
        if (empty($projects)) {
            $tips[] = 'Add a Projects section to demonstrate real-world hands-on delivery.';
        }
        if (empty($certs)) {
            $tips[] = 'Include professional certifications or specialized credentials.';
        }

        // Overall Score capped 0 - 100
        $overallScore = min(100, max(0, $totalPoints));

        // Rating Badge
        if ($overallScore >= 90) {
            $grade = 'Exceptional ATS Compatibility';
            $badgeColor = 'bg-emerald-100 text-emerald-800 border-emerald-300 dark:bg-emerald-950 dark:text-emerald-300 dark:border-emerald-800';
            $icon = '🚀';
        } elseif ($overallScore >= 75) {
            $grade = 'Strong ATS Compatibility';
            $badgeColor = 'bg-purple-100 text-purple-800 border-purple-300 dark:bg-purple-950 dark:text-purple-300 dark:border-purple-800';
            $icon = '✓';
        } elseif ($overallScore >= 60) {
            $grade = 'Moderate ATS Compatibility';
            $badgeColor = 'bg-amber-100 text-amber-800 border-amber-300 dark:bg-amber-950 dark:text-amber-300 dark:border-amber-800';
            $icon = '⚠';
        } else {
            $grade = 'High Risk of Auto-Rejection';
            $badgeColor = 'bg-rose-100 text-rose-800 border-rose-300 dark:bg-rose-950 dark:text-rose-300 dark:border-rose-800';
            $icon = '✕';
        }

        return [
            'score' => $overallScore,
            'grade' => $grade,
            'badgeColor' => $badgeColor,
            'icon' => $icon,
            'checks' => $checks,
            'tips' => $tips,
            'totalSections' => count($addedSections) + ($hasName ? 1 : 0) + ($summaryScore > 0 ? 1 : 0) + ($expCount > 0 ? 1 : 0),
        ];
    }
}

