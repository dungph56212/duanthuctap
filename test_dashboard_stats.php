<?php
// Test dashboard data
require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\Product;
use App\Models\Order;
use App\Models\Category;
use App\Models\Coupon;

echo "=== DASHBOARD TEST ===\n\n";

try {
    // Test all models
    echo "PRODUCTS:\n";
    echo "- Total: " . Product::count() . "\n";
    echo "- Active: " . Product::where('is_active', true)->count() . "\n";
    
    echo "\nORDERS:\n";
    echo "- Total: " . Order::count() . "\n";
    echo "- Pending: " . Order::where('status', 'pending')->count() . "\n";
    echo "- Delivered: " . Order::where('status', 'delivered')->count() . "\n";
    
    echo "\nUSERS:\n";
    echo "- Total: " . User::count() . "\n";
    echo "- Customers: " . User::where('is_admin', false)->count() . "\n";
    echo "- Admins: " . User::where('is_admin', true)->count() . "\n";
    
    echo "\nCOUPONS:\n";
    echo "- Total: " . Coupon::count() . "\n";
    echo "- Active: " . Coupon::where('is_active', true)->count() . "\n";
    echo "- Used: " . Coupon::where('used_count', '>', 0)->count() . "\n";
    
    echo "\nREVENUE:\n";
    $totalRevenue = Order::where('payment_status', 'paid')->sum('total_amount');
    echo "- Total revenue: " . number_format($totalRevenue) . " VND\n";
    
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}

echo "\n=== DONE ===\n";
?>
