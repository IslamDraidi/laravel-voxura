<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $allProducts = Product::with('category')->get();
        $featuredProducts = $allProducts->take(3);
        $banners = Banner::active()->get();

        $likedIds = auth()->check()
            ? auth()->user()->likedProductIds()
            : [];

        // Category filter via ?cat=
        $activeCategoryId = $request->query('cat');
        $activeCategory   = null;

        if ($activeCategoryId) {
            $activeCategory = Category::find($activeCategoryId);
        }

        if ($activeCategory) {
            // If it's a parent, include all children's products too
            $childIds = $activeCategory->children()->pluck('id')->toArray();
            if (count($childIds) > 0) {
                $products = $allProducts->whereIn('category_id', $childIds)->values();
            } else {
                $products = $allProducts->where('category_id', $activeCategory->id)->values();
            }
        } else {
            $products = $allProducts;
        }

        return view('home', compact('products', 'featuredProducts', 'likedIds', 'banners', 'activeCategory'));
    }
}
