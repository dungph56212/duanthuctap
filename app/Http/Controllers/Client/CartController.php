<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
    {
        $cartItems = collect();
        $total = 0;

        if (Auth::check()) {
            // User đã đăng nhập - lấy từ database
            $cartItems = Cart::where('user_id', Auth::id())
                ->with('product')
                ->get();            $total = $cartItems->sum(function($item) {
                return $item->quantity * $item->price;
            });
        } else {
            // Guest - lấy từ session
            $cart = session()->get('cart', []);
            foreach ($cart as $id => $details) {
                $product = Product::find($id);
                if ($product) {
                    $cartItems->push((object)[
                        'id' => $id,
                        'product' => $product,
                        'quantity' => $details['quantity']
                    ]);
                    $total += $details['quantity'] * $product->price;
                }
            }
        }

        return view('client.cart.index', compact('cartItems', 'total'));
    }

    public function add(Request $request, Product $product)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        $quantity = $request->quantity;

        // Kiểm tra stock
        if ($product->stock < $quantity) {
            return response()->json([
                'success' => false,
                'message' => 'Không đủ hàng trong kho!'
            ]);
        }

        if (Auth::check()) {
            // User đã đăng nhập
            $cartItem = Cart::where('user_id', Auth::id())
                ->where('product_id', $product->id)
                ->first();

            if ($cartItem) {
                $newQuantity = $cartItem->quantity + $quantity;
                if ($product->stock < $newQuantity) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Không đủ hàng trong kho!'
                    ]);
                }
                $cartItem->update(['quantity' => $newQuantity]);            } else {
                Cart::create([
                    'user_id' => Auth::id(),
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                    'price' => $product->sale_price ?: $product->price
                ]);
            }
        } else {
            // Guest user - lưu vào session
            $cart = session()->get('cart', []);
            
            if (isset($cart[$product->id])) {
                $newQuantity = $cart[$product->id]['quantity'] + $quantity;
                if ($product->stock < $newQuantity) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Không đủ hàng trong kho!'
                    ]);
                }
                $cart[$product->id]['quantity'] = $newQuantity;
            } else {
                $cart[$product->id] = [
                    'name' => $product->name,
                    'quantity' => $quantity,
                    'price' => $product->price,
                    'image' => $product->images[0] ?? null
                ];
            }
            
            session()->put('cart', $cart);
        }

        return response()->json([
            'success' => true,
            'message' => 'Đã thêm sản phẩm vào giỏ hàng!'
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        $quantity = $request->quantity;
        $product = Product::findOrFail($id);

        if ($product->stock < $quantity) {
            return response()->json([
                'success' => false,
                'message' => 'Không đủ hàng trong kho!'
            ]);
        }

        if (Auth::check()) {
            $cartItem = Cart::where('user_id', Auth::id())
                ->where('product_id', $id)
                ->firstOrFail();
            $cartItem->update(['quantity' => $quantity]);
        } else {
            $cart = session()->get('cart', []);
            if (isset($cart[$id])) {
                $cart[$id]['quantity'] = $quantity;
                session()->put('cart', $cart);
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Đã cập nhật giỏ hàng!'
        ]);
    }

    public function remove($id)
    {
        if (Auth::check()) {
            Cart::where('user_id', Auth::id())
                ->where('product_id', $id)
                ->delete();
        } else {
            $cart = session()->get('cart', []);
            unset($cart[$id]);
            session()->put('cart', $cart);
        }

        return response()->json([
            'success' => true,
            'message' => 'Đã xóa sản phẩm khỏi giỏ hàng!'
        ]);
    }    public function clear()
    {
        if (Auth::check()) {
            Cart::where('user_id', Auth::id())->delete();
        } else {
            session()->forget('cart');
        }

        // Kiểm tra nếu là AJAX request
        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Đã xóa tất cả sản phẩm khỏi giỏ hàng!'
            ]);
        }

        return redirect()->route('client.cart.index')
            ->with('success', 'Đã xóa tất cả sản phẩm khỏi giỏ hàng!');
    }

    public function count()
    {
        $count = 0;

        if (Auth::check()) {
            $count = Cart::where('user_id', Auth::id())->sum('quantity');
        } else {
            $cart = session()->get('cart', []);
            foreach ($cart as $item) {
                $count += $item['quantity'];
            }
        }

        return response()->json(['count' => $count]);
    }
}
