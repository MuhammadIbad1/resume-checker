<?php

namespace App\Services\Payment;

use App\Models\Payment;
use App\Models\Plan;
use App\Models\User;
use App\Services\SubscriptionService;

class StripeProvider implements PaymentProvider
{
    protected SubscriptionService $subscriptionService;

    public function __construct(SubscriptionService $subscriptionService)
    {
        $this->subscriptionService = $subscriptionService;
    }

    public function initiateCheckout(User $user, Plan $plan, string $billingCycle = 'monthly', array $extraData = []): array
    {
        $amount = $billingCycle === 'yearly' ? $plan->price_yearly : $plan->price_monthly;

        // In test/demo environment, create a pending payment and return a mock Stripe checkout redirect URL
        $payment = Payment::create([
            'user_id' => $user->id,
            'plan_id' => $plan->id,
            'provider' => 'stripe',
            'method_details' => 'Stripe Card (Simulated)',
            'amount' => $amount,
            'currency' => 'USD',
            'status' => 'pending',
            'reference' => 'cs_test_' . bin2hex(random_bytes(10)),
        ]);

        return [
            'type' => 'stripe',
            'redirect_url' => route('billing.stripe.demo_success', ['payment_id' => $payment->id]),
            'payment_id' => $payment->id,
            'reference' => $payment->reference,
        ];
    }
}
