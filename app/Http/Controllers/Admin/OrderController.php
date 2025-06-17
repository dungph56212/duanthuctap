<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use App\Models\Product;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with('user')
            ->when(request('status'), function($query) {
                $query->where('status', request('status'));
            })
            ->when(request('search'), function($query) {
                $search = request('search');
                $query->where('order_number', 'like', "%{$search}%")
                      ->orWhereHas('user', function($q) use ($search) {
                          $q->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                      });
            })
            ->latest()
            ->paginate(20);

        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $order->load('user', 'orderItems.product');
        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,shipped,delivered,cancelled',
        ]);

        $oldStatus = $order->status;
        $order->update(['status' => $request->status]);

        // Update timestamps based on status
        if ($request->status === 'shipped' && $oldStatus !== 'shipped') {
            $order->update(['shipped_at' => now()]);
        }

        if ($request->status === 'delivered' && $oldStatus !== 'delivered') {
            $order->update(['delivered_at' => now()]);
        }

        return back()->with('success', 'Trạng thái đơn hàng đã được cập nhật!');
    }

    public function updatePaymentStatus(Request $request, Order $order)
    {
        $request->validate([
            'payment_status' => 'required|in:pending,paid,failed,refunded',
        ]);

        $order->update(['payment_status' => $request->payment_status]);

        return back()->with('success', 'Trạng thái thanh toán đã được cập nhật!');
    }

    public function create()
    {
        $users = User::orderBy('name')->get();
        $products = Product::where('is_active', true)->orderBy('name')->get();
        
        return view('admin.orders.create', compact('users', 'products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'products' => 'required|array',
            'products.*.id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
            'shipping_address' => 'required|string',
            'notes' => 'nullable|string'
        ]);

        $user = User::findOrFail($request->user_id);
        $totalAmount = 0;

        // Calculate total amount
        foreach ($request->products as $productData) {
            $product = Product::findOrFail($productData['id']);
            $totalAmount += $product->price * $productData['quantity'];
        }

        // Create order
        $order = Order::create([
            'user_id' => $user->id,
            'order_number' => 'ORD-' . strtoupper(uniqid()),
            'total_amount' => $totalAmount,
            'status' => 'pending',
            'payment_status' => 'pending',
            'payment_method' => 'manual',
            'shipping_address' => $request->shipping_address,
            'notes' => $request->notes,
        ]);

        // Create order items
        foreach ($request->products as $productData) {
            $product = Product::findOrFail($productData['id']);
            
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $product->id,
                'quantity' => $productData['quantity'],
                'price' => $product->price,
            ]);
        }

        return redirect()->route('admin.orders.index')
            ->with('success', 'Đơn hàng đã được tạo thành công!');
    }

    public function edit(Order $order)
    {
        $order->load('user', 'orderItems.product');
        $users = User::orderBy('name')->get();
        $products = Product::where('is_active', true)->orderBy('name')->get();
        
        return view('admin.orders.edit', compact('order', 'users', 'products'));
    }

    public function update(Request $request, Order $order)
    {
        $request->validate([
            'shipping_address' => 'required|string',
            'notes' => 'nullable|string',
            'status' => 'required|in:pending,processing,shipped,delivered,cancelled',
            'payment_status' => 'required|in:pending,paid,failed,refunded'
        ]);

        $order->update([
            'shipping_address' => $request->shipping_address,
            'notes' => $request->notes,
            'status' => $request->status,
            'payment_status' => $request->payment_status,
        ]);

        // Update timestamps based on status
        if ($request->status === 'shipped' && $order->getOriginal('status') !== 'shipped') {
            $order->update(['shipped_at' => now()]);
        }

        if ($request->status === 'delivered' && $order->getOriginal('status') !== 'delivered') {
            $order->update(['delivered_at' => now()]);
        }

        return redirect()->route('admin.orders.show', $order)
            ->with('success', 'Đơn hàng đã được cập nhật thành công!');
    }    public function destroy(Order $order)
    {
        // Only allow deletion of pending or cancelled orders
        if (!in_array($order->status, ['pending', 'cancelled'])) {
            return back()->with('error', 'Chỉ có thể xóa đơn hàng ở trạng thái "Chờ xử lý" hoặc "Đã hủy"!');
        }

        $order->delete();

        return redirect()->route('admin.orders.index')
            ->with('success', 'Đơn hàng đã được xóa thành công!');
    }

    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:delete,confirm,ship,deliver,cancel',
            'order_ids' => 'required|array',
            'order_ids.*' => 'exists:orders,id'
        ]);

        $action = $request->action;
        $orderIds = $request->order_ids;
        $count = count($orderIds);

        switch ($action) {
            case 'delete':
                // Only delete pending or cancelled orders
                $deletableOrders = Order::whereIn('id', $orderIds)
                    ->whereIn('status', ['pending', 'cancelled'])
                    ->get();
                
                if ($deletableOrders->count() < $count) {
                    return back()->with('error', 'Chỉ có thể xóa đơn hàng ở trạng thái "Chờ xử lý" hoặc "Đã hủy"!');
                }
                
                Order::whereIn('id', $orderIds)->delete();
                $message = "Đã xóa {$count} đơn hàng thành công!";
                break;

            case 'confirm':
                Order::whereIn('id', $orderIds)->update(['status' => 'confirmed']);
                $message = "Đã xác nhận {$count} đơn hàng thành công!";
                break;

            case 'ship':
                Order::whereIn('id', $orderIds)->update(['status' => 'shipped']);
                $message = "Đã chuyển {$count} đơn hàng sang trạng thái vận chuyển!";
                break;

            case 'deliver':
                Order::whereIn('id', $orderIds)->update([
                    'status' => 'delivered',
                    'payment_status' => 'paid'
                ]);
                $message = "Đã giao {$count} đơn hàng thành công!";
                break;

            case 'cancel':
                Order::whereIn('id', $orderIds)->update(['status' => 'cancelled']);
                $message = "Đã hủy {$count} đơn hàng thành công!";
                break;
        }

        return redirect()->route('admin.orders.index')->with('success', $message);
    }    public function markPaid(Order $order)
    {
        $order->update([
            'payment_status' => 'paid'
        ]);

        return redirect()->back()->with('success', 'Đơn hàng đã được đánh dấu là đã thanh toán!');
    }

    public function cancel(Order $order)
    {
        // Chỉ cho phép hủy đơn hàng khi chưa shipped/delivered
        if (in_array($order->status, ['shipped', 'delivered'])) {
            return redirect()->back()->with('error', 'Không thể hủy đơn hàng đã được giao!');
        }

        // Khôi phục lại stock cho các sản phẩm
        foreach ($order->orderItems as $item) {
            $item->product->increment('stock', $item->quantity);
            $item->product->decrement('sold_count', $item->quantity);
        }

        $order->update([
            'status' => 'cancelled'
        ]);

        return redirect()->back()->with('success', 'Đơn hàng đã được hủy và stock đã được khôi phục!');
    }    public function print(Order $order)
    {
        $order->load('user', 'orderItems.product');
        return view('admin.orders.print', compact('order'));
    }

    public function checkNew()
    {
        $newOrdersCount = Order::where('status', 'pending')
            ->where('created_at', '>=', now()->subMinutes(5))
            ->count();
            
        return response()->json([
            'new_orders' => $newOrdersCount,
            'has_new_orders' => $newOrdersCount > 0
        ]);
    }
}
