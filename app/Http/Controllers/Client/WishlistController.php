<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Wishlist;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        $wishlists = $user->wishlists()->with(['product.category', 'product.reviews'])->orderBy('created_at', 'desc')->get();
        return view('client.wishlist.index', compact('wishlists', 'user'));
    }    /**
     * Add product to wishlist
     */
    public function add(Product $product)
    {
        // Check if product already in wishlist
        $exists = Auth::user()->wishlists()->where('product_id', $product->id)->exists();
        
        if ($exists) {
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Sản phẩm đã có trong danh sách yêu thích!'
                ]);
            }
            return back()->with('info', 'Sản phẩm đã có trong danh sách yêu thích!');
        }

        Auth::user()->wishlists()->create([
            'product_id' => $product->id
        ]);

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Đã thêm sản phẩm vào danh sách yêu thích!'
            ]);
        }
        
        return back()->with('success', 'Đã thêm sản phẩm vào danh sách yêu thích!');
    }

    /**
     * Remove product from wishlist
     */
    public function remove(Product $product)
    {
        $deleted = Auth::user()->wishlists()->where('product_id', $product->id)->delete();

        if (request()->ajax()) {
            return response()->json([
                'success' => $deleted > 0,
                'message' => $deleted > 0 ? 'Đã xóa sản phẩm khỏi danh sách yêu thích!' : 'Sản phẩm không có trong danh sách yêu thích!'
            ]);
        }

        return back()->with('success', 'Đã xóa sản phẩm khỏi danh sách yêu thích!');
    }

    /**
     * Remove wishlist item by ID
     */
    public function destroy(Wishlist $wishlist)
    {
        // Ensure user can only delete their own wishlist items
        if ($wishlist->user_id !== Auth::id()) {
            abort(404);
        }

        $wishlist->delete();

        return redirect()->route('client.wishlist.index')
            ->with('success', 'Đã xóa sản phẩm khỏi danh sách yêu thích!');
    }

    /**
     * Check if product is in wishlist (AJAX)
     */
    public function check(Product $product)
    {
        $inWishlist = Auth::user()->wishlists()->where('product_id', $product->id)->exists();
        
        return response()->json(['in_wishlist' => $inWishlist]);
    }

    /**
     * Toggle wishlist status (AJAX)
     */
    public function toggle(Product $product)
    {
        $wishlist = Auth::user()->wishlists()->where('product_id', $product->id)->first();
        
        if ($wishlist) {
            $wishlist->delete();
            $message = 'Đã xóa khỏi danh sách yêu thích!';
            $in_wishlist = false;
        } else {
            Auth::user()->wishlists()->create([
                'product_id' => $product->id
            ]);
            $message = 'Đã thêm vào danh sách yêu thích!';
            $in_wishlist = true;
        }

        return response()->json([
            'success' => true,
            'message' => $message,
            'in_wishlist' => $in_wishlist
        ]);
    }

    /**
     * Get wishlist count (AJAX)
     */
    public function count()
    {
        $count = Auth::user()->wishlists()->count();
        
        return response()->json(['count' => $count]);
    }
}
