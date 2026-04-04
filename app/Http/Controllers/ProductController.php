<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function show(Product $product)
    {
        $product->load(['category', 'images', 'variants', 'feedbacks.user']);

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
            'recentlyViewed',
            'relatedProducts',
            'reviews',
            'averageRating',
            'ratingsBreakdown',
            'verifiedBuyerIds',
            'likedIds'
        ));
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
