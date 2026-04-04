<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class CmsPage extends Model
{
    protected $fillable = ['title', 'slug', 'content', 'status', 'meta_title', 'meta_description', 'sort_order'];

    protected static function booted(): void
    {
        static::creating(function ($page) {
            if (empty($page->slug)) {
                $page->slug = Str::slug($page->title);
            }
        });
    }

    public function isActive(): bool
    {
        return $this->status === 'active';
    }
}
