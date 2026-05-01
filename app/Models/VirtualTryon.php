<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class VirtualTryon extends Model
{
    protected $table = 'virtual_tryons';

    protected $fillable = [
        'user_id',
        'product_id',
        'photo_path',
        'body_model_path',
        'result_model_path',
        'status',
        'height_cm',
        'error_message',
        'photo_consent',
        'queued_at',
        'body_generated_at',
        'result_generated_at',
        'expires_at',
    ];

    protected function casts(): array
    {
        return [
            'photo_consent'       => 'boolean',
            'queued_at'           => 'datetime',
            'body_generated_at'   => 'datetime',
            'result_generated_at' => 'datetime',
            'expires_at'          => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function isReady(): bool
    {
        return $this->status === 'ready' && !empty($this->result_model_path);
    }

    public function isProcessing(): bool
    {
        return in_array($this->status, ['pending', 'processing_body', 'processing_fit'], true);
    }

    public function isFailed(): bool
    {
        return $this->status === 'failed';
    }

    public function getResultUrl(): ?string
    {
        if (!$this->result_model_path) {
            return null;
        }
        return Storage::disk('public')->url($this->result_model_path);
    }

    public function getBodyUrl(): ?string
    {
        if (!$this->body_model_path) {
            return null;
        }
        return Storage::disk('public')->url($this->body_model_path);
    }
}
