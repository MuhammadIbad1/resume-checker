<?php

namespace App\Livewire;

use App\Models\Scan;
use Livewire\Component;

class ScanResults extends Component
{
    public Scan $scan;
    public string $activeTab = 'breakdown'; // 'breakdown', 'security', 'keywords', 'plaintext'

    public function mount(Scan $scan)
    {
        // Security Authorization Check (Prevent IDOR)
        if ($scan->user_id && $scan->user_id !== auth()->id() && !auth()->user()?->is_admin) {
            abort(403, 'Unauthorized access to this scan report.');
        }

        $this->scan = $scan->load('results');
    }

    public function setTab(string $tab)
    {
        $this->activeTab = $tab;
    }

    public function render()
    {
        $keywordResult = $this->scan->results->firstWhere('rule_code', 'KW_MATCH_RATE');
        $keywordMeta = $keywordResult ? ($keywordResult->metadata ?? []) : [];

        return view('livewire.scan-results', [
            'keywordResult' => $keywordResult,
            'keywordMeta' => $keywordMeta,
        ])->layout('layouts.app', ['title' => "Scan Report #{$this->scan->id} — ATS Pulse"]);
    }
}
