<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShoppingCart extends Model
{
    protected $fillable = ['user_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(CartItem::class, 'shopping_cart_id');
    }

    public function total()
    {
        return $this->items->sum(fn($item) => $item->product->price * $item->quantity);
    }

    public function itemCount()
    {
        return $this->items->sum('quantity');
    }
}