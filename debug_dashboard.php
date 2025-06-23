<?php
// Debug dashboard data
require_once __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use App\Models\Product;
use App\Models\Order;
use App\Models\Category;
use Illuminate\Support\Facades\DB;

echo "=== DASHBOARD DEBUG ===\n";

// Check database connection
try {
    $totalUsers = User::count();
    echo "✓ Database connection OK\n";
} catch (Exception $e) {
    echo "✗ Database connection error: " . $e->getMessage() . "\n";
    exit(1);
}

// Count data
echo "\n--- BASIC COUNTS ---\n";
echo "Total Users: " . User::count() . "\n";
echo "Total Products: " . Product::count() . "\n";
echo "Total Orders: " . Order::count() . "\n";
echo "Total Categories: " . Category::count() . "\n";

// Check if tables exist and have data
echo "\n--- DETAILED CHECKS ---\n";

// Check users
$users = User::take(3)->get();
echo "Sample users: " . $users->count() . "\n";
foreach ($users as $user) {
    echo "  - {$user->name} (ID: {$user->id}, Email: {$user->email})\n";
}

// Check products
$products = Product::take(3)->get();
echo "\nSample products: " . $products->count() . "\n";
foreach ($products as $product) {
    echo "  - {$product->name} (ID: {$product->id}, Price: {$product->price})\n";
}

// Check categories
$categories = Category::take(3)->get();
echo "\nSample categories: " . $categories->count() . "\n";
foreach ($categories as $category) {
    echo "  - {$category->name} (ID: {$category->id})\n";
}

// Check orders
$orders = Order::take(3)->get();
echo "\nSample orders: " . $orders->count() . "\n";
foreach ($orders as $order) {
    echo "  - {$order->order_number} (ID: {$order->id}, Total: {$order->total_amount})\n";
}

// Check revenue
echo "\n--- REVENUE CHECKS ---\n";
$todayRevenue = Order::where('payment_status', 'paid')
    ->whereDate('created_at', today())
    ->sum('total_amount');
echo "Today revenue: " . $todayRevenue . "\n";

$monthRevenue = Order::where('payment_status', 'paid')
    ->whereMonth('created_at', now()->month)
    ->whereYear('created_at', now()->year)
    ->sum('total_amount');
echo "Month revenue: " . $monthRevenue . "\n";

// Check payment statuses
echo "\n--- ORDER PAYMENT STATUSES ---\n";
$paymentStatuses = Order::select('payment_status', DB::raw('count(*) as count'))
    ->groupBy('payment_status')
    ->get();
foreach ($paymentStatuses as $status) {
    echo "  - {$status->payment_status}: {$status->count}\n";
}

// Check order statuses
echo "\n--- ORDER STATUSES ---\n";
$orderStatuses = Order::select('status', DB::raw('count(*) as count'))
    ->groupBy('status')
    ->get();
foreach ($orderStatuses as $status) {
    echo "  - {$status->status}: {$status->count}\n";
}

echo "\n=== DEBUG COMPLETE ===\n";
