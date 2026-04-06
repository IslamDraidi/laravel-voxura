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

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function refunds()
    {
        return $this->hasMany(Refund::class);
    }

    public function totalRefunded(): float
    {
        return (float) $this->refunds()->where('status', 'completed')->sum('amount');
    }

    public function refundableAmount(): float
    {
        return max(0, (float) $this->grand_total - $this->totalRefunded());
    }

    public function isFullyRefunded(): bool
    {
        return $this->totalRefunded() >= (float) $this->grand_total;
    }

    public function isPartiallyRefunded(): bool
    {
        $refunded = $this->totalRefunded();
        return $refunded > 0 && $refunded < (float) $this->grand_total;
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
            'delivered', 'paid' => '#16a34a',
            'cancelled', 'payment_blocked' => '#ef4444',
            'refunded' => '#8b5cf6',
            'partially_refunded' => '#d97706',
            default => '#6b7280',
        };
    }

    public function statusBg(): string
    {
        return match ($this->status) {
            'pending' => '#fef3c7',
            'processing' => '#dbeafe',
            'shipped' => '#ede9fe',
            'delivered', 'paid' => '#dcfce7',
            'cancelled', 'payment_blocked' => '#fee2e2',
            'refunded' => '#ede9fe',
            'partially_refunded' => '#fef3c7',
            default => '#f3f4f6',
        };
    }
}
