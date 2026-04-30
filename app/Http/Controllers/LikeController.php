<?php

namespace App\Http\Controllers;

use App\Models\Like;
use App\Models\Product;

class LikeController extends Controller
{
    /** GET /wishlist — show liked products */
    public function index()
    {
        $likedProducts = auth()->user()
            ->likedProducts()
            ->with('category')
            ->get();

        $likedIds = $likedProducts->pluck('id')->toArray();

        return view('wishlist.index', compact('likedProducts', 'likedIds'));
    }

    /** POST /likes/{product}/toggle — add or remove a like */
    public function toggle(Product $product)
    {
        $user     = auth()->user();
        $existing = Like::where('user_id', $user->id)
                        ->where('product_id', $product->id)
                        ->first();

        if ($existing) {
            $existing->delete();
            $liked   = false;
            $message = "\"{$product->name}\" removed from your wishlist.";
        } else {
            Like::create([
                'user_id'    => $user->id,
                'product_id' => $product->id,
            ]);
            $liked   = true;
            $message = "\"{$product->name}\" added to your wishlist!";
        }

        if (request()->ajax()) {
            return response()->json(['success' => true, 'liked' => $liked, 'message' => $message]);
        }

        return back()->with('success', $message);
    }
}
