<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Carbon\Carbon;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'is_admin',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_admin' => 'boolean',
        ];
    }

    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class);
    }

    public function activeSubscription(): HasOne
    {
        return $this->hasOne(Subscription::class)
            ->where('status', 'active')
            ->where(function ($query) {
                $query->whereNull('ends_at')
                    ->orWhere('ends_at', '>', now());
            })
            ->latestOfMany();
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function scans(): HasMany
    {
        return $this->hasMany(Scan::class);
    }

    public function resumes(): HasMany
    {
        return $this->hasMany(UserResume::class);
    }

    public function currentPlan(): Plan
    {
        $activeSub = $this->activeSubscription;
        if ($activeSub && $activeSub->plan) {
            return $activeSub->plan;
        }

        return Plan::where('slug', 'free')->first() ?? new Plan([
            'name' => 'Free',
            'slug' => 'free',
            'scan_limit' => 3,
            'jd_limit' => 1,
        ]);
    }

    public function scansThisMonthCount(): int
    {
        return $this->scans()
            ->where('created_at', '>=', Carbon::now()->startOfMonth())
            ->count();
    }

    public function canScan(): bool
    {
        $plan = $this->currentPlan();
        if ($plan->scan_limit === -1) {
            return true;
        }
        return $this->scansThisMonthCount() < $plan->scan_limit;
    }

    public function canMatchJobDescription(): bool
    {
        $plan = $this->currentPlan();
        if ($plan->jd_limit === -1) {
            return true;
        }
        if ($plan->jd_limit === 0) {
            return false;
        }

        $jdsThisMonth = $this->scans()
            ->where('created_at', '>=', Carbon::now()->startOfMonth())
            ->whereNotNull('job_description')
            ->count();

        return $jdsThisMonth < $plan->jd_limit;
    }
}
