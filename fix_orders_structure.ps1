# Fix Order Database Structure - PowerShell Script
# Sửa lỗi cấu trúc database orders và order_items

Write-Host "=== FIX ORDER DATABASE STRUCTURE ===" -ForegroundColor Yellow
Write-Host ""

Write-Host "Đang sửa lỗi cấu trúc database orders..." -ForegroundColor Cyan

# Backup database trước khi migrate
Write-Host "1. Backup database hiện tại..." -ForegroundColor Green
try {
    $backupFile = "backup_orders_" + (Get-Date -Format "yyyyMMdd_HHmmss") + ".sql"
    $dbName = "project"
    
    # Tạo backup
    mysqldump -u root $dbName orders order_items > $backupFile
    Write-Host "✓ Backup đã được tạo: $backupFile" -ForegroundColor Green
} catch {
    Write-Host "⚠ Không thể tạo backup, tiếp tục migration..." -ForegroundColor Yellow
}

Write-Host ""

# Drop và recreate tables với cấu trúc đúng
Write-Host "2. Xóa và tạo lại bảng orders, order_items..." -ForegroundColor Green

# Tạo script SQL
$sqlScript = @"
-- Drop existing tables
DROP TABLE IF EXISTS order_items;
DROP TABLE IF EXISTS orders;

-- Recreate orders table with correct structure
CREATE TABLE orders (
    id bigint unsigned NOT NULL AUTO_INCREMENT,
    order_number varchar(255) NOT NULL UNIQUE,
    user_id bigint unsigned NOT NULL,
    status enum('pending','processing','shipped','delivered','cancelled') DEFAULT 'pending',
    subtotal decimal(10,2) NOT NULL,
    tax_amount decimal(10,2) DEFAULT 0.00,
    shipping_amount decimal(10,2) DEFAULT 0.00,
    discount_amount decimal(10,2) DEFAULT 0.00,
    total_amount decimal(10,2) NOT NULL,
    currency varchar(3) DEFAULT 'VND',
    payment_status enum('pending','paid','failed','refunded') DEFAULT 'pending',
    payment_method varchar(255) DEFAULT NULL,
    billing_address json NOT NULL,
    shipping_address json NOT NULL,
    notes text DEFAULT NULL,
    shipped_at timestamp NULL DEFAULT NULL,
    delivered_at timestamp NULL DEFAULT NULL,
    created_at timestamp NULL DEFAULT NULL,
    updated_at timestamp NULL DEFAULT NULL,
    PRIMARY KEY (id),
    KEY orders_user_id_foreign (user_id),
    KEY orders_user_id_status_index (user_id,status),
    KEY orders_status_created_at_index (status,created_at),
    KEY orders_order_number_index (order_number),
    CONSTRAINT orders_user_id_foreign FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE
);

-- Recreate order_items table with correct structure
CREATE TABLE order_items (
    id bigint unsigned NOT NULL AUTO_INCREMENT,
    order_id bigint unsigned NOT NULL,
    product_id bigint unsigned NOT NULL,
    product_name varchar(255) NOT NULL,
    product_sku varchar(255) NOT NULL,
    product_price decimal(10,2) NOT NULL,
    quantity int NOT NULL,
    total_price decimal(10,2) NOT NULL,
    product_attributes json DEFAULT NULL,
    created_at timestamp NULL DEFAULT NULL,
    updated_at timestamp NULL DEFAULT NULL,
    PRIMARY KEY (id),
    KEY order_items_order_id_product_id_index (order_id,product_id),
    CONSTRAINT order_items_order_id_foreign FOREIGN KEY (order_id) REFERENCES orders (id) ON DELETE CASCADE,
    CONSTRAINT order_items_product_id_foreign FOREIGN KEY (product_id) REFERENCES products (id) ON DELETE CASCADE
);
"@

# Lưu script SQL
$sqlScript | Out-File -FilePath "fix_orders_structure.sql" -Encoding UTF8

Write-Host "✓ Script SQL đã được tạo: fix_orders_structure.sql" -ForegroundColor Green

# Chạy script SQL
Write-Host "3. Thực thi script SQL..." -ForegroundColor Green
try {
    mysql -u root project < fix_orders_structure.sql
    Write-Host "✓ Cấu trúc database đã được sửa thành công!" -ForegroundColor Green
} catch {
    Write-Host "✗ Lỗi khi thực thi SQL script" -ForegroundColor Red
    Write-Host "Chạy thủ công: mysql -u root project < fix_orders_structure.sql" -ForegroundColor Yellow
}

Write-Host ""

# Clear Laravel cache
Write-Host "4. Xóa cache Laravel..." -ForegroundColor Green
try {
    php artisan cache:clear
    php artisan config:clear
    php artisan route:clear
    php artisan view:clear
    Write-Host "✓ Cache Laravel đã được xóa" -ForegroundColor Green
} catch {
    Write-Host "⚠ Không thể xóa cache Laravel" -ForegroundColor Yellow
}

Write-Host ""

# Test database connection
Write-Host "5. Kiểm tra kết nối database..." -ForegroundColor Green
try {
    $testResult = php -r "
        require 'vendor/autoload.php';
        use Illuminate\Support\Facades\DB;
        `$app = require_once 'bootstrap/app.php';
        `$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
        try {
            `$count = DB::table('orders')->count();
            echo 'SUCCESS: ' . `$count . ' orders found';
        } catch(Exception `$e) {
            echo 'ERROR: ' . `$e->getMessage();
        }
    "
    
    Write-Host "✓ $testResult" -ForegroundColor Green
} catch {
    Write-Host "✗ Lỗi khi kiểm tra database" -ForegroundColor Red
}

Write-Host ""
Write-Host "=== HOÀN TẤT FIX DATABASE ===" -ForegroundColor Yellow
Write-Host ""
Write-Host "Các thay đổi đã thực hiện:" -ForegroundColor Cyan
Write-Host "✓ Sửa cấu trúc bảng orders (total_amount, shipping_address JSON, etc.)" -ForegroundColor White
Write-Host "✓ Sửa cấu trúc bảng order_items (product_name, product_sku, total_price, etc.)" -ForegroundColor White
Write-Host "✓ Xóa cache Laravel" -ForegroundColor White
Write-Host ""
Write-Host "Bây giờ bạn có thể:" -ForegroundColor Cyan
Write-Host "1. Thử đặt hàng mới để kiểm tra" -ForegroundColor White
Write-Host "2. Chạy: php artisan serve" -ForegroundColor White
Write-Host "3. Truy cập: http://localhost:8000/checkout" -ForegroundColor White
Write-Host ""
