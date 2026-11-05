<?php

use App\Http\Middleware\AdminMiddleware;
use App\Livewire\Auth\Login;
use App\Livewire\Auth\Register;
use App\Livewire\BillingPortal;
use App\Livewire\MyResumes;
use App\Livewire\ResumeBuilder;
use App\Livewire\ResumeScanner;
use App\Livewire\ScanResults;
use App\Livewire\UserDashboard;
use App\Models\Payment;
use App\Models\UserResume;
use App\Services\SubscriptionService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// 1. Public Landing Page
Route::get('/', function () {
    return view('welcome');
})->name('home');

// 2. Guest Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', Login::class)->name('login');
    Route::get('/register', Register::class)->name('register');
});

// Logout Route
Route::post('/logout', function () {
    Auth::logout();
    session()->invalidate();
    session()->regenerateToken();
    return redirect()->route('home');
})->name('logout')->middleware('auth');

// 3. Authenticated User Routes
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', UserDashboard::class)->name('dashboard');
    Route::get('/scanner', ResumeScanner::class)->name('scanner');
    Route::get('/results/{scan}', ScanResults::class)->name('scan.results');
    Route::get('/billing', BillingPortal::class)->name('billing');

    // ATS Resume Builder Routes
    Route::get('/my-resumes', MyResumes::class)->name('resumes.index');
    Route::get('/builder/create', ResumeBuilder::class)->name('builder.create');
    Route::get('/builder/{userResume}/edit', ResumeBuilder::class)->name('builder.edit');
    Route::get('/builder/{userResume}/preview', function (UserResume $userResume) {
        if ($userResume->user_id !== auth()->id() && !auth()->user()?->is_admin) {
            abort(403, 'Unauthorized access to resume preview.');
        }
        $viewMap = [
            'modern_ats' => 'resume-templates.modern_ats',
            'classic_clean' => 'resume-templates.classic_clean',
            'minimal_tech' => 'resume-templates.minimal_tech',
            'executive_pro' => 'resume-templates.executive_pro',
            'tech_innovator' => 'resume-templates.tech_innovator',
            'ivy_academic' => 'resume-templates.ivy_academic',
            'compact_clean' => 'resume-templates.compact_clean',
            'nordic_minimal' => 'resume-templates.nordic_minimal',
        ];
        $template = $viewMap[$userResume->template_slug] ?? 'resume-templates.modern_ats';
        $atsReport = app(\App\Services\Ats\ResumeAuditScoreService::class)->calculateScore($userResume);
        return view($template, [
            'resume' => $userResume,
            'atsReport' => $atsReport,
        ]);
    })->name('builder.preview');

    // Stripe Simulation Callback Route
    Route::get('/billing/stripe/demo-success', function (SubscriptionService $subscriptionService) {
        $paymentId = request('payment_id');
        $payment = Payment::find($paymentId);

        if ($payment && $payment->user_id === auth()->id() && $payment->plan) {
            $subscriptionService->activateSubscription(
                $payment->user,
                $payment->plan,
                'stripe',
                'monthly',
                $payment
            );
            session()->flash('message', '🎉 Payment successful! Your ' . $payment->plan->name . ' subscription is now active.');
        }

        return redirect()->route('dashboard');
    })->name('billing.stripe.demo_success');
});
