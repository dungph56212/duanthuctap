<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{    public function index()
    {
        // Sản phẩm nổi bật (featured)
        $featuredProducts = Product::where('is_active', true)
            ->where('is_featured', true)
            ->with('category')
            ->latest()
            ->take(8)
            ->get();

        // Sản phẩm mới nhất
        $latestProducts = Product::where('is_active', true)
            ->with('category')
            ->latest()
            ->take(12)
            ->get();

        // Sản phẩm bán chạy (dựa trên số lượng đã bán)
        $bestSellingProducts = Product::where('is_active', true)
            ->with('category')
            ->orderBy('sold_count', 'desc')
            ->take(8)
            ->get();

        // Danh mục sản phẩm
        $categories = Category::where('is_active', true)
            ->withCount(['products' => function($query) {
                $query->where('is_active', true);
            }])
            ->orderBy('name')
            ->get();        // Get user's wishlist product IDs if authenticated
        $userWishlistIds = [];
        if (Auth::check()) {
            $userWishlistIds = Auth::user()->wishlists()->pluck('product_id')->toArray();
        }

        return view('client.home', compact(
            'featuredProducts',
            'latestProducts', 
            'bestSellingProducts',
            'categories',
            'userWishlistIds'
        ));
    }

    public function about()
    {
        return view('client.about');
    }

    public function contact()
    {
        return view('client.contact');
    }

    public function contactStore(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:1000',
        ]);

        // Lưu tin nhắn liên hệ vào database hoặc gửi email
        // Tạm thời chỉ return success message
        
        return redirect()->route('client.contact')
            ->with('success', 'Cảm ơn bạn đã liên hệ! Chúng tôi sẽ phản hồi sớm nhất có thể.');
    }
}
