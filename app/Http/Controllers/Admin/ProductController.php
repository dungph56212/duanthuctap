<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('category')
            ->when(request('search'), function($query) {
                $query->where('name', 'like', '%' . request('search') . '%')
                      ->orWhere('sku', 'like', '%' . request('search') . '%');
            })
            ->when(request('category'), function($query) {
                $query->where('category_id', request('category'));
            })
            ->latest()
            ->paginate(20);

        $categories = Category::orderBy('name')->get();
        
        return view('admin.products.index', compact('products', 'categories'));
    }

    public function create()
    {
        $categories = Category::active()->orderBy('name')->get();
        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request)
    {        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'short_description' => 'nullable|string|max:500',
            'sku' => 'required|string|unique:products,sku',
            'price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0|lt:price',
            'stock' => 'nullable|integer|min:0',
            'category_id' => 'required|exists:categories,id',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'weight' => 'nullable|numeric|min:0',
        ]);

        $data = $request->all();
        $data['slug'] = Str::slug($request->name . '-' . rand(1000, 9999));

        // Handle images upload
        if ($request->hasFile('images')) {
            $images = [];
            foreach ($request->file('images') as $image) {
                $images[] = $image->store('products', 'public');
            }
            $data['images'] = $images;
        }

        // Handle dimensions
        if ($request->filled(['length', 'width', 'height'])) {
            $data['dimensions'] = [
                'length' => $request->length,
                'width' => $request->width,
                'height' => $request->height,
            ];
        }

        Product::create($data);

        return redirect()->route('admin.products.index')
            ->with('success', 'Sản phẩm đã được tạo thành công!');
    }

    public function show(Product $product)
    {
        $product->load('category', 'reviews.user', 'orderItems');
        return view('admin.products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        $categories = Category::active()->orderBy('name')->get();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'short_description' => 'nullable|string|max:500',
            'sku' => 'required|string|unique:products,sku,' . $product->id,
            'price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0|lt:price',
            'stock' => 'nullable|integer|min:0',
            'category_id' => 'required|exists:categories,id',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'weight' => 'nullable|numeric|min:0',
        ]);

        $data = $request->all();
        $data['slug'] = Str::slug($request->name . '-' . $product->id);

        // Handle new images upload
        if ($request->hasFile('images')) {
            // Delete old images
            if ($product->images) {
                foreach ($product->images as $oldImage) {
                    Storage::disk('public')->delete($oldImage);
                }
            }
            
            $images = [];
            foreach ($request->file('images') as $image) {
                $images[] = $image->store('products', 'public');
            }
            $data['images'] = $images;
        }

        // Handle dimensions
        if ($request->filled(['length', 'width', 'height'])) {
            $data['dimensions'] = [
                'length' => $request->length,
                'width' => $request->width,
                'height' => $request->height,
            ];
        }

        $product->update($data);

        return redirect()->route('admin.products.index')
            ->with('success', 'Sản phẩm đã được cập nhật thành công!');
    }

    public function destroy(Product $product)
    {
        // Check if product has orders
        if ($product->orderItems()->count() > 0) {
            return back()->with('error', 'Không thể xóa sản phẩm đã có đơn hàng!');
        }

        // Delete images
        if ($product->images) {
            foreach ($product->images as $image) {
                Storage::disk('public')->delete($image);
            }
        }

        $product->delete();

        return redirect()->route('admin.products.index')
            ->with('success', 'Sản phẩm đã được xóa thành công!');
    }    public function toggleStatus(Product $product)
    {
        $product->update(['is_active' => !$product->is_active]);
        
        $status = $product->is_active ? 'kích hoạt' : 'tắt';
        return back()->with('success', "Đã {$status} sản phẩm thành công!");
    }

    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:delete,activate,deactivate',
            'product_ids' => 'required|array',
            'product_ids.*' => 'exists:products,id'
        ]);

        $action = $request->action;
        $productIds = $request->product_ids;
        $count = count($productIds);

        switch ($action) {
            case 'delete':
                // Xóa ảnh trước khi xóa sản phẩm
                $products = Product::whereIn('id', $productIds)->get();
                foreach ($products as $product) {
                    if ($product->images) {
                        foreach ($product->images as $image) {
                            Storage::disk('public')->delete($image);
                        }
                    }
                }
                Product::whereIn('id', $productIds)->delete();
                $message = "Đã xóa {$count} sản phẩm thành công!";
                break;

            case 'activate':
                Product::whereIn('id', $productIds)->update(['is_active' => true]);
                $message = "Đã kích hoạt {$count} sản phẩm thành công!";
                break;

            case 'deactivate':
                Product::whereIn('id', $productIds)->update(['is_active' => false]);
                $message = "Đã tắt {$count} sản phẩm thành công!";
                break;
        }        return redirect()->route('admin.products.index')->with('success', $message);
    }

    public function export()
    {
        $products = Product::with('category')->get();
        
        $csvData = [];
        $csvData[] = ['ID', 'Tên sản phẩm', 'Slug', 'Mô tả ngắn', 'Mô tả', 'Giá', 'Giá khuyến mãi', 'SKU', 'Số lượng', 'Danh mục', 'Trạng thái', 'Ngày tạo'];
        
        foreach ($products as $product) {
            $csvData[] = [
                $product->id,
                $product->name,
                $product->slug,
                $product->short_description,
                strip_tags($product->description),
                $product->price,
                $product->sale_price,                $product->sku,
                $product->stock,
                $product->category->name ?? '',
                $product->is_active ? 'Hoạt động' : 'Không hoạt động',
                $product->created_at->format('d/m/Y H:i:s')
            ];
        }
        
        $filename = 'products_export_' . date('Y_m_d_H_i_s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];
        
        $callback = function() use ($csvData) {
            $file = fopen('php://output', 'w');
            
            // Add BOM for UTF-8
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            foreach ($csvData as $row) {
                fputcsv($file, $row);
            }
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }
}
