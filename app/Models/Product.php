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
        ];
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
