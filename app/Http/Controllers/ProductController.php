<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Feedback;

class ProductController extends Controller
{
    public function show(Product $product)
    {
        $product->load('category');

        $reviews = Feedback::where('product_id', $product->id)
                           ->with('user')
                           ->latest()
                           ->get();

        $avgRating   = $reviews->avg('rating');
        $userReview  = auth()->check()
            ? $reviews->firstWhere('user_id', auth()->id())
            : null;

        return view('products.show', compact('product', 'reviews', 'avgRating', 'userReview'));
    }
}