<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Models\Product;

class UserController extends Controller
{
    public function index()
    {
        $products = Product::with('category')->get();
        $featuredProducts = $products->take(3);
        $banners = Banner::active()->get();

        // Pass liked product IDs so the product card can highlight the heart
        $likedIds = auth()->check()
            ? auth()->user()->likedProductIds()
            : [];

        return view('home', compact('products', 'featuredProducts', 'likedIds', 'banners'));
    }
}
