<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::where('is_active', true)->with('category');

        // Tìm kiếm theo từ khóa
        if ($request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('author', 'like', "%{$search}%");
            });
        }

        // Lọc theo danh mục
        if ($request->category) {
            $query->where('category_id', $request->category);
        }

        // Lọc theo giá
        if ($request->min_price) {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->max_price) {
            $query->where('price', '<=', $request->max_price);
        }

        // Sắp xếp
        switch ($request->sort) {
            case 'price_low':
                $query->orderBy('price', 'asc');
                break;
            case 'price_high':
                $query->orderBy('price', 'desc');
                break;
            case 'name':
                $query->orderBy('name', 'asc');
                break;
            case 'popular':
                $query->orderBy('sold_count', 'desc');
                break;
            default:
                $query->latest();
                break;
        }

        $products = $query->paginate(16);
        
        // Lấy danh mục để hiển thị filter
        $categories = Category::where('is_active', true)
            ->withCount(['products' => function($query) {
                $query->where('is_active', true);
            }])
            ->orderBy('name')
            ->get();

        return view('client.products.index', compact('products', 'categories'));
    }

    public function show(Product $product)
    {
        if (!$product->is_active) {
            abort(404);
        }

        // Sản phẩm liên quan (cùng danh mục)
        $relatedProducts = Product::where('is_active', true)
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->take(4)
            ->get();

        // Tăng view count
        $product->increment('view_count');

        return view('client.products.show', compact('product', 'relatedProducts'));
    }    public function category(Category $category, Request $request)
    {
        if (!$category->is_active) {
            abort(404);
        }

        $query = Product::where('is_active', true)
            ->where('category_id', $category->id)
            ->with(['category', 'reviews']);

        // Lọc theo giá
        if ($request->price) {
            $priceRange = explode('-', $request->price);
            if (count($priceRange) == 2) {
                $minPrice = (int)$priceRange[0];
                $maxPrice = $priceRange[1] ? (int)$priceRange[1] : null;
                
                $query->where('price', '>=', $minPrice);
                if ($maxPrice) {
                    $query->where('price', '<=', $maxPrice);
                }
            }
        }

        // Sắp xếp
        switch ($request->sort) {
            case 'oldest':
                $query->oldest();
                break;
            case 'price_low':
                $query->orderBy('price', 'asc');
                break;
            case 'price_high':
                $query->orderBy('price', 'desc');
                break;
            case 'name_asc':
                $query->orderBy('name', 'asc');
                break;
            case 'name_desc':
                $query->orderBy('name', 'desc');
                break;
            case 'popular':
                $query->orderBy('sold_count', 'desc');
                break;
            default: // newest
                $query->latest();
                break;
        }

        $products = $query->paginate(16)->appends($request->all());

        return view('client.products.category', compact('category', 'products'));
    }

    public function search(Request $request)
    {
        $search = $request->get('q');
        
        $products = Product::where('is_active', true)
            ->where(function($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                      ->orWhere('description', 'like', "%{$search}%")
                      ->orWhere('author', 'like', "%{$search}%");
            })
            ->with('category')
            ->paginate(16);

        return view('client.products.search', compact('products', 'search'));
    }
}
