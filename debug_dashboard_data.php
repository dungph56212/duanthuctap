<?php

require_once 'vendor/autoload.php';

// Load Laravel application
$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Import models
use App\Models\User;
use App\Models\Product;
use App\Models\Order;
use App\Models\Category;

echo "=== KIỂM TRA DỮ LIỆU DASHBOARD ADMIN ===\n\n";

try {
    // Check Users
    $totalUsers = User::count();
    echo "Tổng số Users: $totalUsers\n";
    
    if ($totalUsers > 0) {
        $sampleUsers = User::take(3)->get(['id', 'name', 'email', 'is_admin']);
        echo "Sample users:\n";
        foreach ($sampleUsers as $user) {
            echo "- ID: {$user->id}, Name: {$user->name}, Email: {$user->email}, Is Admin: " . ($user->is_admin ? 'Yes' : 'No') . "\n";
        }
    }
    echo "\n";

    // Check Products
    $totalProducts = Product::count();
    echo "Tổng số Products: $totalProducts\n";
    
    if ($totalProducts > 0) {
        $sampleProducts = Product::take(3)->get(['id', 'name', 'price', 'is_active']);
        echo "Sample products:\n";
        foreach ($sampleProducts as $product) {
            echo "- ID: {$product->id}, Name: {$product->name}, Price: {$product->price}, Active: " . ($product->is_active ? 'Yes' : 'No') . "\n";
        }
    }
    echo "\n";

    // Check Orders
    $totalOrders = Order::count();
    echo "Tổng số Orders: $totalOrders\n";
    
    if ($totalOrders > 0) {
        $sampleOrders = Order::take(3)->get(['id', 'user_id', 'total_amount', 'status', 'payment_status']);
        echo "Sample orders:\n";
        foreach ($sampleOrders as $order) {
            echo "- ID: {$order->id}, User ID: {$order->user_id}, Total: {$order->total_amount}, Status: {$order->status}, Payment: {$order->payment_status}\n";
        }
    }
    echo "\n";

    // Check Categories
    $totalCategories = Category::count();
    echo "Tổng số Categories: $totalCategories\n";
    
    if ($totalCategories > 0) {
        $sampleCategories = Category::take(3)->get(['id', 'name', 'is_active']);
        echo "Sample categories:\n";
        foreach ($sampleCategories as $category) {
            echo "- ID: {$category->id}, Name: {$category->name}, Active: " . ($category->is_active ? 'Yes' : 'No') . "\n";
        }
    }
    echo "\n";

    // Check Revenue
    $paidOrders = Order::where('payment_status', 'paid')->count();
    echo "Số Orders đã thanh toán: $paidOrders\n";
    
    if ($paidOrders > 0) {
        $totalRevenue = Order::where('payment_status', 'paid')->sum('total_amount');
        echo "Tổng doanh thu (orders đã paid): " . number_format($totalRevenue) . " VNĐ\n";
        
        $todayRevenue = Order::where('payment_status', 'paid')
            ->whereDate('created_at', today())
            ->sum('total_amount');
        echo "Doanh thu hôm nay: " . number_format($todayRevenue) . " VNĐ\n";
    }

    echo "\n=== KIỂM TRA CẤU TRÚC BẢNG ===\n";
    
    // Check table structures
    $tables = ['users', 'products', 'orders', 'categories'];
    foreach ($tables as $table) {
        try {
            $columns = \Illuminate\Support\Facades\DB::select("DESCRIBE $table");
            echo "\nBảng $table có các cột:\n";
            foreach ($columns as $column) {
                echo "- {$column->Field} ({$column->Type})\n";
            }
        } catch (Exception $e) {
            echo "Lỗi khi kiểm tra bảng $table: " . $e->getMessage() . "\n";
        }
    }

} catch (Exception $e) {
    echo "Lỗi: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}

echo "\n=== KẾT THÚC KIỂM TRA ===\n";
