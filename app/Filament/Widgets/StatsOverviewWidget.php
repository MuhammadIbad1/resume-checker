<?php

namespace App\Filament\Widgets;

use App\Models\Payment;
use App\Models\Scan;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverviewWidget extends BaseWidget
{
    protected function getStats(): array
    {
        $pendingPaymentsCount = Payment::where('status', 'pending')->count();
        $totalUsers = User::count();
        $totalScans = Scan::count();
        $totalRevenue = Payment::where('status', 'paid')->sum('amount');

        return [
            Stat::make('Total Users', $totalUsers)
                ->description('Registered Job Seekers & Admins')
                ->descriptionIcon('heroicon-m-users')
                ->color('primary'),

            Stat::make('Pending Payment Proofs', $pendingPaymentsCount)
                ->description('Awaiting Admin Approval Queue')
                ->descriptionIcon('heroicon-m-clock')
                ->color($pendingPaymentsCount > 0 ? 'warning' : 'success'),

            Stat::make('Total Resumes Scanned', $totalScans)
                ->description('Completed ATS Audits')
                ->descriptionIcon('heroicon-m-document-check')
                ->color('success'),

            Stat::make('Total Paid Revenue', 'Rs. ' . number_format($totalRevenue))
                ->description('Approved SaaS Subscriptions')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('success'),
        ];
    }
}
