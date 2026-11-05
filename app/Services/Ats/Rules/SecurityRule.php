<?php

namespace App\Services\Ats\Rules;

use App\Models\RuleSetting;

class SecurityRule implements RuleContract
{
    public function evaluate(array $parsedData, ?string $jobDescription = null, ?RuleSetting $ruleSetting = null): array
    {
        $code = $ruleSetting ? $ruleSetting->rule_code : 'SEC_PII_PROTECTION';
        $maxPoints = $ruleSetting ? $ruleSetting->max_points : 10;
        $rawText = $parsedData['raw_text'] ?? '';
        $filePath = $parsedData['file_path'] ?? null;

        // 1. Personally Identifiable Information (PII) Protection
        if ($code === 'SEC_PII_PROTECTION') {
            $findings = [];

            // Social Security Number (US)
            if (preg_match('/\b\d{3}-\d{2}-\d{4}\b/', $rawText)) {
                $findings[] = 'US Social Security Number (SSN)';
            }

            // Credit / Debit card patterns (Visa, MC, Amex, Discover)
            if (preg_match('/\b(?:4[0-9]{12}(?:[0-9]{3})?|5[1-5][0-9]{14}|3[47][0-9]{13}|6(?:011|5[0-9]{2})[0-9]{12})\b/', $rawText)) {
                $findings[] = 'Credit or Debit Card Number';
            }

            // CNIC / National ID (PK format: 12345-1234567-1)
            if (preg_match('/\b\d{5}-\d{7}-\d{1}\b/', $rawText)) {
                $findings[] = 'National Identity Card Number (CNIC)';
            }

            // Explicit Passport label + number
            if (preg_match('/\b(?:passport\s*(?:no|number|#)?\s*[:=]?\s*[A-Z0-9]{6,12})\b/i', $rawText)) {
                $findings[] = 'Passport Number';
            }

            // Full Date of Birth (flagged because recruiters in US/EU/UK ATS reject resumes with DOB for age bias compliance)
            if (preg_match('/\b(?:dob|date\s+of\s+birth|birth\s*date)\s*[:=]\s*\d{1,2}[\/\-\.]\d{1,2}[\/\-\.]\d{2,4}\b/i', $rawText)) {
                $findings[] = 'Exact Date of Birth (Age Bias Risk)';
            }

            $passed = empty($findings);

            return [
                'rule_code' => 'SEC_PII_PROTECTION',
                'rule_name' => 'PII & Identity Data Protection',
                'category' => 'security',
                'passed' => $passed,
                'score_awarded' => $passed ? $maxPoints : 0,
                'max_score' => $maxPoints,
                'message' => $passed
                    ? 'Privacy check passed: No exposed high-risk PII (SSN, National ID, card numbers, or DOB) found.'
                    : 'Security Warning: Detected exposed personal data: ' . implode(', ', $findings) . '.',
                'recommendation' => $passed
                    ? null
                    : 'Remove sensitive identifiers (SSN, national ID, passport, birth date). Modern ATS platforms and corporate recruiters filter out resumes with sensitive personal info to avoid legal compliance risks.',
                'metadata' => [
                    'findings' => $findings,
                ],
            ];
        }

        // 2. ATS Prompt Injection & Adversarial Evasion
        if ($code === 'SEC_PROMPT_INJECTION') {
            $injections = [];

            $injectionPatterns = [
                'System Prompt Override' => '/\b(?:ignore\s+(?:all\s+)?(?:previous|prior)\s+instructions|system\s+prompt\s*[:=]|system\s+instructions)\b/i',
                'AI Recruiter Hijack' => '/\b(?:you\s+are\s+(?:an?\s+)?(?:ai\s+)?recruiter|roleplay\s+as\s+(?:the\s+)?hiring\s+manager)\b/i',
                'Score Manipulation' => '/\b(?:give\s+(?:this\s+)?(?:resume|candidate)\s+(?:a\s+)?(?:100|perfect)\s+score|rank\s+(?:this\s+)?candidate\s+(?:#?1|highest|first))\b/i',
                'ATS Filter Bypass' => '/\b(?:disregard\s+(?:qualification|scoring)\s+criteria|bypass\s+ats\s+filter|jailbreak)\b/i',
                'Output Forcing' => '/\b(?:output\s+only\s+(?:["\']?hired["\']?|["\']?qualified["\']?)|reply\s+with\s+["\']?match\s*100%["\']?)\b/i',
            ];

            foreach ($injectionPatterns as $name => $pattern) {
                if (preg_match($pattern, $rawText)) {
                    $injections[] = $name;
                }
            }

            $passed = empty($injections);

            return [
                'rule_code' => 'SEC_PROMPT_INJECTION',
                'rule_name' => 'ATS Prompt Injection & Adversarial Evasion',
                'category' => 'security',
                'passed' => $passed,
                'score_awarded' => $passed ? $maxPoints : 0,
                'max_score' => $maxPoints,
                'message' => $passed
                    ? 'Integrity check passed: No adversarial prompt injection or AI manipulation patterns found.'
                    : 'Critical ATS Red-Flag: Detected prompt injection patterns: ' . implode(', ', $injections) . '.',
                'recommendation' => $passed
                    ? null
                    : 'Remove any hidden LLM instructions or AI prompt hacking phrases. Enterprise ATS engines (Workday, Greenhouse, Ashby) use adversarial detectors that automatically blacklist resumes with prompt injections.',
                'metadata' => [
                    'injections' => $injections,
                ],
            ];
        }

        // 3. Secrets, API Keys & Credential Leaks
        if ($code === 'SEC_SECRET_LEAKS') {
            $leaks = [];

            // AWS Access Key ID
            if (preg_match('/\bAKIA[0-9A-Z]{16}\b/', $rawText)) {
                $leaks[] = 'AWS Access Key ID';
            }

            // GitHub Personal Access Token
            if (preg_match('/\b(?:ghp_[a-zA-Z0-9]{36}|github_pat_[a-zA-Z0-9_]{50,})\b/', $rawText)) {
                $leaks[] = 'GitHub Personal Access Token';
            }

            // Private Key header
            if (preg_match('/-----BEGIN (?:RSA|OPENSSH|DSA|EC)? PRIVATE KEY-----/', $rawText)) {
                $leaks[] = 'Private Cryptographic Key';
            }

            // Hardcoded connection strings with passwords
            if (preg_match('/(?:mongodb(?:\+srv)?|postgres(?:ql)?|mysql):\/\/[a-zA-Z0-9._-]+:[a-zA-Z0-9._-]+@/i', $rawText)) {
                $leaks[] = 'Database Connection URI with Credentials';
            }

            // Generic API token assignment
            if (preg_match('/(?:api[_-]?key|auth[_-]?token|bearer[_-]?token)\s*[:=]\s*["\'][a-zA-Z0-9_\-\.]{20,}["\']/i', $rawText)) {
                $leaks[] = 'Hardcoded API Key / Bearer Token';
            }

            $passed = empty($leaks);

            return [
                'rule_code' => 'SEC_SECRET_LEAKS',
                'rule_name' => 'Secrets & Credential Exposure',
                'category' => 'security',
                'passed' => $passed,
                'score_awarded' => $passed ? $maxPoints : 0,
                'max_score' => $maxPoints,
                'message' => $passed
                    ? 'Security check passed: No exposed credentials, private keys, or API tokens detected.'
                    : 'High Security Risk: Found exposed secrets in resume text: ' . implode(', ', $leaks) . '.',
                'recommendation' => $passed
                    ? null
                    : 'Immediately sanitize any API keys, tokens, or private repository URLs. Do not include live keys in code snippets or project descriptions on your resume.',
                'metadata' => [
                    'leaks' => $leaks,
                ],
            ];
        }

        // 4. Safe Links & Phishing Prevention
        if ($code === 'SEC_LINK_SAFETY') {
            $threats = [];

            // Raw IP address URL
            if (preg_match('/\bhttp:\/\/\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}\b/', $rawText)) {
                $threats[] = 'Direct IP Address URL';
            }

            // Suspicious / executable download extensions in links
            if (preg_match('/\bhttps?:\/\/[^\s"\'><]+?\.(?:exe|bat|cmd|sh|vbs|scr|msi|apk|zip|rar)\b/i', $rawText, $m)) {
                $threats[] = 'Direct executable/archive download link (' . basename(parse_url($m[0], PHP_URL_PATH)) . ')';
            }

            // Insecure HTTP links when external
            if (preg_match_all('/\bhttp:\/\/(?!localhost|127\.0\.0\.1)([a-zA-Z0-9.-]+\.[a-zA-Z]{2,}[^\s"\'><]*)/i', $rawText, $httpMatches)) {
                $insecureDomains = array_unique($httpMatches[0]);
                if (count($insecureDomains) > 0) {
                    $threats[] = 'Insecure HTTP links detected (e.g. ' . implode(', ', array_slice($insecureDomains, 0, 2)) . ')';
                }
            }

            $passed = empty($threats);

            return [
                'rule_code' => 'SEC_LINK_SAFETY',
                'rule_name' => 'Link Safety & Phishing Protection',
                'category' => 'security',
                'passed' => $passed,
                'score_awarded' => $passed ? $maxPoints : 0,
                'max_score' => $maxPoints,
                'message' => $passed
                    ? 'All detected portfolio & project links are secure (HTTPS) and free of suspicious downloads.'
                    : 'Link Risk Detected: ' . implode('; ', $threats) . '.',
                'recommendation' => $passed
                    ? null
                    : 'Upgrade all links to secure HTTPS URLs and avoid direct links to executable files or unencrypted IP addresses.',
                'metadata' => [
                    'threats' => $threats,
                ],
            ];
        }

        // 5. Document Integrity & Active Script Inspection
        // Default to SEC_FILE_INTEGRITY
        $risks = [];

        if ($filePath && file_exists($filePath)) {
            $fileContents = @file_get_contents($filePath);
            if ($fileContents !== false) {
                // PDF Active Script Tags
                if (preg_match('/\/JavaScript\b|\/JS\b/i', $fileContents)) {
                    $risks[] = 'Active PDF JavaScript code embedded (/JS)';
                }
                if (preg_match('/\/Launch\b/i', $fileContents)) {
                    $risks[] = 'PDF /Launch action (can trigger external program execution)';
                }
                if (preg_match('/\/EmbeddedFiles\b/i', $fileContents)) {
                    $risks[] = 'Embedded file payload attachment in PDF';
                }
            }
        }

        $passed = empty($risks);

        return [
            'rule_code' => 'SEC_FILE_INTEGRITY',
            'rule_name' => 'Document Integrity & Active Content Safety',
            'category' => 'security',
            'passed' => $passed,
            'score_awarded' => $passed ? $maxPoints : 0,
            'max_score' => $maxPoints,
            'message' => $passed
                ? 'Document clean: Standard static document structure with no malicious embedded scripts or launch vectors.'
                : 'Malicious Content Alert: ' . implode(', ', $risks) . '.',
            'recommendation' => $passed
                ? null
                : 'Re-export your resume as a clean, static PDF using "Print to PDF" from Google Docs or Word. Enterprise mail and ATS security gateways quarantine PDFs containing active JavaScript or launch actions.',
            'metadata' => [
                'risks' => $risks,
            ],
        ];
    }
}

