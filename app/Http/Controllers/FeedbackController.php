<?php

namespace App\Http\Controllers;

use App\Models\Feedback;
use App\Models\Product;
use Illuminate\Http\Request;

class FeedbackController extends Controller
{
    public function store(Request $request, Product $product)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|max:1000',
        ]);

        Feedback::create([
            'user_id' => auth()->id(),
            'product_id' => $product->id,
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        return back()->with('success', 'Review submitted!');
    }

    public function markHelpful(Feedback $feedback)
    {
        $helpfulReviews = session('helpful_reviews', []);

        if (in_array($feedback->id, $helpfulReviews, true)) {
            return back()->with('success', 'You already marked this review as helpful.');
        }

        $feedback->increment('helpful_votes');
        $helpfulReviews[] = $feedback->id;
        session(['helpful_reviews' => $helpfulReviews]);

        return back()->with('success', 'Thanks for your feedback.');
    }

    public function destroy(Feedback $feedback)
    {
        abort_unless($feedback->user_id === auth()->id() || auth()->user()->isAdmin(), 403);
        $feedback->delete();

        return back()->with('success', 'Review deleted.');
    }
}
