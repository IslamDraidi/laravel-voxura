<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaxRate extends Model
{
    protected $fillable = ['name', 'rate', 'is_active'];

    protected $casts = [
        'is_active' => 'boolean',
        'rate' => 'decimal:2',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /** Sum of all active tax rates as a percentage (e.g. 10.00 = 10%) */
    public static function effectiveRate(): float
    {
        return (float) self::active()->sum('rate');
    }
}
