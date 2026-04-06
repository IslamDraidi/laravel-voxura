<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'order_id', 'amount', 'status', 'payment_method', 'transaction_id',
        'failure_reason', 'failure_code', 'attempts', 'last_attempted_at',
        'gateway', 'gateway_response',
    ];

    protected $casts = [
        'last_attempted_at' => 'datetime',
        'gateway_response' => 'array',
        'amount' => 'decimal:2',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function refunds()
    {
        return $this->hasMany(Refund::class);
    }
}
