<?php

namespace App\Providers;

use App\Models\Category;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        // Share nav categories with the main layout on every page
        View::composer('components.layout', function ($view) {
            $navCategories = Category::whereNull('parent_id')
                ->with(['children' => fn($q) => $q->whereHas('products')->orderBy('name')])
                ->orderBy('name')
                ->get()
                ->filter(fn($c) => $c->children->isNotEmpty() || $c->products()->exists())
                ->values();

            $view->with('navCategories', $navCategories);
        });
    }
}
