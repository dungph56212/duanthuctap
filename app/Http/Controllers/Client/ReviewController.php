<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $reviews = Auth::user()->reviews()->with('product')->orderBy('created_at', 'desc')->get();
        return view('client.reviews.index', compact('reviews'));
    }

    /**
     * Store a newly created review
     */
    public function store(Request $request, Product $product)
    {
        // Check if user has already reviewed this product
        $existingReview = Auth::user()->reviews()->where('product_id', $product->id)->first();
        
        if ($existingReview) {
            return back()->with('error', 'Bạn đã đánh giá sản phẩm này rồi!');
        }

        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000'
        ]);

        Auth::user()->reviews()->create([
            'product_id' => $product->id,
            'rating' => $request->rating,
            'comment' => $request->comment
        ]);

        return back()->with('success', 'Cảm ơn bạn đã đánh giá sản phẩm!');
    }

    /**
     * Update the specified review
     */
    public function update(Request $request, Review $review)
    {
        // Ensure user can only update their own reviews
        if ($review->user_id !== Auth::id()) {
            abort(404);
        }

        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000'
        ]);

        $review->update([
            'rating' => $request->rating,
            'comment' => $request->comment
        ]);

        return back()->with('success', 'Đánh giá đã được cập nhật!');
    }

    /**
     * Remove the specified review
     */
    public function destroy(Review $review)
    {
        // Ensure user can only delete their own reviews
        if ($review->user_id !== Auth::id()) {
            abort(404);
        }

        $review->delete();

        return redirect()->route('client.reviews.index')
            ->with('success', 'Đánh giá đã được xóa!');
    }
}
