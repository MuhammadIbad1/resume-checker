<?php

namespace App\Livewire;

use App\Models\Plan;
use App\Models\Setting;
use App\Services\Payment\ManualProvider;
use App\Services\Payment\StripeProvider;
use Livewire\Component;
use Livewire\WithFileUploads;

class BillingPortal extends Component
{
    use WithFileUploads;

    public bool $showPaymentModal = false;
    public ?Plan $selectedPlan = null;
    public string $billingCycle = 'monthly'; // 'monthly', 'yearly'
    public string $paymentMethod = 'manual'; // 'stripe', 'manual'
    public string $manualMethod = 'JazzCash'; // 'JazzCash', 'Easypaisa', 'Bank Transfer'
    public string $reference = '';
    public string $userNotes = '';
    public $proofFile;

    public function selectPlan(int $planId)
    {
        $this->selectedPlan = Plan::find($planId);
        $this->showPaymentModal = true;
    }

    public function closeModal()
    {
        $this->showPaymentModal = false;
        $this->reset(['reference', 'userNotes', 'proofFile']);
    }

    public function processCheckout(StripeProvider $stripe, ManualProvider $manual)
    {
        $user = auth()->user();
        if (!$user || !$this->selectedPlan) {
            return;
        }

        if ($this->paymentMethod === 'stripe') {
            $result = $stripe->initiateCheckout($user, $this->selectedPlan, $this->billingCycle);
            session()->flash('message', 'Redirecting to Stripe Test Checkout...');
            return redirect()->to($result['redirect_url']);
        }

        // Manual Payment Proof Upload
        $this->validate([
            'reference' => 'nullable|string|max:100',
            'proofFile' => 'nullable|image|max:5120', // max 5MB image proof
        ]);

        $result = $manual->initiateCheckout($user, $this->selectedPlan, $this->billingCycle, [
            'method_details' => $this->manualMethod,
            'reference' => $this->reference,
            'user_notes' => $this->userNotes,
            'proof_file' => $this->proofFile,
        ]);

        $this->closeModal();
        session()->flash('message', $result['message']);
        return redirect()->route('dashboard');
    }

    public function render()
    {
        $user = auth()->user();
        $currentPlan = $user ? $user->currentPlan() : null;
        $activeSub = $user ? $user->activeSubscription : null;
        $plans = Plan::where('is_active', true)->get();

        $jazzcash = json_decode(Setting::get('manual_payment_jazzcash', '{}'), true);
        $easypaisa = json_decode(Setting::get('manual_payment_easypaisa', '{}'), true);
        $bank = json_decode(Setting::get('manual_payment_bank', '{}'), true);

        return view('livewire.billing-portal', [
            'currentPlan' => $currentPlan,
            'activeSub' => $activeSub,
            'plans' => $plans,
            'jazzcash' => $jazzcash,
            'easypaisa' => $easypaisa,
            'bank' => $bank,
        ])->layout('layouts.app', ['title' => 'Plans & Billing — ATS Pulse']);
    }
}
