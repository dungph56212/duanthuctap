<?php

// Script tạo tài khoản admin
require_once __DIR__ . '/vendor/autoload.php';

use Illuminate\Support\Facades\Hash;
use App\Models\User;

// Khởi tạo Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    // Kiểm tra xem đã có admin chưa
    $adminExists = User::where('is_admin', true)->exists();
    
    if (!$adminExists) {
        // Tạo tài khoản admin mới
        $admin = User::create([
            'name' => 'Administrator',
            'email' => 'admin@bookstore.com',
            'password' => Hash::make('admin123'),
            'is_admin' => true,
            'is_active' => true,
            'email_verified_at' => now(),
        ]);
        
        echo "✅ Đã tạo tài khoản admin thành công!\n";
        echo "📧 Email: admin@bookstore.com\n";
        echo "🔑 Password: admin123\n";
        echo "🌐 Đăng nhập tại: http://127.0.0.1:8000/admin/login\n";
    } else {
        echo "ℹ️ Tài khoản admin đã tồn tại.\n";
        
        // Hiển thị danh sách admin
        $admins = User::where('is_admin', true)->get();
        echo "📋 Danh sách admin hiện tại:\n";
        foreach ($admins as $admin) {
            echo "  - {$admin->name} ({$admin->email})\n";
        }
    }
    
    // Kiểm tra và sửa password admin đầu tiên nếu cần
    $firstAdmin = User::where('is_admin', true)->first();
    if ($firstAdmin && !Hash::check('admin123', $firstAdmin->password)) {
        $firstAdmin->update([
            'password' => Hash::make('admin123')
        ]);
        echo "🔧 Đã reset password cho admin đầu tiên thành 'admin123'\n";
    }
    
} catch (Exception $e) {
    echo "❌ Lỗi: " . $e->getMessage() . "\n";
}
