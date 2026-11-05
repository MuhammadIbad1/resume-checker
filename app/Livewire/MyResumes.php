<?php

namespace App\Livewire;

use App\Models\UserResume;
use App\Services\Ats\ScoringEngineService;
use Livewire\Component;

class MyResumes extends Component
{
    public function auditResume(UserResume $userResume, ScoringEngineService $engine)
    {
        $user = auth()->user();
        if (!$user) return;

        // Security Authorization Check (Prevent IDOR)
        if ($userResume->user_id !== $user->id && !$user->is_admin) {
            abort(403, 'Unauthorized access.');
        }

        // Convert structured resume to plain text string
        $plainText = $userResume->toPlainString();

        // Create temporary text file for the parser engine
        $tempPath = storage_path('app/public/resumes/built_' . $userResume->id . '.txt');
        file_put_contents($tempPath, $plainText);

        $scan = $engine->evaluateResume(
            $tempPath,
            null,
            $user,
            $userResume->title . ' (Built CV).pdf'
        );

        session()->flash('message', '🎉 Resume audited successfully! Here is your ATS Compatibility Score.');
        return redirect()->route('scan.results', ['scan' => $scan->id]);
    }

    public function deleteResume(UserResume $userResume)
    {
        if ($userResume->user_id === auth()->id()) {
            $userResume->delete();
            session()->flash('message', 'Resume deleted.');
        }
    }

    public function render()
    {
        $resumes = auth()->user() ? auth()->user()->resumes()->latest()->get() : collect();

        return view('livewire.my-resumes', [
            'resumes' => $resumes,
        ])->layout('layouts.app', ['title' => 'My ATS Resumes — ATS Pulse']);
    }
}
