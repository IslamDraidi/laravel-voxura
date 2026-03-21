<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'total_amount',
        'status',
        'shipping_address',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function statusColor(): string
    {
        return match($this->status) {
            'pending'    => '#f59e0b',
            'processing' => '#3b82f6',
            'shipped'    => '#8b5cf6',
            'delivered'  => '#16a34a',
            'cancelled'  => '#ef4444',
            default      => '#6b7280',
        };
    }

    public function statusBg(): string
    {
        return match($this->status) {
            'pending'    => '#fef3c7',
            'processing' => '#dbeafe',
            'shipped'    => '#ede9fe',
            'delivered'  => '#dcfce7',
            'cancelled'  => '#fee2e2',
            default      => '#f3f4f6',
        };
    }
}