<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = ['name', 'email', 'password', 'role'];
    protected $hidden   = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
        ];
    }

    public function isAdmin(): bool { return (bool) $this->is_admin; }
    public function isBuyer(): bool { return $this->role === 'buyer'; }

    public function cart()   { return $this->hasOne(ShoppingCart::class); }
    public function likes()  { return $this->hasMany(Like::class); }
    public function orders() { return $this->hasMany(Order::class); }

    public function likedProducts()
    {
        return $this->belongsToMany(Product::class, 'likes');
    }

    public function getOrCreateCart(): ShoppingCart
    {
        return $this->cart ?? $this->cart()->create();
    }

    public function likedProductIds(): array
    {
        return $this->likes()->pluck('product_id')->toArray();
    }

    public function cartCount(): int
    {
        return $this->cart?->items()->sum('quantity') ?? 0;
    }

    public function wishlistCount(): int
    {
        return $this->likes()->count();
    }
}