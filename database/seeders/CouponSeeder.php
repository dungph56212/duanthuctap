<?php

namespace Database\Seeders;

use App\Models\Coupon;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class CouponSeeder extends Seeder
{
    public function run(): void
    {
        // Fixed amount coupons
        Coupon::create([
            'code' => 'WELCOME10',
            'name' => 'Chào mừng khách hàng mới',
            'description' => 'Giảm 10.000đ cho đơn hàng đầu tiên',
            'type' => 'fixed',
            'value' => 10000,
            'minimum_amount' => 50000,
            'usage_limit' => 1000,
            'is_active' => true,
            'starts_at' => now(),
            'expires_at' => now()->addMonths(3),
        ]);

        Coupon::create([
            'code' => 'SAVE50K',
            'name' => 'Giảm 50K',
            'description' => 'Giảm 50.000đ cho đơn hàng từ 500.000đ',
            'type' => 'fixed',
            'value' => 50000,
            'minimum_amount' => 500000,
            'usage_limit' => 500,
            'is_active' => true,
            'starts_at' => now(),
            'expires_at' => now()->addMonths(2),
        ]);

        // Percentage coupons
        Coupon::create([
            'code' => 'SALE10',
            'name' => 'Giảm 10%',
            'description' => 'Giảm 10% tổng đơn hàng',
            'type' => 'percentage',
            'value' => 10,
            'minimum_amount' => 100000,
            'usage_limit' => 2000,
            'is_active' => true,
            'starts_at' => now(),
            'expires_at' => now()->addMonth(),
        ]);

        Coupon::create([
            'code' => 'BIGDEAL20',
            'name' => 'Siêu sale 20%',
            'description' => 'Giảm 20% cho đơn hàng từ 1.000.000đ',
            'type' => 'percentage',
            'value' => 20,
            'minimum_amount' => 1000000,
            'usage_limit' => 100,
            'is_active' => true,
            'starts_at' => now(),
            'expires_at' => now()->addWeeks(2),
        ]);

        // Expired coupon (for testing)
        Coupon::create([
            'code' => 'EXPIRED',
            'name' => 'Coupon hết hạn',
            'description' => 'Coupon này đã hết hạn',
            'type' => 'percentage',
            'value' => 15,
            'minimum_amount' => 200000,
            'usage_limit' => 100,
            'is_active' => true,
            'starts_at' => now()->subMonth(),
            'expires_at' => now()->subWeek(),
        ]);
    }
}
