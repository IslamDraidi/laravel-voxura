<?php

namespace App\Http\Controllers;

use App\Models\Feedback;
use App\Models\Product;
use Illuminate\Http\Request;

class FeedbackController extends Controller
{
    /** POST /products/{product}/reviews */
    public function store(Request $request, Product $product)
    {
        $request->validate([
            'rating'  => 'required|integer|min:1|max:5',
            'comment' => 'required|string|min:5|max:1000',
        ]);

        // One review per user per product
        $existing = Feedback::where('user_id', auth()->id())
                            ->where('product_id', $product->id)
                            ->first();

        if ($existing) {
            return back()->with('error', 'You have already reviewed this product.');
        }

        Feedback::create([
            'user_id'    => auth()->id(),
            'product_id' => $product->id,
            'rating'     => $request->rating,
            'comment'    => $request->comment,
        ]);

        return back()->with('success', 'Your review has been posted!');
    }

    /** DELETE /reviews/{feedback} */
    public function destroy(Feedback $feedback)
    {
        abort_unless(
            $feedback->user_id === auth()->id() || auth()->user()->isAdmin(),
            403
        );

        $feedback->delete();
        return back()->with('success', 'Review deleted.');
    }
}