<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserResume extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'template_slug',
        'personal_info',
        'work_experience',
        'education',
        'skills',
        'projects',
        'certifications',
        'custom_sections',
    ];

    protected $casts = [
        'personal_info' => 'array',
        'work_experience' => 'array',
        'education' => 'array',
        'skills' => 'array',
        'projects' => 'array',
        'certifications' => 'array',
        'custom_sections' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getAtsScoreAttribute(): array
    {
        return app(\App\Services\Ats\ResumeAuditScoreService::class)->calculateScore($this);
    }

    public function toPlainString(): string
    {
        $info = $this->personal_info ?? [];
        $text = [];

        $text[] = ($info['full_name'] ?? '') . "\n" .
                  "Email: " . ($info['email'] ?? '') . " | Phone: " . ($info['phone'] ?? '') . "\n" .
                  "LinkedIn: " . ($info['linkedin'] ?? '') . " | Location: " . ($info['location'] ?? '') . "\n\n";

        if (!empty($info['summary'])) {
            $text[] = "SUMMARY\n" . $info['summary'] . "\n\n";
        }

        if (!empty($this->work_experience)) {
            $text[] = "WORK EXPERIENCE\n";
            foreach ($this->work_experience as $exp) {
                $text[] = ($exp['role'] ?? '') . " — " . ($exp['company'] ?? '') . " (" . ($exp['start_date'] ?? '') . " - " . ($exp['end_date'] ?? 'Present') . ")\n";
                if (!empty($exp['bullets'])) {
                    foreach (array_filter(explode("\n", $exp['bullets'])) as $b) {
                        $text[] = "• " . trim($b) . "\n";
                    }
                }
                $text[] = "\n";
            }
        }

        if (!empty($this->education)) {
            $text[] = "EDUCATION\n";
            foreach ($this->education as $edu) {
                $text[] = ($edu['degree'] ?? '') . " in " . ($edu['field'] ?? '') . " — " . ($edu['institution'] ?? '') . " (" . ($edu['year'] ?? '') . ")\n";
            }
            $text[] = "\n";
        }

        if (!empty($this->skills)) {
            $text[] = "SKILLS\n";
            foreach ($this->skills as $skillCategory => $skillList) {
                if (is_array($skillList)) {
                    $text[] = (is_string($skillCategory) ? ucfirst($skillCategory) . ": " : "") . implode(", ", $skillList) . "\n";
                } elseif (is_string($skillList)) {
                    $text[] = $skillList . "\n";
                }
            }
            $text[] = "\n";
        }

        if (!empty($this->projects)) {
            $text[] = "PROJECTS\n";
            foreach ($this->projects as $proj) {
                $text[] = ($proj['title'] ?? '') . (!empty($proj['role']) ? " — " . $proj['role'] : '') . (!empty($proj['link']) ? " (" . $proj['link'] . ")" : '') . "\n";
                if (!empty($proj['description'])) {
                    $text[] = $proj['description'] . "\n";
                }
                if (!empty($proj['bullets'])) {
                    foreach (array_filter(explode("\n", $proj['bullets'])) as $b) {
                        $text[] = "• " . trim($b) . "\n";
                    }
                }
                $text[] = "\n";
            }
        }

        if (!empty($this->certifications)) {
            $text[] = "CERTIFICATIONS\n";
            foreach ($this->certifications as $cert) {
                $text[] = ($cert['name'] ?? '') . (!empty($cert['issuer']) ? " — " . $cert['issuer'] : '') . (!empty($cert['year']) ? " (" . $cert['year'] . ")" : '') . "\n";
            }
            $text[] = "\n";
        }

        if (!empty($this->custom_sections)) {
            foreach ($this->custom_sections as $cSec) {
                $secTitle = !empty($cSec['title']) ? strtoupper($cSec['title']) : 'ADDITIONAL DETAILS';
                $text[] = $secTitle . "\n";
                if (!empty($cSec['content'])) {
                    $text[] = $cSec['content'] . "\n";
                }
                if (!empty($cSec['items'])) {
                    foreach (array_filter(explode("\n", $cSec['items'])) as $it) {
                        $text[] = "• " . trim($it) . "\n";
                    }
                }
                $text[] = "\n";
            }
        }

        return implode("", $text);
    }
}
