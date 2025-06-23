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
use Illuminate\Http\Request;

class DashboardController extends Controller
{    
    public function index()
    {
        // Debug logging
        Log::info('Dashboard controller called');
        
        // Basic Statistics
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
        
        // Order statistics
        $orderStats = [
            'total' => Order::count(),
            'pending' => Order::where('status', 'pending')->count(),
            'delivered' => Order::where('status', 'delivered')->count(),
            'total_revenue' => Order::where('payment_status', 'paid')->sum('total_amount'),
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
        
        // Coupon statistics
        $couponStats = [
            'total' => Coupon::count(),
            'active' => Coupon::where('is_active', true)->count(),
            'used' => Coupon::where('used_count', '>', 0)->count(),
            'total_savings' => Order::where('payment_status', 'paid')
                ->whereNotNull('discount_amount')
                ->sum('discount_amount'),
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
            ->sum('total_amount');

        // Recent orders
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
        }

        Log::info('All stats calculated, returning view');

        return view('admin.dashboard', compact(
            'totalUsers',
            'totalProducts', 
            'totalOrders',
            'totalCategories',
            'userStats',
            'orderStats',
            'productStats',
            'couponStats',
            'todayRevenue',
            'monthRevenue',
            'yearRevenue',
            'recentOrders',
            'lowStockProducts',
            'monthlyRevenue'
        ));
    }
}
