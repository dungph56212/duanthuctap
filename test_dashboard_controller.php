<?php
// Simple test for dashboard controller
echo "Testing dashboard controller...\n";

// Check if we can access the controller directly
try {
    require_once 'vendor/autoload.php';
    $app = require_once 'bootstrap/app.php';
    $kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
    $kernel->bootstrap();
    
    use App\Http\Controllers\Admin\DashboardController;
    use App\Models\Order;
    
    // Test order counts directly
    echo "Testing Order model:\n";
    echo "- Total orders: " . Order::count() . "\n";
    echo "- Pending orders: " . Order::where('status', 'pending')->count() . "\n";
    echo "- Delivered orders: " . Order::where('status', 'delivered')->count() . "\n";
    echo "- Total revenue: " . Order::where('payment_status', 'paid')->sum('total_amount') . "\n";
    
    // Test controller
    echo "\nTesting DashboardController:\n";
    $controller = new DashboardController();
    $response = $controller->index();
    
    echo "Controller executed successfully!\n";
    
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
}
?>
