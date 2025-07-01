<?php

// Script tạo tài khoản admin cho BookStore
// Chạy: php create_admin_account.php

require_once 'vendor/autoload.php';

// Khởi tạo Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

try {
    echo "=== TẠO TÀI KHOẢN ADMIN BOOKSTORE ===\n";
    echo "Email: bookstore@admin.com\n";
    echo "Password: admin123456\n";
    echo "Đang tạo...\n\n";

    // Kiểm tra xem admin đã tồn tại chưa
    $existingAdmin = DB::table('users')->where('email', 'bookstore@admin.com')->first();
    
    if ($existingAdmin) {
        echo "⚠️ Tài khoản admin đã tồn tại!\n";
        echo "Đang cập nhật mật khẩu...\n";
        
        DB::table('users')
            ->where('email', 'bookstore@admin.com')
            ->update([
                'password' => Hash::make('admin123456'),
                'is_admin' => true,
                'is_active' => true,
                'email_verified_at' => now(),
                'updated_at' => now()
            ]);
            
        echo "✅ Đã cập nhật mật khẩu cho tài khoản admin!\n";
    } else {
        // Tạo tài khoản admin mới
        DB::table('users')->insert([
            'name' => 'BookStore Admin',
            'email' => 'bookstore@admin.com',
            'password' => Hash::make('admin123456'),
            'phone' => '0123456789',
            'is_admin' => true,
            'is_active' => true,
            'email_verified_at' => now(),
            'created_at' => now(),
            'updated_at' => now()
        ]);
        
        echo "✅ Đã tạo tài khoản admin thành công!\n";
    }
    
    echo "\n=== THÔNG TIN ĐĂNG NHẬP ===\n";
    echo "URL Admin: http://localhost:8000/admin/login\n";
    echo "Email: bookstore@admin.com\n";
    echo "Password: admin123456\n";
    echo "\n✅ Hoàn tất!\n";
    echo "Bây giờ bạn có thể đăng nhập vào admin panel.\n";

} catch (Exception $e) {
    echo "❌ Lỗi: " . $e->getMessage() . "\n";
    echo "\nVui lòng kiểm tra:\n";
    echo "1. Laragon đã khởi động MySQL chưa\n";
    echo "2. Database 'project' đã tồn tại chưa\n";
    echo "3. Bảng users đã được tạo chưa (chạy: php artisan migrate)\n";
}
