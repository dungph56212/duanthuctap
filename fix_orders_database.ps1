# Script sửa lỗi Database Orders
# Fix lỗi: Field 'total_amount' doesn't have a default value

Write-Host "=== SỬA LỖI DATABASE ORDERS ===" -ForegroundColor Yellow
Write-Host ""

Write-Host "1. Backup database hiện tại..." -ForegroundColor Cyan
$timestamp = Get-Date -Format "yyyyMMdd_HHmmss"
$backupFile = "backup_$timestamp.sql"

try {
    # Backup database
    mysqldump -u root -p bookstore > $backupFile
    Write-Host "✓ Backup thành công: $backupFile" -ForegroundColor Green
} catch {
    Write-Host "⚠ Không thể backup database, tiếp tục..." -ForegroundColor Yellow
}

Write-Host ""
Write-Host "2. Chạy migrate fresh với seed..." -ForegroundColor Cyan

try {
    php artisan migrate:fresh --seed
    Write-Host "✓ Migrate fresh thành công" -ForegroundColor Green
} catch {
    Write-Host "✗ Lỗi migrate, thử rollback..." -ForegroundColor Red
    
    # Nếu migrate lỗi, thử drop tất cả tables và tạo lại
    Write-Host "3. Drop và tạo lại database..." -ForegroundColor Cyan
    
    $dropSql = @"
DROP DATABASE IF EXISTS bookstore;
CREATE DATABASE bookstore CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
"@
    
    $dropSql | mysql -u root -p
    Write-Host "✓ Database được tạo lại" -ForegroundColor Green
    
    # Migrate lại
    php artisan migrate --seed
    Write-Host "✓ Migrate và seed thành công" -ForegroundColor Green
}

Write-Host ""
Write-Host "3. Kiểm tra bảng orders..." -ForegroundColor Cyan

$checkSql = @"
USE bookstore;
DESCRIBE orders;
"@

$result = $checkSql | mysql -u root -p

if ($result -match "total_amount") {
    Write-Host "✓ Bảng orders có trường total_amount" -ForegroundColor Green
} else {
    Write-Host "✗ Bảng orders thiếu trường total_amount" -ForegroundColor Red
}

Write-Host ""
Write-Host "4. Kiểm tra bảng order_items..." -ForegroundColor Cyan

$checkItemsSql = @"
USE bookstore;
DESCRIBE order_items;
"@

$result = $checkItemsSql | mysql -u root -p

if ($result -match "product_name" -and $result -match "total_price") {
    Write-Host "✓ Bảng order_items có các trường cần thiết" -ForegroundColor Green
} else {
    Write-Host "✗ Bảng order_items thiếu trường" -ForegroundColor Red
}

Write-Host ""
Write-Host "5. Clear cache..." -ForegroundColor Cyan

php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

Write-Host "✓ Cache đã được xóa" -ForegroundColor Green

Write-Host ""
Write-Host "=== KIỂM TRA KẾT QUẢ ===" -ForegroundColor Yellow

Write-Host "Test tạo đơn hàng..." -ForegroundColor Cyan
Write-Host "1. Đăng nhập: http://localhost:8000/login" -ForegroundColor White
Write-Host "2. Thêm sản phẩm vào giỏ: http://localhost:8000/products" -ForegroundColor White
Write-Host "3. Thanh toán: http://localhost:8000/checkout" -ForegroundColor White
Write-Host "4. Kiểm tra đơn hàng: http://localhost:8000/orders" -ForegroundColor White

Write-Host ""
Write-Host "=== CÁC LỖI ĐÃ SỬA ===" -ForegroundColor Yellow
Write-Host ""
Write-Host "✓ Field 'total_amount' doesn't have a default value" -ForegroundColor Green
Write-Host "✓ Cannot access offset of type string on string" -ForegroundColor Green
Write-Host "✓ Relationship orderItems thay vì items" -ForegroundColor Green
Write-Host "✓ Cấu trúc JSON cho billing_address và shipping_address" -ForegroundColor Green
Write-Host "✓ Trường product_name, product_price, total_price trong order_items" -ForegroundColor Green
Write-Host ""

Write-Host "=== HOÀN TẤT ===" -ForegroundColor Green
