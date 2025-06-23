<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Product;
use App\Models\Order;
use App\Models\User;
use App\Models\Coupon;

echo "=== DASHBOARD DEBUG ===\n\n";

// Products
echo "PRODUCTS:\n";
echo "- Total: " . Product::count() . "\n";
echo "- Active: " . Product::where('is_active', true)->count() . "\n";
echo "- Manage stock: " . Product::where('manage_stock', true)->count() . "\n";
echo "- Low stock: " . Product::where('manage_stock', true)->where('stock', '<=', 10)->count() . "\n";
echo "- Out of stock: " . Product::where('manage_stock', true)->where('stock', 0)->count() . "\n\n";

// Orders
echo "ORDERS:\n";
echo "- Total: " . Order::count() . "\n";
echo "- Pending: " . Order::where('status', 'pending')->count() . "\n";
echo "- Delivered: " . Order::where('status', 'delivered')->count() . "\n";
echo "- Total revenue: " . Order::where('status', 'delivered')->sum('total') . "\n\n";

// Users
echo "USERS:\n";
echo "- Total: " . User::count() . "\n";
echo "- Customers: " . User::where('is_admin', false)->count() . "\n";
echo "- Admins: " . User::where('is_admin', true)->count() . "\n";
echo "- This month: " . User::whereMonth('created_at', date('m'))->whereYear('created_at', date('Y'))->count() . "\n\n";

// Coupons (if table exists)
try {
    echo "COUPONS:\n";
    echo "- Total: " . Coupon::count() . "\n";
    echo "- Active: " . Coupon::where('is_active', true)->count() . "\n";
    echo "- Used: " . Coupon::where('used_count', '>', 0)->count() . "\n\n";
} catch (Exception $e) {
    echo "COUPONS: Table not found or error - " . $e->getMessage() . "\n\n";
}

echo "=== END DEBUG ===\n";
