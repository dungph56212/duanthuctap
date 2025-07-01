<?php
require_once __DIR__ . '/vendor/autoload.php';

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

try {
    // Tạo tài khoản admin
    $adminData = [
        'name' => 'Admin',
        'email' => 'admin@bookstore.com',
        'password' => Hash::make('admin123456'),
        'role' => 'admin',
        'is_admin' => true,
        'email_verified_at' => now(),
        'created_at' => now(),
        'updated_at' => now()
    ];

    // Kiểm tra xem admin đã tồn tại chưa
    $existingAdmin = DB::table('users')->where('email', $adminData['email'])->first();
    
    if ($existingAdmin) {
        echo "✅ Admin đã tồn tại:\n";
        echo "📧 Email: " . $existingAdmin->email . "\n";
        echo "🔑 Password: admin123456\n";
        echo "👤 Role: " . ($existingAdmin->role ?? 'admin') . "\n";
    } else {
        // Tạo admin mới
        $adminId = DB::table('users')->insertGetId($adminData);
        
        if ($adminId) {
            echo "🎉 Tạo tài khoản admin thành công!\n";
            echo "📧 Email: " . $adminData['email'] . "\n";
            echo "🔑 Password: admin123456\n";
            echo "👤 Role: admin\n";
            echo "🆔 ID: " . $adminId . "\n";
        } else {
            echo "❌ Lỗi khi tạo tài khoản admin!\n";
        }
    }
    
    echo "\n" . str_repeat("=", 50) . "\n";
    echo "🚀 Truy cập admin panel:\n";
    echo "🌐 URL: http://127.0.0.1:8000/admin/login\n";
    echo "📧 Email: admin@bookstore.com\n";
    echo "🔑 Password: admin123456\n";
    echo str_repeat("=", 50) . "\n";

} catch (Exception $e) {
    echo "❌ Lỗi: " . $e->getMessage() . "\n";
}
?>
