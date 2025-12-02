<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
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
        'role',
        'branch_id',
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
        ];
    }

    /**
     * Check if user is super admin
     */
    public function isSuperAdmin(): bool
    {
        return $this->role === 'super_admin';
    }

    /**
     * Check if user is admin
     */
    public function isAdmin(): bool
    {
        return in_array($this->role, ['super_admin', 'admin']);
    }

    /**
     * Check if user has specific role
     */
    public function hasRole(string $role): bool
    {
        return $this->role === $role;
    }

    /**
     * Check if user has any of the given roles
     */
    public function hasAnyRole(array $roles): bool
    {
        return in_array($this->role, $roles);
    }

    /**
     * Get the branch that the user belongs to
     */
    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    /**
     * Get the user's subscriptions
     */
    public function subscriptions(): HasMany
    {
        return $this->hasMany(UserSubscription::class);
    }

    /**
     * Get the user's active subscription
     */
    public function activeSubscription()
    {
        return $this->subscriptions()->active()->first();
    }

    /**
     * Check if user has an active subscription
     */
    public function hasActiveSubscription(): bool
    {
        $subscription = $this->activeSubscription();

        if (!$subscription) {
            return false;
        }

        // If subscription is marked as active but has expired, mark it as expired
        if ($subscription->status === 'active' && $subscription->isExpired()) {
            $subscription->update(['status' => 'expired']);
            return false;
        }

        return $subscription->isActive();
    }

    /**
     * Check if user can access a specific feature
     */
    public function canAccessFeature(string $feature, $requiredTier = null): bool
    {
        // Super admin can access everything
        if ($this->isSuperAdmin()) {
            return true;
        }

        $subscription = $this->activeSubscription();

        if (!$subscription) {
            return false;
        }

        $plan = $subscription->plan;

        // Check if plan has the feature
        if (!$plan->hasFeature($feature)) {
            return false;
        }

        // If specific tier is required, check it
        if ($requiredTier && $plan->slug !== $requiredTier) {
            return false;
        }

        return true;
    }

    /**
     * Get usage limit for a specific feature
     */
    public function getUsageLimit(string $feature)
    {
        $subscription = $this->activeSubscription();

        if (!$subscription) {
            return 0;
        }

        return $subscription->plan->getLimit($feature) ?? 0;
    }

    /**
     * Check if user has exceeded usage limit
     */
    public function hasExceededLimit(string $feature, int $currentUsage): bool
    {
        $limit = $this->getUsageLimit($feature);

        if ($limit === 0) {
            return false; // Unlimited
        }

        return $currentUsage >= $limit;
    }

    /**
     * Get the current branch for the user, with fallback to default branch for super admins
     */
    public function getCurrentBranch()
    {
        if ($this->branch) {
            return $this->branch;
        }

        // For super admins without a branch, return the default branch
        if ($this->isSuperAdmin()) {
            return Branch::where('name', 'Kantor Pusat')->first();
        }

        return null;
    }

    /**
     * Get the current branch ID, with fallback for super admins
     */
    public function getCurrentBranchId()
    {
        $branch = $this->getCurrentBranch();
        return $branch ? $branch->id : null;
    }
}
