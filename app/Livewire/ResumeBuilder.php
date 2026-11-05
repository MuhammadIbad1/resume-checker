<?php

namespace App\Livewire;

use App\Models\UserResume;
use App\Services\Ats\ResumeAuditScoreService;
use Livewire\Component;

class ResumeBuilder extends Component
{
    public ?UserResume $userResume = null;

    public string $title = 'Senior Developer Resume 2026';
    public string $template_slug = 'modern_ats';

    // Personal Info Fields
    public string $fullName = '';
    public string $jobTitle = '';
    public string $email = '';
    public string $phone = '';
    public string $location = '';
    public string $linkedin = '';
    public string $github = '';
    public string $summary = '';

    // Dynamic Section Arrays
    public array $workExperience = [];
    public array $education = [];
    public array $skills = [];
    public array $projects = [];
    public array $certifications = [];
    public array $customSections = [];

    public function mount(?UserResume $userResume = null)
    {
        if ($userResume && $userResume->exists) {
            // Security Authorization Check (Prevent IDOR)
            if ($userResume->user_id !== auth()->id() && !auth()->user()?->is_admin) {
                abort(403, 'Unauthorized access to this resume.');
            }

            $this->userResume = $userResume;
            $this->title = $userResume->title;
            $this->template_slug = $userResume->template_slug;

            $info = $userResume->personal_info ?? [];
            $this->fullName = $info['full_name'] ?? '';
            $this->jobTitle = $info['job_title'] ?? '';
            $this->email = $info['email'] ?? '';
            $this->phone = $info['phone'] ?? '';
            $this->location = $info['location'] ?? '';
            $this->linkedin = $info['linkedin'] ?? '';
            $this->github = $info['github'] ?? '';
            $this->summary = $info['summary'] ?? '';

            $this->workExperience = $userResume->work_experience ?? [];
            $this->education = $userResume->education ?? [];
            $this->skills = $userResume->skills ?? [];
            $this->projects = $userResume->projects ?? [];
            $this->certifications = $userResume->certifications ?? [];
            $this->customSections = $userResume->custom_sections ?? [];
        } else {
            // Seed defaults for easy fast creation
            $user = auth()->user();
            $this->fullName = $user ? $user->name : 'Alex Johnson';
            $this->email = $user ? $user->email : 'alex.johnson@email.com';
            $this->jobTitle = 'Senior Full Stack Software Engineer';
            $this->phone = '+1 (555) 019-2834';
            $this->location = 'San Francisco, CA (Remote)';
            $this->linkedin = 'linkedin.com/in/alexjohnson';
            $this->github = 'github.com/alexjohnson';
            $this->summary = 'Results-driven Senior Engineer with 6+ years of experience building high-throughput web applications using Laravel, Livewire, MySQL, and Vue.js. Proven track record scaling APIs for 200k+ daily active users.';

            $this->workExperience = [
                [
                    'role' => 'Senior Full Stack Engineer',
                    'company' => 'TechCorp Global Solutions',
                    'start_date' => '2022',
                    'end_date' => 'Present',
                    'bullets' => "Architected high-throughput REST APIs reducing response latency by 42% for 200,000+ daily active users.\nSpearheaded migration to Laravel 12 and Livewire 3, increasing database query efficiency by 35%.\nLed team of 5 engineers delivering payment gateway integration via Stripe and local fintech gateways.",
                ],
                [
                    'role' => 'Software Developer',
                    'company' => 'Apex Digital Studio',
                    'start_date' => '2019',
                    'end_date' => '2022',
                    'bullets' => "Developed scalable SaaS dashboard components using Tailwind CSS and Vue.js.\nOptimized MySQL database indexing resulting in a 50% speedup on complex reporting queries.",
                ]
            ];

            $this->education = [
                [
                    'degree' => 'Bachelor of Science',
                    'field' => 'Computer Science',
                    'institution' => 'University of California, Berkeley',
                    'year' => '2019',
                ]
            ];

            $this->skills = [
                'Backend & Languages' => 'PHP, Laravel 12, Python, Node.js, REST APIs',
                'Frontend & Styling' => 'Livewire 3, Tailwind CSS, JavaScript, Alpine.js, Vue.js',
                'Database & DevOps' => 'MySQL, PostgreSQL, Redis, Docker, Git, Linux',
            ];

            $this->projects = [
                [
                    'title' => 'ATS Pulse AI Platform',
                    'role' => 'Lead Architect',
                    'link' => 'https://atspulse.io',
                    'description' => 'Real-time resume scoring engine with 6-rule ATS evaluation, dual Stripe & manual payments, and dynamic rule weight controls.',
                    'bullets' => "Implemented automated scoring pipeline evaluating 1,000+ keywords per second.\nIntegrated PDF binary parsing and adversarial prompt injection defenses.",
                ]
            ];

            $this->certifications = [
                [
                    'name' => 'AWS Certified Solutions Architect – Associate',
                    'issuer' => 'Amazon Web Services',
                    'year' => '2023',
                ]
            ];

            $this->customSections = [
                [
                    'title' => 'Languages & Fluency',
                    'content' => 'English (Native / Bilingual), Spanish (Professional Working Proficiency)',
                    'items' => '',
                ]
            ];
        }
    }

