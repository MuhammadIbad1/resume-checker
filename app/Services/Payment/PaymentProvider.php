<?php

namespace App\Services\Payment;

use App\Models\Plan;
use App\Models\User;

interface PaymentProvider
{
    public function initiateCheckout(User $user, Plan $plan, string $billingCycle = 'monthly', array $extraData = []): array;
}
