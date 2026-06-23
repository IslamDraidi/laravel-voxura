<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $sort       = $request->get('sort', 'newest');
        $sizes      = array_values(array_filter((array) $request->get('size', [])));
        $colors     = array_values(array_filter((array) $request->get('color', [])));
        $priceRange = $request->get('price_range', '');

        $rawCategory = $request->get('category');
        if (is_array($rawCategory)) {
            $categoryIds = array_values(array_filter($rawCategory));
            $categoryId  = count($categoryIds) === 1 ? $categoryIds[0] : null;
        } else {
            $categoryId  = $rawCategory ?: null;
            $categoryIds = $categoryId ? [$categoryId] : [];
        }

        $storeSlug = $request->get('store', '');

        $query = Product::with(['category', 'variants', 'images', 'store'])
            ->whereNull('deleted_at')
            ->where('status', 'approved')
            ->where(function ($q) {
                $q->whereNull('store_id')
                  ->orWhereHas('store', fn ($sq) => $sq->where('status', 'approved'));
            });

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

        if ($priceMin !== null) {
            $query->where('price', '>=', $priceMin);
        }
        if ($priceMax !== null) {
            $query->where('price', '<=', $priceMax);
        }

        if (!empty($categoryIds)) {
            $query->whereIn('category_id', $categoryIds);
        }

        if ($storeSlug !== '') {
            $query->whereHas('store', fn ($q) => $q->where('slug', $storeSlug));
        }

        $query = match ($sort) {
            'price_asc'  => $query->orderBy('price'),
            'price_desc' => $query->orderByDesc('price'),
            'popular'    => $query->orderByRaw('(SELECT COUNT(*) FROM order_items WHERE order_items.product_id = products.id) DESC'),
            default      => $query->latest(),
        };

        $stores   = Store::approved()->orderBy('name')->get(['id', 'name', 'slug']);
        $products = $query->get();

        $categories = Category::all();

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

        $q          = '';
        $isShopPage = true;

        return view('search.index', compact(
            'products', 'categories', 'q', 'sort', 'categoryId',
            'sizes', 'colors', 'priceRange', 'categoryIds',
            'availableSizes', 'availableColors', 'productCounts',
            'isShopPage', 'stores', 'storeSlug'
        ));
    }

    public function show(Product $product)
    {
        $product->load(['category', 'images', 'variants', 'feedbacks.user', 'store']);

        $moreFromStore = collect();
        if ($product->store) {
            $moreFromStore = Product::where('store_id', $product->store_id)
                ->where('id', '!=', $product->id)
                ->where('status', 'approved')
                ->with('images')
                ->latest()
                ->take(4)
                ->get();
        }

        $viewed = session('recently_viewed', []);
        $viewed = array_filter($viewed, fn ($id) => $id !== $product->id);
        array_unshift($viewed, $product->id);
        $viewed = array_slice(array_values($viewed), 0, 8);
        session(['recently_viewed' => $viewed]);

        $recentlyViewed = count($viewed) > 1
            ? Product::with('category')
                ->whereIn('id', array_slice($viewed, 1, 6))
                ->whereNull('deleted_at')
                ->get()
                ->sortBy(fn ($p) => array_search($p->id, $viewed))
                ->values()
            : collect();

        $relatedProducts = Product::with('category')
            ->where('category_id', $product->category_id)
            ->whereKeyNot($product->id)
            ->whereNull('deleted_at')
            ->limit(4)
            ->get();

        $reviews = $product->feedbacks;
        $averageRating = round((float) ($reviews->avg('rating') ?? 0), 1);
        $ratingsBreakdown = collect(range(5, 1))
            ->mapWithKeys(fn (int $rating) => [$rating => $reviews->where('rating', $rating)->count()]);
        $verifiedBuyerIds = OrderItem::query()
            ->join('orders', 'orders.id', '=', 'order_items.order_id')
            ->where('order_items.product_id', $product->id)
            ->whereIn('orders.status', ['processing', 'shipped', 'delivered'])
            ->distinct()
            ->pluck('orders.user_id')
            ->all();
        $likedIds = auth()->check() ? auth()->user()->likedProductIds() : [];

        return view('products.show', compact(
            'product',
            'moreFromStore',
            'recentlyViewed',
            'relatedProducts',
            'reviews',
            'averageRating',
            'ratingsBreakdown',
            'verifiedBuyerIds',
            'likedIds'
        ));
    }

    public function liveSearch(Request $request): \Illuminate\Http\JsonResponse
    {
        $q = trim($request->get('q', ''));

        if (mb_strlen($q) < 2) {
            return response()->json(['results' => []]);
        }

        $results = Product::with('category')
            ->whereNull('deleted_at')
            ->where('stock', '>', 0)
            ->where(function ($sub) use ($q) {
                $sub->where('name', 'like', "%{$q}%")
                    ->orWhere('description', 'like', "%{$q}%");
            })
            ->orderByRaw('CASE WHEN name LIKE ? THEN 0 ELSE 1 END, name', ["{$q}%"])
            ->limit(6)
            ->get()
            ->map(fn ($p) => [
                'id'       => $p->id,
                'name'     => $p->name,
                'slug'     => $p->slug,
                'price'    => number_format((float) $p->price, 2),
                'image'    => $p->image ? asset('images/' . $p->image) : null,
                'category' => $p->category?->name,
                'url'      => route('products.show', $p->slug),
            ]);

        return response()->json(['results' => $results]);
    }

    public function search(Request $request)
    {
        $q = trim($request->get('q', ''));
        $categoryId = $request->get('category');
        $sort = $request->get('sort', 'relevance');

        $query = Product::with('category')->whereNull('deleted_at');

        if ($q !== '') {
            $query->where(function ($sub) use ($q) {
                $sub->where('name', 'like', "%{$q}%")
                    ->orWhere('description', 'like', "%{$q}%");
            });
        }

        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }

        $query = match ($sort) {
            'price_asc' => $query->orderBy('price'),
            'price_desc' => $query->orderByDesc('price'),
            'newest' => $query->latest(),
            default => $query->orderByRaw('CASE WHEN name LIKE ? THEN 0 ELSE 1 END, name', ["%{$q}%"]),
        };

        $products = $query->get();
        $categories = Category::all();

        return view('search.index', compact('products', 'categories', 'q', 'sort', 'categoryId'));
    }
}
