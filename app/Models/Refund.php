<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Refund extends Model
{
    protected $fillable = [
        'order_id', 'payment_id', 'admin_id', 'gateway', 'gateway_refund_id',
        'amount', 'reason', 'status', 'gateway_response', 'refunded_at',
    ];

    protected $casts = [
        'gateway_response' => 'array',
        'refunded_at' => 'datetime',
        'amount' => 'decimal:2',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }
}
