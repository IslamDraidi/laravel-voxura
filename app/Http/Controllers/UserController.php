<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $sort       = $request->get('sort', 'newest');
        $sizes      = array_values(array_filter((array) $request->get('size', [])));
        $colors     = array_values(array_filter((array) $request->get('color', [])));
        $priceRange = $request->get('price_range', '');

        // Support both category[] (filter form) and cat= (nav mega-dropdown)
        $rawCategory = $request->get('category') ?: ($request->get('cat') ? [$request->get('cat')] : null);
        if (is_array($rawCategory)) {
            $categoryIds = array_values(array_filter($rawCategory));
            $categoryId  = count($categoryIds) === 1 ? $categoryIds[0] : null;
        } else {
            $categoryId  = $rawCategory ?: null;
            $categoryIds = $categoryId ? [$categoryId] : [];
        }

        $query = Product::with('category')->whereNull('deleted_at');

        if (!empty($sizes)) {
            $query->whereHas('variants', fn ($q) => $q->where('type', 'Size')->whereIn('value', $sizes));
        }
        if (!empty($colors)) {
            $query->whereHas('variants', fn ($q) => $q->where('type', 'Color')->whereIn('value', $colors));
        }

        [$priceMin, $priceMax] = match ($priceRange) {
            'under_50' => [null, 50],
            '50_150'   => [50, 150],
            '150_300'  => [150, 300],
            '300_plus' => [300, null],
            default    => [null, null],
        };
        if ($priceMin !== null) $query->where('price', '>=', $priceMin);
        if ($priceMax !== null) $query->where('price', '<=', $priceMax);

        if (!empty($categoryIds)) {
            $query->whereIn('category_id', $categoryIds);
        }

        $query = match ($sort) {
            'price_asc'  => $query->orderBy('price'),
            'price_desc' => $query->orderByDesc('price'),
            'popular'    => $query->orderByRaw('(SELECT COUNT(*) FROM order_items WHERE order_items.product_id = products.id) DESC'),
            default      => $query->latest(),
        };

        $products         = $query->get();
        $featuredProducts = Product::with('category')->whereNull('deleted_at')->latest()->take(3)->get();
        $banners          = Banner::active()->get();
        $categories       = Category::all();

        $productCounts = Product::whereNull('deleted_at')
            ->select('category_id', DB::raw('count(*) as cnt'))
            ->groupBy('category_id')
            ->pluck('cnt', 'category_id');

        $availableSizes = ProductVariant::where('type', 'Size')
            ->distinct()->pluck('value')->sort()->values();
        if ($availableSizes->isEmpty()) {
            $availableSizes = collect(['XS', 'S', 'M', 'L', 'XL', 'XXL']);
        }

        $availableColors = ProductVariant::where('type', 'Color')
            ->distinct()->pluck('value')->values();

        $likedIds = auth()->check() ? auth()->user()->likedProductIds() : [];

        $activeCategory = count($categoryIds) === 1
            ? $categories->firstWhere('id', (int) $categoryIds[0])
            : null;

        return view('home', compact(
            'products', 'featuredProducts', 'likedIds', 'banners', 'activeCategory',
            'categories', 'sort', 'categoryId', 'sizes', 'colors', 'priceRange',
            'categoryIds', 'availableSizes', 'availableColors', 'productCounts'
        ));
    }
}
