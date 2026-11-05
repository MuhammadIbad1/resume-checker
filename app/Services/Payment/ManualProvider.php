<?php

namespace App\Services\Payment;

use App\Models\Payment;
use App\Models\Plan;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ManualProvider implements PaymentProvider
{
    public function initiateCheckout(User $user, Plan $plan, string $billingCycle = 'monthly', array $extraData = []): array
    {
        $method = $extraData['method_details'] ?? 'JazzCash';
        $reference = $extraData['reference'] ?? null;
        $userNotes = $extraData['user_notes'] ?? null;
        $proofFile = $extraData['proof_file'] ?? null;

        $amount = ($billingCycle === 'yearly' ? $plan->price_pkr_yearly : $plan->price_pkr_monthly) ?: ($billingCycle === 'yearly' ? $plan->price_yearly : $plan->price_monthly);
        $currency = $plan->price_pkr_monthly ? 'PKR' : 'USD';

        $proofPath = null;
        if ($proofFile instanceof UploadedFile) {
            $proofPath = $proofFile->store('proofs', 'public');
        }

        $payment = Payment::create([
            'user_id' => $user->id,
            'plan_id' => $plan->id,
            'provider' => 'manual',
            'method_details' => $method,
            'amount' => $amount,
            'currency' => $currency,
            'status' => 'pending',
            'reference' => $reference,
            'proof_path' => $proofPath,
            'user_notes' => $userNotes,
        ]);

        return [
            'type' => 'manual',
            'status' => 'pending_review',
            'message' => 'Payment receipt submitted successfully! Our admin team will verify and activate your plan shortly.',
            'payment_id' => $payment->id,
        ];
    }
}
