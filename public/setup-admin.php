<!DOCTYPE html>
<html>
<head>
    <title>Admin Setup - BookStore</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background: #f5f5f5;
        }
        .container {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }
        .success { color: #28a745; }
        .error { color: #dc3545; }
        .info { color: #17a2b8; }
        .warning { color: #ffc107; }
        .btn {
            background: #007bff;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            margin: 10px 5px;
        }
        .btn:hover { background: #0056b3; }
        .btn-success { background: #28a745; }
        .btn-success:hover { background: #1e7e34; }
        pre {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            border-left: 4px solid #007bff;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔧 Admin Setup - BookStore</h1>
        <p>Trang này giúp bạn tạo và quản lý tài khoản admin.</p>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    try {
        // Khởi tạo Laravel
        require_once __DIR__ . '/vendor/autoload.php';
        $app = require_once __DIR__ . '/bootstrap/app.php';
        $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
        $kernel->bootstrap();
        
        use Illuminate\Support\Facades\Hash;
        use App\Models\User;

        if ($_POST['action'] === 'create_admin') {
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
                
                echo '<div class="success">✅ Đã tạo tài khoản admin thành công!</div>';
                echo '<pre>';
                echo "📧 Email: admin@bookstore.com\n";
                echo "🔑 Password: admin123\n";
                echo "🌐 Đăng nhập tại: <a href='/admin/login'>http://127.0.0.1:8000/admin/login</a>";
                echo '</pre>';
            } else {
                echo '<div class="warning">ℹ️ Tài khoản admin đã tồn tại.</div>';
            }
        }
        
        if ($_POST['action'] === 'reset_password') {
            $firstAdmin = User::where('is_admin', true)->first();
            if ($firstAdmin) {
                $firstAdmin->update([
                    'password' => Hash::make('admin123')
                ]);
                echo '<div class="success">🔧 Đã reset password cho admin thành "admin123"</div>';
            } else {
                echo '<div class="error">❌ Không tìm thấy tài khoản admin nào.</div>';
            }
        }
        
        if ($_POST['action'] === 'list_admins') {
            $admins = User::where('is_admin', true)->get();
            if ($admins->count() > 0) {
                echo '<div class="info">📋 Danh sách admin hiện tại:</div>';
                echo '<pre>';
                foreach ($admins as $admin) {
                    $status = $admin->is_active ? '✅ Active' : '❌ Inactive';
                    echo "• {$admin->name} ({$admin->email}) - {$status}\n";
                }
                echo '</pre>';
            } else {
                echo '<div class="warning">⚠️ Chưa có tài khoản admin nào.</div>';
            }
        }
        
    } catch (Exception $e) {
        echo '<div class="error">❌ Lỗi: ' . htmlspecialchars($e->getMessage()) . '</div>';
    }
}
?>

        <h3>🚀 Các hành động có sẵn:</h3>
        
        <form method="post" style="display: inline;">
            <input type="hidden" name="action" value="create_admin">
            <button type="submit" class="btn btn-success">Tạo Admin Mới</button>
        </form>
        
        <form method="post" style="display: inline;">
            <input type="hidden" name="action" value="reset_password">
            <button type="submit" class="btn">Reset Password Admin</button>
        </form>
        
        <form method="post" style="display: inline;">
            <input type="hidden" name="action" value="list_admins">
            <button type="submit" class="btn">Xem Danh Sách Admin</button>
        </form>

        <hr>
        <h3>📝 Hướng dẫn:</h3>
        <ol>
            <li><strong>Tạo Admin Mới:</strong> Tạo tài khoản admin đầu tiên với email "admin@bookstore.com" và password "admin123"</li>
            <li><strong>Reset Password:</strong> Đặt lại password của admin đầu tiên thành "admin123"</li>
            <li><strong>Xem Danh Sách:</strong> Hiển thị tất cả tài khoản admin hiện có</li>
        </ol>

        <div class="info">
            <p><strong>Lưu ý:</strong> Sau khi tạo admin, hãy truy cập <a href="/admin/login">/admin/login</a> để đăng nhập.</p>
            <p><strong>Bảo mật:</strong> Nhớ xóa file này sau khi hoàn tất setup!</p>
        </div>
        
        <hr>
        <p><a href="/" class="btn">🏠 Về Trang Chủ</a> <a href="/admin/login" class="btn">👨‍💼 Đăng Nhập Admin</a></p>
    </div>
</body>
</html>
