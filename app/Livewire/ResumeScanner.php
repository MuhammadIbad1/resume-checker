<?php

namespace App\Livewire;

use App\Services\Ats\ScoringEngineService;
use Livewire\Component;
use Livewire\WithFileUploads;

class ResumeScanner extends Component
{
    use WithFileUploads;

    public $resumeFile;
    public string $jobDescription = '';
    public bool $isScanning = false;

    protected $rules = [
        'resumeFile' => 'required|file|mimes:pdf,docx|max:5120', // Max 5MB
        'jobDescription' => 'nullable|string|max:10000',
    ];

    public function processScan(ScoringEngineService $engine)
    {
        $this->validate();

        // 1. Security Check: File upload safety verification
        $originalName = $this->resumeFile->getClientOriginalName();
        $extension = strtolower($this->resumeFile->getClientOriginalExtension());

        if (!in_array($extension, ['pdf', 'docx'])) {
            $this->addError('resumeFile', 'Security Violation: Only authentic PDF and DOCX files are allowed.');
            return;
        }

        // Prevent double extension attacks (e.g. payload.php.pdf or exploit.exe.pdf)
        if (preg_match('/\.(php|phtml|phar|sh|exe|bat|cmd|vbs|js|py|pl|cgi|asp|aspx)\./i', $originalName)) {
            $this->addError('resumeFile', 'Security Blocked: Double extension executable names are prohibited.');
            return;
        }

        // Validate file header magic bytes
        $realPath = $this->resumeFile->getRealPath();
        $fileHeader = @file_get_contents($realPath, false, null, 0, 8);
        if ($extension === 'pdf' && (!str_starts_with($fileHeader, '%PDF'))) {
            $this->addError('resumeFile', 'Security Blocked: File header is not a valid PDF binary.');
            return;
        }
        if ($extension === 'docx' && (!str_starts_with($fileHeader, "PK\x03\x04"))) {
            $this->addError('resumeFile', 'Security Blocked: File header is not a valid OpenXML DOCX binary.');
            return;
        }

        $user = auth()->user();

        // Check plan limits
        if ($user && !$user->canScan()) {
            session()->flash('error', 'You have reached your monthly scan limit. Upgrade your plan to continue scanning!');
            return redirect()->route('billing');
        }

        if ($user && !empty($this->jobDescription) && !$user->canMatchJobDescription()) {
            session()->flash('error', 'Your current plan does not include Job Description matching. Upgrade to Pro to unlock keyword matching!');
            return redirect()->route('billing');
        }

        $filePath = $this->resumeFile->store('resumes', 'public');
        $absolutePath = storage_path('app/public/' . $filePath);

        // Sanitize client filename for storage & display
        $safeFileName = preg_replace('/[^a-zA-Z0-9_\-\. ]/', '', $originalName);

        $scan = $engine->evaluateResume(
            $absolutePath,
            $this->jobDescription,
            $user,
            $safeFileName
        );

        return redirect()->route('scan.results', ['scan' => $scan->id]);
    }

    public function render()
    {
        $user = auth()->user();
        return view('livewire.resume-scanner', [
            'scansUsed' => $user ? $user->scansThisMonthCount() : 0,
            'scanLimit' => $user ? $user->currentPlan()->scan_limit : 3,
        ])->layout('layouts.app', ['title' => 'Scan Resume — ATS Pulse']);
    }
}
