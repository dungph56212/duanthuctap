Route::get('/admin/dashboard-test', function() {
    use App\Models\Order;
    use Illuminate\Support\Facades\DB;
    
    echo "<h1>Dashboard Debug Test</h1>";
    
    echo "<h3>Raw SQL:</h3>";
    echo "Total orders: " . DB::table('orders')->count() . "<br>";
    echo "Pending orders: " . DB::table('orders')->where('status', 'pending')->count() . "<br>";
    echo "Delivered orders: " . DB::table('orders')->where('status', 'delivered')->count() . "<br>";
    echo "Revenue: " . DB::table('orders')->where('payment_status', 'paid')->sum('total_amount') . "<br>";
    
    echo "<h3>Eloquent:</h3>";
    echo "Total orders: " . Order::count() . "<br>";
    echo "Pending orders: " . Order::where('status', 'pending')->count() . "<br>";
    echo "Delivered orders: " . Order::where('status', 'delivered')->count() . "<br>";
    echo "Revenue: " . Order::where('payment_status', 'paid')->sum('total_amount') . "<br>";
    
    echo "<h3>Database Connection:</h3>";
    echo "Connection: " . config('database.default') . "<br>";
    echo "Database: " . config('database.connections.mysql.database') . "<br>";
});
