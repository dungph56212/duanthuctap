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
use Illuminate\Support\Facades\Log;

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
    }    public function store(Request $request)
    {
        // Validate dữ liệu chung
        $rules = [
            'email' => 'required|email|max:255',
            'shipping_name' => 'required|string|max:255',
            'shipping_phone' => 'required|string|max:20',
            'shipping_address_line' => 'required|string|max:500',
            'payment_method' => 'required|in:cod,bank_transfer,momo',
            'notes' => 'nullable|string|max:500'
        ];

        // Validate theo chế độ nhập địa chỉ
        if ($request->input('shipping_input_mode') === 'manual') {
            $rules['shipping_province_manual'] = 'required|string|max:255';
            $rules['shipping_district_manual'] = 'required|string|max:255';
            $rules['shipping_ward_manual'] = 'required|string|max:255';
        } else {
            $rules['shipping_province'] = 'required|string|max:255';
            $rules['shipping_district'] = 'required|string|max:255';
            $rules['shipping_ward'] = 'required|string|max:255';
        }

        $request->validate($rules);

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

            // Xử lý địa chỉ theo chế độ nhập
            $addressInfo = $this->processAddress($request);

            $order = Order::create([
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
                'billing_address' => json_encode($addressInfo),
                'shipping_address' => json_encode($addressInfo),
                'notes' => $request->notes
            ]);

            // Lưu địa chỉ nếu user yêu cầu
            if (Auth::check() && $request->input('shipping_save_address')) {
                $this->saveUserAddress($request, $addressInfo);
            }

            // Tạo order items và cập nhật stock
            foreach ($cartItems as $item) {
                // Kiểm tra stock
                if ($item->product->stock < $item->quantity) {
                    throw new \Exception("Sản phẩm {$item->product->name} không đủ hàng!");
                }

                OrderItem::create([
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
    }

    private function processAddress(Request $request)
    {
        $addressInfo = [
            'name' => $request->input('shipping_name'),
            'email' => $request->input('email'),
            'phone' => $request->input('shipping_phone'),
            'address_line' => $request->input('shipping_address_line')
        ];

        if ($request->input('shipping_input_mode') === 'manual') {
            // Chế độ nhập tay
            $addressInfo['province'] = $request->input('shipping_province_manual');
            $addressInfo['district'] = $request->input('shipping_district_manual');
            $addressInfo['ward'] = $request->input('shipping_ward_manual');
            $addressInfo['city'] = $request->input('shipping_province_manual');
            
            // Tạo địa chỉ đầy đủ
            $fullAddress = $request->input('shipping_address_line');
            $fullAddress .= ', ' . $request->input('shipping_ward_manual');
            $fullAddress .= ', ' . $request->input('shipping_district_manual');
            $fullAddress .= ', ' . $request->input('shipping_province_manual');
            
            $addressInfo['address'] = $fullAddress;
        } else {
            // Chế độ dropdown (giữ nguyên logic cũ)
            $addressInfo['province'] = $request->input('shipping_province');
            $addressInfo['district'] = $request->input('shipping_district');
            $addressInfo['ward'] = $request->input('shipping_ward');
            $addressInfo['city'] = $request->input('shipping_province');
            
            // Tạo địa chỉ đầy đủ
            $fullAddress = $request->input('shipping_address_line');
            if ($request->filled('shipping_ward')) {
                $fullAddress .= ', ' . $request->input('shipping_ward');
            }
            if ($request->filled('shipping_district')) {
                $fullAddress .= ', ' . $request->input('shipping_district');
            }
            if ($request->filled('shipping_province')) {
                $fullAddress .= ', ' . $request->input('shipping_province');
            }
            
            $addressInfo['address'] = $fullAddress;
        }

        return $addressInfo;
    }

    private function saveUserAddress(Request $request, array $addressInfo)
    {
        // Lưu địa chỉ vào bảng addresses nếu user muốn
        try {
            Address::create([
                'user_id' => Auth::id(),
                'name' => $addressInfo['name'],
                'phone' => $addressInfo['phone'],
                'ten_tinh' => $addressInfo['province'],
                'ten_quan' => $addressInfo['district'],
                'ten_phuong' => $addressInfo['ward'],
                'address_line' => $addressInfo['address_line'],
                'type' => 'shipping',
                'is_default' => Address::where('user_id', Auth::id())->count() === 0 // Đặt làm mặc định nếu là địa chỉ đầu tiên
            ]);        } catch (\Exception $e) {
            // Không throw exception để không ảnh hưởng đến việc đặt hàng
            Log::error('Could not save address: ' . $e->getMessage());
        }
    }public function success(Order $order)
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
    }    public function cancel(Order $order)
    {
        // Debug log
        Log::info('Cancel order request', [
            'order_id' => $order->id,
            'user_id' => Auth::id(),
            'order_user_id' => $order->user_id,
            'order_status' => $order->status,
            'payment_status' => $order->payment_status
        ]);

        // Chỉ cho phép user hủy đơn hàng của mình
        if (!Auth::check() || $order->user_id !== Auth::id()) {
            Log::warning('Unauthorized cancel attempt', [
                'order_id' => $order->id,
                'user_id' => Auth::id(),
                'order_user_id' => $order->user_id
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Bạn không có quyền hủy đơn hàng này!'
            ], 403);
        }

        // Chỉ cho phép hủy đơn hàng ở trạng thái 'pending' hoặc 'confirmed' và chưa thanh toán
        if (!in_array($order->status, ['pending', 'confirmed']) || $order->payment_status === 'paid') {
            Log::warning('Invalid order status for cancellation', [
                'order_id' => $order->id,
                'status' => $order->status,
                'payment_status' => $order->payment_status
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Không thể hủy đơn hàng này. Đơn hàng đã được xử lý hoặc đã thanh toán!'
            ], 400);
        }

        try {
            DB::beginTransaction();

            // Cập nhật trạng thái đơn hàng
            $order->update([
                'status' => 'cancelled',
                'payment_status' => 'cancelled',
                'cancelled_at' => now(),
                'cancelled_reason' => 'Hủy bởi khách hàng'
            ]);

            Log::info('Order status updated', ['order_id' => $order->id]);            // Hoàn lại số lượng sản phẩm vào kho
            foreach ($order->orderItems as $item) {
                if ($item->product) {
                    $oldStock = $item->product->stock;
                    $item->product->increment('stock', $item->quantity);
                    Log::info('Stock restored', [
                        'product_id' => $item->product->id,
                        'old_stock' => $oldStock,
                        'added' => $item->quantity,
                        'new_stock' => $item->product->fresh()->stock
                    ]);
                }
            }

            // Hoàn lại lượt sử dụng coupon nếu có
            if ($order->coupon_id) {
                $coupon = Coupon::find($order->coupon_id);
                if ($coupon) {
                    $oldUsedCount = $coupon->used_count;
                    $coupon->decrement('used_count');
                    Log::info('Coupon usage restored', [
                        'coupon_id' => $coupon->id,
                        'old_used_count' => $oldUsedCount,
                        'new_used_count' => $coupon->fresh()->used_count
                    ]);
                }
            }

            DB::commit();
            
            Log::info('Order cancelled successfully', ['order_id' => $order->id]);

            return response()->json([
                'success' => true,
                'message' => 'Đơn hàng đã được hủy thành công!',
                'redirect' => route('client.orders.index')
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error cancelling order', [
                'order_id' => $order->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi hủy đơn hàng: ' . $e->getMessage()
            ], 500);
        }
    }
}
