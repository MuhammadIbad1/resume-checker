<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;

class UserDashboard extends Component
{
    use WithPagination;

    public function render()
    {
        $user = auth()->user();
        $scans = $user ? $user->scans()->latest()->paginate(10) : collect();
        $plan = $user ? $user->currentPlan() : null;

        return view('livewire.user-dashboard', [
            'user' => $user,
            'plan' => $plan,
            'scans' => $scans,
            'scansThisMonth' => $user ? $user->scansThisMonthCount() : 0,
        ])->layout('layouts.app', ['title' => 'Dashboard — ATS Pulse']);
    }
}
