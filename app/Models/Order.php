<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id', 'coupon_id', 'shipping_method_id', 'shipping_zone_id',
        'total_amount', 'discount_amount', 'tax_amount', 'shipping_cost',
        'tax_breakdown', 'shipping_tax_amount', 'subtotal', 'grand_total',
        'currency', 'channel', 'coupon_code', 'status', 'shipping_address',
    ];

    protected $casts = [
        'tax_breakdown' => 'array',
        'subtotal' => 'decimal:2',
        'grand_total' => 'decimal:2',
        'shipping_tax_amount' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function coupon()
    {
        return $this->belongsTo(Coupon::class);
    }

    public function shippingMethod()
    {
        return $this->belongsTo(ShippingMethod::class);
    }

    public function shippingZone()
    {
        return $this->belongsTo(ShippingZone::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function grandTotal(): float
    {
        return max(0, $this->total_amount - $this->discount_amount + $this->tax_amount + $this->shipping_cost);
    }

    public function statusColor(): string
    {
        return match ($this->status) {
            'pending' => '#f59e0b',
            'processing' => '#3b82f6',
            'shipped' => '#8b5cf6',
            'delivered' => '#16a34a',
            'cancelled' => '#ef4444',
            default => '#6b7280',
        };
    }

    public function statusBg(): string
    {
        return match ($this->status) {
            'pending' => '#fef3c7',
            'processing' => '#dbeafe',
            'shipped' => '#ede9fe',
            'delivered' => '#dcfce7',
            'cancelled' => '#fee2e2',
            default => '#f3f4f6',
        };
    }
}
