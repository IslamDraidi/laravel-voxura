<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name', 'slug', 'description',
        'price', 'stock', 'image', 'category_id',
        'sale_badge', 'is_new', 'max_order_quantity', 'stock_alert_threshold',
        'delivery_estimate', 'material', 'fit', 'care_instructions', 'sku',
        'shipping_returns', 'color_swatches', 'has_colors', 'size_guide',
        'model3d_path', 'has_3d_model',
        'model3d_status', 'model3d_queued_at', 'model3d_generated_at',
        'model3d_error', 'model3d_selected_image', 'model3d_job_id',
    ];

    protected function casts(): array
    {
        return [
            'is_new' => 'boolean',
            'has_colors' => 'boolean',
            'max_order_quantity' => 'integer',
            'stock_alert_threshold' => 'integer',
            'color_swatches' => 'array',
            'size_guide' => 'array',
            'has_3d_model' => 'boolean',
            'model3d_queued_at' => 'datetime',
            'model3d_generated_at' => 'datetime',
        ];
    }

    public function is3DReady(): bool
    {
        return $this->has_3d_model && $this->model3d_status === 'ready';
    }

    public function is3DProcessing(): bool
    {
        return in_array($this->model3d_status, ['queued', 'processing'], true);
    }

    public function is3DFailed(): bool
    {
        return $this->model3d_status === 'failed';
    }

    public function get3DModelUrl(): string
    {
        if ($this->is3DReady() && $this->model3d_path) {
            return asset("storage/models/{$this->id}/{$this->model3d_path}");
        }

        return asset('models/placeholder.glb');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort_order');
    }

    public function variants()
    {
        return $this->hasMany(ProductVariant::class)->orderBy('type')->orderBy('value');
    }

    public function feedbacks()
    {
        return $this->hasMany(Feedback::class)->latest();
    }
}