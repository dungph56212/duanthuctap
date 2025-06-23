<?php
// File debug để kiểm tra tài khoản admin
// Truy cập: http://127.0.0.1:8000/debug-admin.php

require_once __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Http\Kernel::class);

use App\Models\User;
use Illuminate\Support\Facades\Hash;

echo "<h2>Debug Admin Account</h2>";

try {
    // Kiểm tra tài khoản admin
    $admin = User::where('email', 'tiendung08102005@gmail.com')->first();
    
    if ($admin) {
        echo "<h3>✅ Tài khoản admin tồn tại:</h3>";
        echo "<ul>";
        echo "<li>ID: " . $admin->id . "</li>";
        echo "<li>Name: " . $admin->name . "</li>";
        echo "<li>Email: " . $admin->email . "</li>";
        echo "<li>Is Admin: " . ($admin->is_admin ? 'Yes' : 'No') . "</li>";
        echo "<li>Is Active: " . ($admin->is_active ? 'Yes' : 'No') . "</li>";
        echo "<li>Email Verified: " . ($admin->email_verified_at ? 'Yes' : 'No') . "</li>";
        echo "</ul>";
        
        // Test password
        $testPassword = 'Dung08102005#';
        $passwordMatch = Hash::check($testPassword, $admin->password);
        echo "<h3>🔐 Test Password:</h3>";
        echo "<p>Password '" . $testPassword . "' match: " . ($passwordMatch ? '✅ YES' : '❌ NO') . "</p>";
        
        // Nếu password không match, tạo lại
        if (!$passwordMatch) {
            echo "<h3>🔧 Fixing password...</h3>";
            $admin->password = Hash::make($testPassword);
            $admin->save();
            echo "<p>✅ Password updated!</p>";
        }
        
        // Đảm bảo is_admin = true
        if (!$admin->is_admin) {
            echo "<h3>🔧 Setting as admin...</h3>";
            $admin->is_admin = true;
            $admin->save();
            echo "<p>✅ Admin status updated!</p>";
        }
        
        // Đảm bảo is_active = true
        if (!$admin->is_active) {
            echo "<h3>🔧 Activating account...</h3>";
            $admin->is_active = true;
            $admin->save();
            echo "<p>✅ Account activated!</p>";
        }
        
    } else {
        echo "<h3>❌ Tài khoản admin không tồn tại</h3>";
        echo "<h3>🔧 Tạo tài khoản admin mới...</h3>";
        
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'tiendung08102005@gmail.com',
            'password' => Hash::make('Dung08102005#'),
            'phone' => '+84123456789',
            'is_admin' => true,
            'is_active' => true,
            'email_verified_at' => now(),
        ]);
        
        echo "<p>✅ Tài khoản admin đã được tạo!</p>";
        echo "<ul>";
        echo "<li>ID: " . $admin->id . "</li>";
        echo "<li>Name: " . $admin->name . "</li>";
        echo "<li>Email: " . $admin->email . "</li>";
        echo "<li>Password: Dung08102005#</li>";
        echo "</ul>";
    }
    
    echo "<hr>";
    echo "<h3>📊 Thống kê users:</h3>";
    $totalUsers = User::count();
    $adminUsers = User::where('is_admin', true)->count();
    $regularUsers = User::where('is_admin', false)->count();
    
    echo "<ul>";
    echo "<li>Total users: " . $totalUsers . "</li>";
    echo "<li>Admin users: " . $adminUsers . "</li>";
    echo "<li>Regular users: " . $regularUsers . "</li>";
    echo "</ul>";
    
    echo "<hr>";
    echo "<h3>🔗 Links:</h3>";
    echo '<p><a href="/admin/login">👉 Đăng nhập Admin</a></p>';
    echo '<p><a href="/login">👉 Đăng nhập Client</a></p>';
    
} catch (Exception $e) {
    echo "<h3>❌ Lỗi:</h3>";
    echo "<p>" . $e->getMessage() . "</p>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}
?>
