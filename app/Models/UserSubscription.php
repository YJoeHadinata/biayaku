<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserSubscription extends Model
{
    protected $fillable = [
        'user_id',
        'subscription_plan_id',
        'status',
        'current_period_start',
        'current_period_end',
        'trial_ends_at',
        'ends_at',
        'metadata',
    ];

    protected $casts = [
        'current_period_start' => 'datetime',
        'current_period_end' => 'datetime',
        'trial_ends_at' => 'datetime',
        'ends_at' => 'datetime',
        'metadata' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function plan(): BelongsTo
    {
        return $this->belongsTo(SubscriptionPlan::class, 'subscription_plan_id');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeCancelled($query)
    {
        return $query->where('status', 'cancelled');
    }

    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function isActive(): bool
    {
        return $this->status === 'active' &&
            (!$this->current_period_end || $this->current_period_end->isFuture());
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isTrial(): bool
    {
        return $this->trial_ends_at && $this->trial_ends_at->isFuture();
    }

    public function isExpired(): bool
    {
        return $this->status === 'expired' ||
            ($this->current_period_end && $this->current_period_end->isPast());
    }

    public function approve()
    {
        $this->update(['status' => 'active']);
    }

    public function reject()
    {
        $this->update(['status' => 'cancelled']);
    }

    public function cancel()
    {
        // Free subscriptions cannot be cancelled
        if ($this->plan->slug === 'free') {
            throw new \Exception('Free subscriptions cannot be cancelled.');
        }

        $this->update(['status' => 'cancelled']);
    }

    public function reactivate()
    {
        $this->update(['status' => 'active']);
    }
}
