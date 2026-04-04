<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    protected $fillable = ['product_id', 'type', 'value', 'price_modifier', 'stock'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function label(): string
    {
        return "{$this->type}: {$this->value}";
    }

    public function effectiveStock(Product $product): int
    {
        return $this->stock ?? $product->stock;
    }
}
