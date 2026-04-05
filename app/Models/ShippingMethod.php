<?php

namespace App\Models;

use App\Traits\HasTranslations;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ShippingMethod extends Model
{
    use HasTranslations;

    protected $fillable = [
        'name', 'name_translations', 'type', 'price', 'base_rate',
        'per_unit_rate', 'weight_rate', 'weight_unit', 'free_above',
        'min_order_amount', 'max_order_amount', 'max_weight',
        'channel', 'estimated_delivery', 'estimated_days_min',
        'estimated_days_max', 'sort_order', 'is_active', 'metadata',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'price' => 'decimal:2',
        'base_rate' => 'decimal:2',
        'per_unit_rate' => 'decimal:2',
        'weight_rate' => 'decimal:4',
        'free_above' => 'decimal:2',
        'min_order_amount' => 'decimal:2',
        'max_order_amount' => 'decimal:2',
        'max_weight' => 'decimal:4',
        'name_translations' => 'array',
        'metadata' => 'array',
    ];

    public function zones(): BelongsToMany
    {
        return $this->belongsToMany(ShippingZone::class, 'shipping_zone_method')
            ->withPivot(['rate_override', 'is_active']);
    }

    public function getFormattedRateAttribute(): string
    {
        return match ($this->type) {
            'flat' => '$'.number_format($this->base_rate, 2).' flat',
            'per_unit' => '$'.number_format($this->base_rate, 2).' + $'.number_format($this->per_unit_rate, 2).'/item',
            'weight_based' => '$'.number_format($this->base_rate, 2).' + $'.number_format($this->weight_rate, 4).'/'.$this->weight_unit,
            'free' => 'Free',
            'custom' => 'Custom rate',
            default => '$'.number_format($this->base_rate, 2),
        };
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeForChannel($query, ?string $channel)
    {
        return $query->where(function ($q) use ($channel) {
            $q->whereNull('channel')->orWhere('channel', $channel);
        });
    }

    public function scopeForZone($query, int $zoneId)
    {
        return $query->whereHas('zones', fn ($q) => $q->where('shipping_zones.id', $zoneId));
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order');
    }
}
