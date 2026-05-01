<?php

namespace App\Providers;

use App\Models\Category;
use App\Services\AI\Contracts\TryOnBodyProvider;
use App\Services\AI\Sam3DBodyFalService;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(TryOnBodyProvider::class, function () {
            return match (config('model3d.tryon.body_provider', 'fal')) {
                'fal'   => new Sam3DBodyFalService(),
                default => new Sam3DBodyFalService(),
            };
        });
    }

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
