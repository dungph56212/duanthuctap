<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Cart;
use App\Models\Product;
use App\Models\Coupon;
use App\Models\Address;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function checkout()
    {
        $cartItems = collect();
        $subtotal = 0;

        if (Auth::check()) {
            $cartItems = Cart::where('user_id', Auth::id())
                ->with('product')
                ->get();
            $subtotal = $cartItems->sum(function($item) {
                return $item->quantity * $item->product->price;
            });
        } else {
            $cart = session()->get('cart', []);
            foreach ($cart as $id => $details) {
                $product = Product::find($id);
                if ($product) {
                    $cartItems->push((object)[
                        'id' => $id,
                        'product' => $product,
                        'quantity' => $details['quantity']
                    ]);
                    $subtotal += $details['quantity'] * $product->price;
                }
            }
        }

        if ($cartItems->isEmpty()) {
            return redirect()->route('client.cart.index')
                ->with('error', 'Giỏ hàng của bạn đang trống!');
        }

        // Lấy địa chỉ của user (nếu đã đăng nhập)
        $addresses = collect();
        if (Auth::check()) {
            $addresses = Address::where('user_id', Auth::id())->get();
        }

        // Tính phí vận chuyển (giả định)
        $shipping = 30000; // 30,000đ phí ship cố định
        $total = $subtotal + $shipping;

        return view('client.checkout', compact('cartItems', 'subtotal', 'shipping', 'total', 'addresses'));
    }

    public function applyCoupon(Request $request)
    {
        $request->validate([
            'coupon_code' => 'required|string'
        ]);

        $coupon = Coupon::where('code', $request->coupon_code)
            ->where('is_active', true)
            ->where(function($query) {
                $query->whereNull('start_date')
                      ->orWhere('start_date', '<=', now());
            })
            ->where(function($query) {
                $query->whereNull('end_date')
                      ->orWhere('end_date', '>=', now());
            })
            ->first();

        if (!$coupon) {
            return response()->json([
                'success' => false,
                'message' => 'Mã giảm giá không hợp lệ hoặc đã hết hạn!'
            ]);
        }

        // Kiểm tra số lượng đã sử dụng
        if ($coupon->quantity && $coupon->used_count >= $coupon->quantity) {
            return response()->json([
                'success' => false,
                'message' => 'Mã giảm giá đã hết lượt sử dụng!'
            ]);
        }

        // Tính subtotal
        $subtotal = 0;
        if (Auth::check()) {
            $cartItems = Cart::where('user_id', Auth::id())->with('product')->get();
            $subtotal = $cartItems->sum(function($item) {
                return $item->quantity * $item->product->price;
            });
        } else {
            $cart = session()->get('cart', []);
            foreach ($cart as $id => $details) {
                $product = Product::find($id);
                if ($product) {
                    $subtotal += $details['quantity'] * $product->price;
                }
            }
        }

        // Kiểm tra giá trị đơn hàng tối thiểu
        if ($coupon->min_amount && $subtotal < $coupon->min_amount) {
            return response()->json([
                'success' => false,
                'message' => "Đơn hàng tối thiểu " . number_format($coupon->min_amount) . "đ để sử dụng mã này!"
            ]);
        }

        // Tính discount
        $discount = 0;
        if ($coupon->type === 'percentage') {
            $discount = $subtotal * ($coupon->value / 100);
            if ($coupon->max_discount && $discount > $coupon->max_discount) {
                $discount = $coupon->max_discount;
            }
        } else {
            $discount = $coupon->value;
        }

        $total = $subtotal - $discount;

        // Lưu vào session
        session()->put('applied_coupon', [
            'id' => $coupon->id,
            'code' => $coupon->code,
            'discount' => $discount
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Áp dụng mã giảm giá thành công!',
            'discount' => $discount,
            'total' => $total
        ]);
    }

    public function store(Request $request)
    {        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'city' => 'required|string|max:255',
            'address' => 'required|string|max:500',
            'payment_method' => 'required|in:cod,bank_transfer,momo',
            'notes' => 'nullable|string|max:500'
        ]);

        DB::beginTransaction();
        try {
            // Lấy cart items
            $cartItems = collect();
            if (Auth::check()) {
                $cartItems = Cart::where('user_id', Auth::id())->with('product')->get();
            } else {
                $cart = session()->get('cart', []);
                foreach ($cart as $id => $details) {
                    $product = Product::find($id);
                    if ($product) {
                        $cartItems->push((object)[
                            'product' => $product,
                            'quantity' => $details['quantity']
                        ]);
                    }
                }
            }

            if ($cartItems->isEmpty()) {
                throw new \Exception('Giỏ hàng trống!');
            }

            // Tính tổng tiền
            $subtotal = $cartItems->sum(function($item) {
                return $item->quantity * $item->product->price;
            });

            $discount = 0;
            $couponId = null;
            $appliedCoupon = session()->get('applied_coupon');
            if ($appliedCoupon) {
                $discount = $appliedCoupon['discount'];
                $couponId = $appliedCoupon['id'];
            }

            $shipping = 30000; // Phí ship cố định 30k
            $total = $subtotal - $discount + $shipping;

            // Tạo đơn hàng            // Tạo địa chỉ đầy đủ
            $fullAddress = $request->address;
            if ($request->filled('ten_phuong')) {
                $fullAddress = $request->ten_phuong . ', ' . $fullAddress;
            }
            if ($request->filled('ten_quan')) {
                $fullAddress = $request->ten_quan . ', ' . $fullAddress;
            }
            if ($request->filled('ten_tinh')) {
                $fullAddress = $request->ten_tinh . ', ' . $fullAddress;
            }            $order = Order::create([
                'user_id' => Auth::id(),
                'order_number' => 'ORD' . date('YmdHis') . rand(100, 999),
                'status' => 'pending',
                'subtotal' => $subtotal,
                'tax_amount' => 0,
                'shipping_amount' => $shipping,
                'discount_amount' => $discount,
                'total_amount' => $total,
                'currency' => 'VND',
                'payment_status' => 'pending',
                'payment_method' => $request->payment_method,
                'billing_address' => json_encode([
                    'name' => $request->name,
                    'email' => $request->email,
                    'phone' => $request->phone,
                    'city' => $request->ten_tinh ?? $request->city,
                    'address' => $fullAddress
                ]),
                'shipping_address' => json_encode([
                    'name' => $request->name,
                    'email' => $request->email,
                    'phone' => $request->phone,
                    'city' => $request->ten_tinh ?? $request->city,
                    'address' => $fullAddress
                ]),
                'notes' => $request->notes
            ]);

            // Tạo order items và cập nhật stock
            foreach ($cartItems as $item) {
                // Kiểm tra stock
                if ($item->product->stock < $item->quantity) {
                    throw new \Exception("Sản phẩm {$item->product->name} không đủ hàng!");
                }                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product->id,
                    'product_name' => $item->product->name,
                    'product_sku' => $item->product->sku ?? 'N/A',
                    'product_price' => $item->product->price,
                    'quantity' => $item->quantity,
                    'total_price' => $item->product->price * $item->quantity,
                    'product_attributes' => json_encode([])
                ]);

                // Cập nhật stock và sold_count
                $item->product->decrement('stock', $item->quantity);
                $item->product->increment('sold_count', $item->quantity);
            }

            // Cập nhật coupon usage
            if ($couponId) {
                Coupon::find($couponId)->increment('used_count');
            }

            // Xóa cart
            if (Auth::check()) {
                Cart::where('user_id', Auth::id())->delete();
            } else {
                session()->forget('cart');
            }

            // Xóa applied coupon
            session()->forget('applied_coupon');

            DB::commit();

            return redirect()->route('client.order.success', $order)
                ->with('success', 'Đặt hàng thành công!');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->with('error', $e->getMessage())
                ->withInput();
        }
    }    public function success(Order $order)
    {
        // Chỉ cho phép user xem đơn hàng của mình hoặc guest với session
        if (Auth::check() && $order->user_id !== Auth::id()) {
            abort(403);
        }

        return view('client.order-success', compact('order'));
    }public function myOrders(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }        $query = Order::where('user_id', Auth::id())
            ->with('orderItems.product');

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by date range
        if ($request->filled('from_date')) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }

        $orders = $query->latest()->paginate(10);

        return view('client.orders.index', compact('orders'));
    }    public function show(Order $order)
    {
        // Chỉ cho phép user xem đơn hàng của mình
        if (Auth::check() && $order->user_id !== Auth::id()) {
            abort(403);
        }

        $order->load('orderItems.product');

        return view('client.orders.show', compact('order'));
    }
}