    // Work Experience
    public function addExperience()
    {
        $this->workExperience[] = [
            'role' => '',
            'company' => '',
            'start_date' => '',
            'end_date' => 'Present',
            'bullets' => '',
        ];
    }

    public function removeExperience($index)
    {
        unset($this->workExperience[$index]);
        $this->workExperience = array_values($this->workExperience);
    }

    // Education
    public function addEducation()
    {
        $this->education[] = [
            'degree' => '',
            'field' => '',
            'institution' => '',
            'year' => '',
        ];
    }

    public function removeEducation($index)
    {
        unset($this->education[$index]);
        $this->education = array_values($this->education);
    }

    // Projects
    public function addProject()
    {
        $this->projects[] = [
            'title' => '',
            'role' => '',
            'link' => '',
            'description' => '',
            'bullets' => '',
        ];
    }

    public function removeProject($index)
    {
        unset($this->projects[$index]);
        $this->projects = array_values($this->projects);
    }

    // Certifications
    public function addCertification()
    {
        $this->certifications[] = [
            'name' => '',
            'issuer' => '',
            'year' => '',
        ];
    }

    public function removeCertification($index)
    {
        unset($this->certifications[$index]);
        $this->certifications = array_values($this->certifications);
    }

    // Custom Dynamic Sections
    public function addCustomSection(string $title = 'Custom Section')
    {
        $this->customSections[] = [
            'title' => $title,
            'content' => '',
            'items' => '',
        ];
    }

    public function removeCustomSection($index)
    {
        unset($this->customSections[$index]);
        $this->customSections = array_values($this->customSections);
    }

    /**
     * Compute Real-Time ATS Friendliness Score in the Builder.
     */
    public function getAtsScoreProperty(): array
    {
        $tempResume = new UserResume([
            'personal_info' => [
                'full_name' => $this->fullName,
                'job_title' => $this->jobTitle,
                'email' => $this->email,
                'phone' => $this->phone,
                'location' => $this->location,
                'linkedin' => $this->linkedin,
                'github' => $this->github,
                'summary' => $this->summary,
            ],
            'work_experience' => $this->workExperience,
            'education' => $this->education,
            'skills' => $this->skills,
            'projects' => $this->projects,
            'certifications' => $this->certifications,
            'custom_sections' => $this->customSections,
        ]);

        return app(ResumeAuditScoreService::class)->calculateScore($tempResume);
    }

    public function saveResume()
    {
        $this->validate([
            'title' => 'required|string|max:255',
            'fullName' => 'required|string|max:255',
            'email' => 'required|email',
        ]);

        $user = auth()->user();

        $data = [
            'user_id' => $user->id,
            'title' => $this->title,
            'template_slug' => $this->template_slug,
            'personal_info' => [
                'full_name' => $this->fullName,
                'job_title' => $this->jobTitle,
                'email' => $this->email,
                'phone' => $this->phone,
                'location' => $this->location,
                'linkedin' => $this->linkedin,
                'github' => $this->github,
                'summary' => $this->summary,
            ],
            'work_experience' => $this->workExperience,
            'education' => $this->education,
            'skills' => $this->skills,
            'projects' => $this->projects,
            'certifications' => $this->certifications,
            'custom_sections' => $this->customSections,
        ];

        if ($this->userResume && $this->userResume->exists) {
            $this->userResume->update($data);
            $resume = $this->userResume;
        } else {
            $resume = UserResume::create($data);
        }

        session()->flash('message', '🎉 Resume saved successfully! Your ATS Friendliness score is calculated below.');
        return redirect()->route('builder.preview', ['userResume' => $resume->id]);
    }

    public function render()
    {
        return view('livewire.resume-builder', [
            'atsReport' => $this->atsScore,
        ])->layout('layouts.app', ['title' => 'ATS Resume Builder — ATS Pulse']);
    }
}
