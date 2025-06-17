# ======================================
# SCRIPT SỬA LỖI TOGGLE STATUS USERS
# ======================================

Write-Host "Đang sửa lỗi toggle status users..." -ForegroundColor Green

# 1. Migrate database để thêm cột is_active
Write-Host "1. Đang migrate database..." -ForegroundColor Yellow
php artisan migrate --force

if ($LASTEXITCODE -eq 0) {
    Write-Host "✅ Migration thành công!" -ForegroundColor Green
} else {
    Write-Host "❌ Migration thất bại!" -ForegroundColor Red
    Write-Host "Vui lòng chạy lệnh thủ công: php artisan migrate --force" -ForegroundColor Yellow
}

# 2. Update tất cả users có is_active = true
Write-Host "2. Đang cập nhật dữ liệu users..." -ForegroundColor Yellow
Write-Host "Vui lòng mở phpMyAdmin và chạy câu lệnh SQL sau:" -ForegroundColor Cyan
Write-Host "UPDATE users SET is_active = 1 WHERE is_active IS NULL;" -ForegroundColor White

# 3. Clear cache
Write-Host "3. Đang xóa cache..." -ForegroundColor Yellow
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear

Write-Host ""
Write-Host "✅ HOÀN TẤT SỬA LỖI!" -ForegroundColor Green
Write-Host ""
Write-Host "Các thay đổi đã thực hiện:" -ForegroundColor Cyan
Write-Host "• ✅ Thêm route admin.users.toggle-status" -ForegroundColor White
Write-Host "• ✅ Thêm method toggleStatus vào UserController" -ForegroundColor White
Write-Host "• ✅ Thêm cột is_active vào bảng users" -ForegroundColor White
Write-Host "• ✅ Cập nhật User model" -ForegroundColor White
Write-Host ""
Write-Host "Bây giờ bạn có thể:" -ForegroundColor Cyan
Write-Host "• Kích hoạt/tạm khóa tài khoản users" -ForegroundColor White
Write-Host "• Quản lý trạng thái người dùng đầy đủ" -ForegroundColor White
Write-Host ""
Write-Host "Nếu migration bị lỗi, vui lòng:" -ForegroundColor Yellow
Write-Host "1. Mở phpMyAdmin" -ForegroundColor White
Write-Host "2. Chọn database của project" -ForegroundColor White
Write-Host "3. Chạy SQL: ALTER TABLE users ADD COLUMN is_active BOOLEAN DEFAULT 1 AFTER is_admin;" -ForegroundColor White
Write-Host "4. Chạy SQL: UPDATE users SET is_active = 1;" -ForegroundColor White

Read-Host "Nhấn Enter để tiếp tục..."
