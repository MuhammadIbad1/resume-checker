<?php

namespace App\Console\Commands;

use App\Services\SubscriptionService;
use Illuminate\Console\Command;

class ExpireSubscriptions extends Command
{
    protected $signature = 'subscriptions:expire';
    protected $description = 'Expire active user subscriptions past their end date.';

    public function handle(SubscriptionService $subscriptionService): int
    {
        $count = $subscriptionService->expireOutdatedSubscriptions();
        $this->info("Successfully expired {$count} outdated subscription(s).");
        return Command::SUCCESS;
    }
}
