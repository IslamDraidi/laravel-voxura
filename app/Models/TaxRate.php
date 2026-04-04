<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TaxRate extends Model
{
    protected $fillable = [
        'name', 'name_translations', 'type', 'rate', 'scope',
        'category_id', 'channel', 'country', 'region',
        'postal_code_pattern', 'priority', 'is_inclusive',
        'apply_to_shipping', 'valid_from', 'valid_to',
        'is_active', 'metadata',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_inclusive' => 'boolean',
        'apply_to_shipping' => 'boolean',
        'rate' => 'decimal:2',
        'name_translations' => 'array',
        'metadata' => 'array',
        'valid_from' => 'date',
        'valid_to' => 'date',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function getTranslatedNameAttribute(): string
    {
        $locale = app()->getLocale();
        $translations = $this->name_translations;

        return $translations[$locale] ?? $translations['en'] ?? $this->name;
    }

    public function isCurrentlyValid(): bool
    {
        $now = now()->startOfDay();

        if ($this->valid_from && $now->lt($this->valid_from)) {
            return false;
        }

        if ($this->valid_to && $now->gt($this->valid_to)) {
            return false;
        }

        return true;
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeForCountry($query, ?string $code)
    {
        return $query->where(function ($q) use ($code) {
            $q->whereNull('country')->orWhere('country', strtoupper($code));
        });
    }

    public function scopeForChannel($query, ?string $channel)
    {
        return $query->where(function ($q) use ($channel) {
            $q->whereNull('channel')->orWhere('channel', $channel);
        });
    }

    public function scopeForScope($query, string $scope)
    {
        return $query->where('scope', $scope);
    }

    public function scopeCurrentlyValid($query)
    {
        $now = now()->startOfDay();

        return $query->where(function ($q) use ($now) {
            $q->whereNull('valid_from')->orWhere('valid_from', '<=', $now);
        })->where(function ($q) use ($now) {
            $q->whereNull('valid_to')->orWhere('valid_to', '>=', $now);
        });
    }

    /** Sum of all active tax rates as a percentage (e.g. 10.00 = 10%) */
    public static function effectiveRate(): float
    {
        return (float) self::active()->sum('rate');
    }
}
