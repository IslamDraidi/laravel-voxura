<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class UserController extends Controller
{
    public function index()
    {
        $products         = Product::with('category')->get();
        $featuredProducts = $products->take(3);

        // Pass liked product IDs so the product card can highlight the heart
        $likedIds = auth()->check()
            ? auth()->user()->likedProductIds()
            : [];

        return view('home', compact('products', 'featuredProducts', 'likedIds'));
    }
}