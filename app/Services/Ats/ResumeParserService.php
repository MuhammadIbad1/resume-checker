<?php

namespace App\Services\Ats;

use Smalot\PdfParser\Parser as PdfParser;
use Throwable;

class ResumeParserService
{
    protected PdfParser $pdfParser;

    public function __construct()
    {
        $this->pdfParser = new PdfParser();
    }

    public function parseFile(string $absoluteFilePath): array
    {
        try {
            $pdf = $this->pdfParser->parseFile($absoluteFilePath);
            $rawText = $pdf->getText();
        } catch (Throwable $e) {
            $rawText = @file_get_contents($absoluteFilePath) ?: '';
        }

        $data = $this->analyzeText($rawText);
        $data['file_path'] = $absoluteFilePath;
        return $data;
    }

    public function analyzeText(string $rawText): array
    {
        $cleanText = $this->sanitizeText($rawText);
        
        return [
            'raw_text' => $cleanText,
            'word_count' => str_word_count($cleanText),
            'line_count' => count(array_filter(explode("\n", $cleanText))),
            'email' => $this->extractEmail($cleanText),
            'phone' => $this->extractPhone($cleanText),
            'linkedin' => $this->extractLinkedin($cleanText),
            'sections' => $this->detectSections($cleanText),
            'bullets' => $this->extractBullets($cleanText),
            'action_verbs' => $this->extractActionVerbs($cleanText),
            'metrics' => $this->extractMetrics($cleanText),
        ];
    }

    protected function sanitizeText(string $text): string
    {
        // Replace unicode non-breaking spaces and normalize newlines
        $text = str_replace(["\r\n", "\r"], "\n", $text);
        $text = preg_replace('/[^\x20-\x7E\n]/u', ' ', $text);
        return trim(preg_replace('/[ \t]+/', ' ', $text));
    }

    protected function extractEmail(string $text): ?string
    {
        if (preg_match('/[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}/', $text, $matches)) {
            return strtolower($matches[0]);
        }
        return null;
    }

    protected function extractPhone(string $text): ?string
    {
        if (preg_match('/(?:\+?\d{1,3}[-.\s]?)?\(?\d{3}\)?[-.\s]?\d{3}[-.\s]?\d{4}/', $text, $matches)) {
            return $matches[0];
        }
        return null;
    }

    protected function extractLinkedin(string $text): ?string
    {
        if (preg_match('/(?:https?:\/\/)?(?:www\.)?linkedin\.com\/in\/[a-zA-Z0-9_-]+/i', $text, $matches)) {
            return $matches[0];
        }
        return null;
    }

    protected function detectSections(string $text): array
    {
        $foundSections = [];
        $commonSections = [
            'experience' => ['experience', 'work history', 'employment history', 'professional background'],
            'education' => ['education', 'academic background', 'qualifications'],
            'skills' => ['skills', 'technical skills', 'core competencies', 'technologies'],
            'projects' => ['projects', 'key projects', 'personal projects'],
            'summary' => ['summary', 'professional summary', 'executive summary', 'profile', 'about me'],
            'certifications' => ['certifications', 'licenses', 'courses'],
        ];

        $lines = explode("\n", $text);
        foreach ($lines as $line) {
            $cleanLine = strtolower(trim($line));
            foreach ($commonSections as $sectionKey => $keywords) {
                foreach ($keywords as $kw) {
                    if ($cleanLine === $kw || preg_match('/^' . preg_quote($kw, '/') . '[:\s]*$/i', $cleanLine)) {
                        $foundSections[$sectionKey] = true;
                    }
                }
            }
        }

        return array_keys($foundSections);
    }

    protected function extractBullets(string $text): array
    {
        $bullets = [];
        $lines = explode("\n", $text);
        foreach ($lines as $line) {
            $trimmed = trim($line);
            if (preg_match('/^[\bullet\-\*•\>\d+\.]\s*(.+)/u', $trimmed, $matches)) {
                $bullets[] = trim($matches[1]);
            }
        }
        return $bullets;
    }

    protected function extractActionVerbs(string $text): array
    {
        $strongVerbs = [
            'architected', 'spearheaded', 'engineered', 'optimized', 'developed',
            'scaled', 'implemented', 'designed', 'orchestrated', 'built',
            'automated', 'transformed', 'delivered', 'integrated', 'lead',
            'directed', 'managed', 'created', 'accelerated', 'launched'
        ];

        $found = [];
        $words = array_map('strtolower', preg_split('/\W+/', $text));
        foreach ($strongVerbs as $verb) {
            if (in_array($verb, $words)) {
                $found[] = ucfirst($verb);
            }
        }

        return array_values(array_unique($found));
    }

    protected function extractMetrics(string $text): array
    {
        $metrics = [];
        // Look for percentages, dollar figures, numbers with k/m/b, multipliers
        if (preg_match_all('/\b(?:\$\d+(?:\.\d+)?(?:k|m|b)?|\d+(?:\.\d+)?%|\d+x|\d+\s*(?:users|clients|projects|million|thousand))\b/i', $text, $matches)) {
            $metrics = array_values(array_unique($matches[0]));
        }
        return $metrics;
    }
}
