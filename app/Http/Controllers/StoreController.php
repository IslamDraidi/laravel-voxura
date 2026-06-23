<?php

namespace App\Http\Controllers;

use App\Models\Store;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    public function index(Request $request)
    {
        $filter = $request->query('filter', 'all');

        $query = Store::where('status', 'approved')
            ->with(['products' => function ($q) {
                $q->where('status', 'approved')
                  ->with(['images' => fn ($i) => $i->orderBy('sort_order')])
                  ->limit(3);
            }])
            ->withCount(['products as products_count' => fn ($q) => $q->where('status', 'approved')]);

        switch ($filter) {
            case 'new':
                $query->orderByDesc('created_at');
                break;
            case 'popular':
                $query->orderByDesc('visit_count');
                break;
            case '3d':
                $query->where('has_3d_products', true);
                break;
            case 'women':
            case 'men':
            case 'casual':
            case 'formal':
            case 'shoes':
            case 'accessories':
                $query->whereJsonContains('category_tags', ucfirst($filter));
                break;
            default:
                $query->latest();
        }

        $stores = $query->get();

        $featured = Store::where('is_featured', true)->where('status', 'approved')
            ->with(['products' => function ($q) {
                $q->where('status', 'approved')
                  ->with(['images' => fn ($i) => $i->orderBy('sort_order')])
                  ->limit(4);
            }])
            ->first();

        return view('stores.index', compact('stores', 'featured', 'filter'));
    }

    public function show(Store $store, Request $request)
    {
        abort_unless($store->status === 'approved', 404);

        $sessionKey = 'visited_store_' . $store->id;
        if (!session()->has($sessionKey)) {
            $store->increment('visit_count');
            $store->update(['last_visited_at' => now()]);
            session()->put($sessionKey, true);
        }

        $query = $store->products()->where('status', 'approved')->with('images');

        $category = $request->get('category');
        if ($category && $category !== 'all') {
            if ($category === '3d') {
                $query->whereNotNull('model3d_path')->where('model3d_status', 'ready');
            } else {
                $query->where('category', $category);
            }
        }

        $sort = $request->get('sort', 'newest');
        match ($sort) {
            'price_asc'  => $query->orderBy('price', 'asc'),
            'price_desc' => $query->orderBy('price', 'desc'),
            default      => $query->latest(),
        };

        $products = $query->paginate(8)->withQueryString();

        $totalProducts   = $store->products()->count();
        $total3DProducts = $store->products()->where('model3d_status', 'ready')->count();

        $featuredProducts = $store->products()
            ->with('images')
            ->latest()
            ->take(5)
            ->get();

        $similarStores = Store::where('status', 'approved')
            ->where('id', '!=', $store->id)
            ->inRandomOrder()
            ->take(3)
            ->get();

        return view('stores.show', compact(
            'store', 'products', 'totalProducts', 'total3DProducts',
            'featuredProducts', 'similarStores',
        ));
    }

    public function apply()
    {
        return redirect()->route('partner.apply');
    }

    public function publish()
    {
        $store = auth()->user()->store;
        abort_if(!$store, 403);

        $errors = [];

        if (!$store->logo_path) {
            $errors[] = __('general.upload_logo');
        }

        if (!$store->banner_path) {
            $errors[] = __('general.upload_banner');
        }

        $productCount = $store->products()->where('status', 'approved')->count();

        if ($productCount < 5) {
            $errors[] = __('general.add_5_products') . " ({$productCount}/5)";
        }

        if (!empty($errors)) {
            return back()->withErrors($errors);
        }

        $store->update([
            'status'            => 'approved',
            'onboarding_status' => 'live',
            'published_at'      => now(),
        ]);

        return redirect()->route('stores.show', $store)
            ->with('success', '🎉 ' . __('general.store_now_live'));
    }
}
