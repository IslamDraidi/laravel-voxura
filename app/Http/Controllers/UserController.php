<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $trendingProducts = Product::where('status', 'approved')
            ->whereHas('store', fn ($q) => $q->where('status', 'approved'))
            ->with(['images', 'store'])
            ->inRandomOrder()
            ->take(8)
            ->get();

        $newArrivals = Product::where('status', 'approved')
            ->whereHas('store', fn ($q) => $q->where('status', 'approved'))
            ->with(['images', 'store'])
            ->latest()
            ->take(8)
            ->get();

        $products3D = Product::where('status', 'approved')
            ->where('model3d_status', 'ready')
            ->whereNotNull('model3d_path')
            ->whereHas('store', fn ($q) => $q->where('status', 'approved')->where('has_3d_products', true))
            ->with(['images', 'store'])
            ->take(4)
            ->get();

        $categories = [
            ['name' => 'Dresses',     'icon' => 'ti-dress'],
            ['name' => 'Jackets',     'icon' => 'ti-jacket'],
            ['name' => 'Shirts',      'icon' => 'ti-shirt'],
            ['name' => 'Shoes',       'icon' => 'ti-shoe'],
            ['name' => 'Accessories', 'icon' => 'ti-sunglasses'],
            ['name' => 'Offers',      'icon' => 'ti-tag'],
        ];

        $featuredStores = Store::approved()
            ->where('is_featured', true)
            ->with(['products' => fn ($q) => $q->where('status', 'approved')->take(3)->with('images')])
            ->take(3)
            ->get();

        if ($featuredStores->count() < 3) {
            $existing = $featuredStores->pluck('id')->toArray();
            $fill = Store::approved()
                ->whereNotIn('id', $existing)
                ->orderByDesc('visit_count')
                ->take(3 - $featuredStores->count())
                ->get();
            $featuredStores = $featuredStores->merge($fill);
        }

        return view('home', compact(
            'trendingProducts',
            'newArrivals',
            'products3D',
            'categories',
            'featuredStores',
        ));
    }
}
