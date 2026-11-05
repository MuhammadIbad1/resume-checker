<?php

namespace App\Services;

use App\Models\Payment;
use App\Models\Plan;
use App\Models\Subscription;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class SubscriptionService
{
    public function activateSubscription(User $user, Plan $plan, string $provider = 'manual', string $billingCycle = 'monthly', ?Payment $payment = null): Subscription
    {
        return DB::transaction(function () use ($user, $plan, $provider, $billingCycle, $payment) {
            // Cancel any previous active subscriptions
            Subscription::where('user_id', $user->id)
                ->where('status', 'active')
                ->update([
                    'status' => 'cancelled',
                    'cancelled_at' => Carbon::now(),
                ]);

            $duration = $billingCycle === 'yearly' ? 12 : 1;
            $startsAt = Carbon::now();
            $endsAt = Carbon::now()->addMonths($duration);

            $subscription = Subscription::create([
                'user_id' => $user->id,
                'plan_id' => $plan->id,
                'provider' => $provider,
                'status' => 'active',
                'billing_cycle' => $billingCycle,
                'starts_at' => $startsAt,
                'ends_at' => $endsAt,
            ]);

            if ($payment) {
                $payment->update([
                    'status' => 'paid',
                    'subscription_id' => $subscription->id,
                    'reviewed_at' => Carbon::now(),
                ]);
            }

            return $subscription;
        });
    }

    public function rejectPayment(Payment $payment, User $admin, string $reason): void
    {
        $payment->update([
            'status' => 'rejected',
            'admin_notes' => $reason,
            'reviewed_by' => $admin->id,
            'reviewed_at' => Carbon::now(),
        ]);
    }

    public function expireOutdatedSubscriptions(): int
    {
        return Subscription::where('status', 'active')
            ->whereNotNull('ends_at')
            ->where('ends_at', '<=', Carbon::now())
            ->update([
                'status' => 'expired',
            ]);
    }
}
