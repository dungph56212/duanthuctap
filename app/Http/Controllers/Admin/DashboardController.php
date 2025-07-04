<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Product;
use App\Models\Order;
use App\Models\Category;
use App\Models\Coupon;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class DashboardController extends Controller
{    public function index()
    {
        // FORCE DEBUG OUTPUT
        echo "<div style='background: red; color: white; padding: 10px; margin: 10px; font-size: 18px; border: 3px solid yellow;'>DASHBOARD CONTROLLER EXECUTED!</div>";
        
        // Debug logging
        Log::info('Dashboard controller called');
          // Test raw SQL queries to debug
        $totalOrdersRaw = DB::table('orders')->count();
        $pendingOrdersRaw = DB::table('orders')->where('status', 'pending')->count();
        $deliveredOrdersRaw = DB::table('orders')->where('status', 'delivered')->count();
        $revenueRaw = DB::table('orders')->where('payment_status', 'paid')->sum('total_amount');
        
        Log::info('Raw SQL results:', [
            'total_orders' => $totalOrdersRaw,
            'pending_orders' => $pendingOrdersRaw,
            'delivered_orders' => $deliveredOrdersRaw,
            'revenue' => $revenueRaw
        ]);
        
        // Statistics
        $totalUsers = User::count();
        $totalProducts = Product::count();
        $totalOrders = Order::count();
        $totalCategories = Category::count();
        
        Log::info('Basic stats calculated', [
            'users' => $totalUsers,
            'products' => $totalProducts, 
            'orders' => $totalOrders,
            'categories' => $totalCategories
        ]);
        
        // User statistics
        $userStats = [
            'total' => User::count(),
            'customers' => User::where('is_admin', false)->count(),
            'admins' => User::where('is_admin', true)->count(),
            'this_month' => User::whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count(),
        ];
          // Order statistics - using raw SQL values for debugging
        $orderStats = [
            'total' => $totalOrdersRaw,
            'pending' => $pendingOrdersRaw,
            'delivered' => $deliveredOrdersRaw,
            'total_revenue' => $revenueRaw,
        ];
        
        Log::info('Order stats calculated', $orderStats);
        
        // Product statistics
        $productStats = [
            'total' => Product::count(),
            'active' => Product::where('is_active', true)->count(),
            'inactive' => Product::where('is_active', false)->count(),
            'low_stock' => Product::where('manage_stock', true)
                ->where('stock', '<=', 10)
                ->where('is_active', true)
                ->count(),
            'out_of_stock' => Product::where('manage_stock', true)
                ->where('stock', 0)
                ->where('is_active', true)
                ->count(),
        ];
        
        // Revenue statistics
        $todayRevenue = Order::where('payment_status', 'paid')
            ->whereDate('created_at', today())
            ->sum('total_amount');
            
        $monthRevenue = Order::where('payment_status', 'paid')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('total_amount');
            
        $yearRevenue = Order::where('payment_status', 'paid')
            ->whereYear('created_at', now()->year)
            ->sum('total_amount');// Recent orders
        $recentOrders = Order::with('user')
            ->latest()
            ->take(10)
            ->get();

        // Low stock products
        $lowStockProducts = Product::where('manage_stock', true)
            ->where('stock', '<=', 10)
            ->where('is_active', true)
            ->take(10)
            ->get();

        // Monthly revenue chart data (last 12 months)
        $monthlyRevenue = [];
        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $revenue = Order::where('payment_status', 'paid')
                ->whereMonth('created_at', $date->month)
                ->whereYear('created_at', $date->year)
                ->sum('total_amount');
            
            $monthlyRevenue[] = [
                'month' => $date->format('M Y'),
                'revenue' => $revenue
            ];
        }        // Coupon statistics
        $couponStats = [
            'total' => Coupon::count(),
            'active' => Coupon::where('is_active', true)->count(),
            'used' => Coupon::where('used_count', '>', 0)->count(),
            'total_savings' => Order::where('payment_status', 'paid')
                ->whereNotNull('discount_amount')
                ->sum('discount_amount'),
        ];return view('admin.dashboard', compact(
            'totalUsers',
            'totalProducts', 
            'totalOrders',
            'totalCategories',
            'userStats',
            'orderStats',
            'productStats',
            'todayRevenue',
            'monthRevenue',
            'yearRevenue',
            'recentOrders',
            'lowStockProducts',
            'monthlyRevenue',
            'couponStats'
        ));
    }
}
