<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StoreMessage extends Model
{
    protected $fillable = [
        'store_id', 'customer_name', 'customer_email',
        'subject', 'message', 'status', 'admin_note',
        'store_reply', 'replied_at', 'forwarded_at',
        'auto_approved', 'filter_flags',
    ];

    protected $casts = [
        'filter_flags'  => 'array',
        'auto_approved' => 'boolean',
        'replied_at'    => 'datetime',
        'forwarded_at'  => 'datetime',
    ];

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeFlagged($query)
    {
        return $query->where('status', 'flagged');
    }

    public function scopeApproved($query)
    {
        return $query->whereIn('status', ['approved', 'replied']);
    }

    public function scopeNeedsReview($query)
    {
        return $query->whereIn('status', ['pending', 'flagged']);
    }
}
