<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ShippingZone extends Model
{
    protected $fillable = ['name', 'countries', 'regions', 'is_active'];

    protected $casts = [
        'countries' => 'array',
        'regions' => 'array',
        'is_active' => 'boolean',
    ];

    public function methods(): BelongsToMany
    {
        return $this->belongsToMany(ShippingMethod::class, 'shipping_zone_method')
            ->withPivot(['rate_override', 'is_active']);
    }

    public function containsCountry(string $isoCode): bool
    {
        return in_array(strtoupper($isoCode), array_map('strtoupper', $this->countries ?? []));
    }

    public function containsRegion(string $country, string $region): bool
    {
        $regions = $this->regions ?? [];

        $key = strtoupper($country);
        if (! isset($regions[$key])) {
            return false;
        }

        return in_array(strtoupper($region), array_map('strtoupper', (array) $regions[$key]));
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
