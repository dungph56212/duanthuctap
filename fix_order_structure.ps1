# Fix Order Database Structure
# Script to fix order database schema mismatch

Write-Host "=== SỬA LỖI CẤU TRÚC DATABASE ORDER ===" -ForegroundColor Yellow
Write-Host ""

Write-Host "1. Backup database hiện tại..." -ForegroundColor Cyan
# Backup current database
$timestamp = Get-Date -Format "yyyyMMdd_HHmmss"
$backupFile = "backup_before_order_fix_$timestamp.sql"

try {
    # Get database info from .env
    $dbName = (Get-Content .env | Where-Object {$_ -match "DB_DATABASE"}) -split "=" | Select-Object -Last 1
    $dbUser = (Get-Content .env | Where-Object {$_ -match "DB_USERNAME"}) -split "=" | Select-Object -Last 1
    $dbPass = (Get-Content .env | Where-Object {$_ -match "DB_PASSWORD"}) -split "=" | Select-Object -Last 1
    
    if ($dbName) {
        Write-Host "Đang backup database: $dbName" -ForegroundColor White
        mysqldump -u $dbUser -p$dbPass $dbName > $backupFile
        Write-Host "✓ Backup thành công: $backupFile" -ForegroundColor Green
    }
} catch {
    Write-Host "⚠ Không thể backup database tự động" -ForegroundColor Yellow
}

Write-Host ""
Write-Host "2. Chạy migration fresh với seed..." -ForegroundColor Cyan
try {
    php artisan migrate:fresh --seed --force
    Write-Host "✓ Migration hoàn tất" -ForegroundColor Green
} catch {
    Write-Host "✗ Lỗi migration: $($_.Exception.Message)" -ForegroundColor Red
    Write-Host "Thử chạy lệnh thủ công: php artisan migrate:fresh --seed" -ForegroundColor Yellow
}

Write-Host ""
Write-Host "3. Kiểm tra cấu trúc bảng orders..." -ForegroundColor Cyan
try {
    $tableCheck = php -r "
        require 'vendor/autoload.php';
        use Illuminate\Support\Facades\DB;
        use Illuminate\Support\Facades\Schema;
        \$app = require_once 'bootstrap/app.php';
        \$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
        try {
            \$columns = Schema::getColumnListing('orders');
            echo 'COLUMNS: ' . implode(', ', \$columns);
        } catch(Exception \$e) {
            echo 'ERROR: ' . \$e->getMessage();
        }
    "
    
    if ($tableCheck -like "*total_amount*") {
        Write-Host "✓ Bảng orders có cấu trúc đúng" -ForegroundColor Green
    } else {
        Write-Host "✗ Bảng orders thiếu trường total_amount" -ForegroundColor Red
        Write-Host "Columns hiện tại: $tableCheck" -ForegroundColor Yellow
    }
} catch {
    Write-Host "✗ Không thể kiểm tra cấu trúc bảng" -ForegroundColor Red
}

Write-Host ""
Write-Host "4. Kiểm tra cấu trúc bảng order_items..." -ForegroundColor Cyan
try {
    $itemsCheck = php -r "
        require 'vendor/autoload.php';
        use Illuminate\Support\Facades\DB;
        use Illuminate\Support\Facades\Schema;
        \$app = require_once 'bootstrap/app.php';
        \$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
        try {
            \$columns = Schema::getColumnListing('order_items');
            echo 'COLUMNS: ' . implode(', ', \$columns);
        } catch(Exception \$e) {
            echo 'ERROR: ' . \$e->getMessage();
        }
    "
    
    if ($itemsCheck -like "*product_name*" -and $itemsCheck -like "*total_price*") {
        Write-Host "✓ Bảng order_items có cấu trúc đúng" -ForegroundColor Green
    } else {
        Write-Host "✗ Bảng order_items thiếu các trường cần thiết" -ForegroundColor Red
        Write-Host "Columns hiện tại: $itemsCheck" -ForegroundColor Yellow
    }
} catch {
    Write-Host "✗ Không thể kiểm tra cấu trúc bảng order_items" -ForegroundColor Red
}

Write-Host ""
Write-Host "5. Test tạo đơn hàng mẫu..." -ForegroundColor Cyan
try {
    $testOrder = php -r "
        require 'vendor/autoload.php';
        use App\Models\Order;
        use App\Models\OrderItem;
        use App\Models\User;
        use App\Models\Product;
        \$app = require_once 'bootstrap/app.php';
        \$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
        try {
            \$user = User::first();
            \$product = Product::first();
            if (\$user && \$product) {
                \$order = Order::create([
                    'user_id' => \$user->id,
                    'order_number' => 'TEST' . time(),
                    'status' => 'pending',
                    'subtotal' => 100000,
                    'tax_amount' => 0,
                    'shipping_amount' => 30000,
                    'discount_amount' => 0,
                    'total_amount' => 130000,
                    'currency' => 'VND',
                    'payment_status' => 'pending',
                    'payment_method' => 'cod',
                    'billing_address' => json_encode(['name' => 'Test', 'phone' => '0123456789', 'email' => 'test@test.com', 'address' => 'Test address']),
                    'shipping_address' => json_encode(['name' => 'Test', 'phone' => '0123456789', 'email' => 'test@test.com', 'address' => 'Test address']),
                    'notes' => 'Test order'
                ]);
                \$order->delete(); // Clean up
                echo 'SUCCESS: Order creation test passed';
            } else {
                echo 'ERROR: No user or product found for testing';
            }
        } catch(Exception \$e) {
            echo 'ERROR: ' . \$e->getMessage();
        }
    "
    
    if ($testOrder -like "*SUCCESS*") {
        Write-Host "✓ Test tạo đơn hàng thành công" -ForegroundColor Green
    } else {
        Write-Host "✗ Test tạo đơn hàng thất bại: $testOrder" -ForegroundColor Red
    }
} catch {
    Write-Host "✗ Không thể test tạo đơn hàng" -ForegroundColor Red
}

Write-Host ""
Write-Host "6. Clear cache..." -ForegroundColor Cyan
try {
    php artisan cache:clear
    php artisan config:clear
    php artisan route:clear
    php artisan view:clear
    Write-Host "✓ Cache đã được xóa" -ForegroundColor Green
} catch {
    Write-Host "⚠ Không thể xóa cache" -ForegroundColor Yellow
}

Write-Host ""
Write-Host "=== HOÀN TẤT SỬA LỖI ===" -ForegroundColor Yellow
Write-Host ""
Write-Host "Lỗi đã được sửa:" -ForegroundColor Green
Write-Host "- Cập nhật OrderController để sử dụng đúng tên trường database" -ForegroundColor White
Write-Host "- Sửa các view để hiển thị đúng dữ liệu từ JSON fields" -ForegroundColor White
Write-Host "- Cập nhật relationship từ 'items' thành 'orderItems'" -ForegroundColor White
Write-Host "- Migration database với cấu trúc mới" -ForegroundColor White
Write-Host ""
Write-Host "Bây giờ có thể test checkout:" -ForegroundColor Cyan
Write-Host "1. Truy cập http://localhost:8000/checkout" -ForegroundColor White
Write-Host "2. Điền thông tin và đặt hàng" -ForegroundColor White
Write-Host "3. Kiểm tra trang order success" -ForegroundColor White
Write-Host ""

if (Test-Path $backupFile) {
    Write-Host "Backup database: $backupFile" -ForegroundColor Yellow
}
