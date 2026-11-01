<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function store(Request $request, Product $product)
    {
        $data = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'title'  => 'nullable|string|max:255',
            'body'   => 'nullable|string|max:2000',
        ]);

        // Check if already reviewed
        $existing = Review::where('product_id', $product->id)->where('user_id', auth()->id())->first();
        if ($existing) {
            return back()->with('error', 'You have already reviewed this product.');
        }

        // Check if purchased and delivered
        $hasOrdered = \App\Models\Order::where('user_id', auth()->id())
            ->where('status', 'delivered')
            ->whereHas('items', function($q) use ($product) {
                $q->where('product_id', $product->id);
            })->exists();

        if (!$hasOrdered) {
            return back()->with('error', 'You can only review products you have purchased and received.');
        }

        Review::create([
            'product_id' => $product->id,
            'user_id'    => auth()->id(),
            'rating'     => $data['rating'],
            'title'      => $data['title'] ?? null,
            'body'       => $data['body'] ?? null,
        ]);

        // Update product rating
        $avgRating = Review::where('product_id', $product->id)->approved()->avg('rating');
        $count     = Review::where('product_id', $product->id)->approved()->count();
        $product->update(['rating' => round($avgRating, 2), 'reviews_count' => $count]);

        return back()->with('success', 'Thank you for your review!');
    }

    public function destroy(Review $review)
    {
        if ($review->user_id !== auth()->id() && !auth()->user()->isAdmin()) {
            abort(403);
        }
        $product = $review->product;
        $review->delete();

        // Recalculate rating
        $avgRating = Review::where('product_id', $product->id)->approved()->avg('rating') ?? 0;
        $count     = Review::where('product_id', $product->id)->approved()->count();
        $product->update(['rating' => round($avgRating, 2), 'reviews_count' => $count]);

        return back()->with('success', 'Review deleted.');
    }
}
