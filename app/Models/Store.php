<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Store extends Model
{
    protected $fillable = [
        'name', 'slug', 'tagline', 'description',
        'logo_path', 'banner_path', 'accent_color',
        'status', 'is_featured', 'featured_label',
        'social_links', 'has_3d_products', 'category_tags',
        'products_count', 'owner_id',
        'plan_type', 'subscription_fee', 'subscription_expires_at',
        'subscription_active', 'commission_rate',
        'visit_count', 'last_visited_at',
        'products_approved', 'products_pending',
        'admin_notes', 'approved_at', 'rejected_at', 'suspended_at',
        'rejection_reason', 'suspension_reason', 'expiry_reminder_sent',
        // Onboarding
        'onboarding_status', 'business_name', 'business_id', 'business_phone',
        'billing_cycle', 'monthly_fee', 'yearly_fee',
        'payment_reference', 'payment_method', 'bank_transfer_pending',
        'last_payment_at', 'subscription_start', 'published_at',
        'credits_balance', 'credits_granted_total', 'credits_used_total',
        'credits_bonus', 'credits_last_topped_up_at',
    ];

    protected function casts(): array
    {
        return [
            'is_featured'             => 'boolean',
            'has_3d_products'         => 'boolean',
            'category_tags'           => 'array',
            'social_links'            => 'array',
            'subscription_active'     => 'boolean',
            'subscription_expires_at' => 'datetime',
            'last_visited_at'         => 'datetime',
            'approved_at'             => 'datetime',
            'rejected_at'             => 'datetime',
            'suspended_at'            => 'datetime',
            'expiry_reminder_sent'    => 'boolean',
            'subscription_fee'        => 'decimal:2',
            'commission_rate'         => 'decimal:2',
            'bank_transfer_pending'   => 'boolean',
            'last_payment_at'         => 'datetime',
            'subscription_start'      => 'datetime',
            'published_at'              => 'datetime',
            'monthly_fee'               => 'decimal:2',
            'yearly_fee'                => 'decimal:2',
            'credits_last_topped_up_at' => 'datetime',
        ];
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function messages(): HasMany
    {
        return $this->hasMany(StoreMessage::class);
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    public function scopeSuspended($query)
    {
        return $query->where('status', 'suspended');
    }

    public function scopeMostVisited($query)
    {
        return $query->orderByDesc('visit_count');
    }

    public function scopeLeastVisited($query)
    {
        return $query->orderBy('visit_count');
    }

    public function getIsExpiredAttribute(): bool
    {
        return $this->subscription_expires_at
            && $this->subscription_expires_at->isPast();
    }

    public function getDaysUntilExpiryAttribute(): ?int
    {
        if (!$this->subscription_expires_at) return null;
        return max(0, (int) now()->diffInDays($this->subscription_expires_at, false));
    }

    public function getLogo_urlAttribute(): ?string
    {
        return $this->logo_path ? asset($this->logo_path) : null;
    }

    public function getBanner_urlAttribute(): ?string
    {
        return $this->banner_path ? asset($this->banner_path) : null;
    }

    public function getIsActiveAttribute(): bool
    {
        return $this->status === 'approved';
    }

    public function getCommissionMultiplierAttribute(): float
    {
        return 1 - ((float) ($this->commission_rate ?? 0) / 100);
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function can3D(): bool
    {
        $plan = config("voxura_plans.{$this->plan_type}");
        return ($plan['has_3d'] ?? false) && $this->credits_balance > 0;
    }

    public function has3DAccess(): bool
    {
        $plan = config("voxura_plans.{$this->plan_type}");
        return $plan['has_3d'] ?? false;
    }

    public function monthlyCredits(): int
    {
        return (int) config("voxura_plans.{$this->plan_type}.monthly_3d_credits", 0);
    }
}
