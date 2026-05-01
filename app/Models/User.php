<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'role', 'is_blocked',
        'has_body_model', 'body_model_path', 'body_model_generated_at', 'body_height_cm',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at'        => 'datetime',
            'password'                 => 'hashed',
            'has_body_model'           => 'boolean',
            'body_model_generated_at'  => 'datetime',
        ];
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isBlocked(): bool
    {
        return (bool) $this->is_blocked;
    }

    public function isBuyer(): bool
    {
        return $this->role === 'buyer';
    }

    public function cart()
    {
        return $this->hasOne(ShoppingCart::class);
    }

    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

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

    public function tryons()
    {
        return $this->hasMany(VirtualTryon::class);
    }

    public function hasReusableBodyModel(): bool
    {
        return $this->has_body_model
            && !empty($this->body_model_path)
            && Storage::disk('public')->exists($this->body_model_path);
    }
}
