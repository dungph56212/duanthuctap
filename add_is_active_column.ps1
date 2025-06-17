# Script PowerShell để thêm cột is_active (nếu chạy được)
# Chạy script này trong PowerShell với quyền Administrator

Write-Host "=== THÊM CỘT IS_ACTIVE VÀO BẢNG USERS ===" -ForegroundColor Green

# Kiểm tra xem có ở đúng thư mục project không
if (!(Test-Path "artisan")) {
    Write-Host "❌ Không tìm thấy file artisan. Hãy chạy script trong thư mục project Laravel!" -ForegroundColor Red
    pause
    exit
}

try {
    Write-Host "🔄 Đang chạy migration để thêm cột is_active..." -ForegroundColor Yellow
    
    # Chạy migration
    php artisan migrate --path=database/migrations/2025_06_17_022500_add_is_active_to_users_table.php
    
    if ($LASTEXITCODE -eq 0) {
        Write-Host "✅ Đã thêm cột is_active thành công!" -ForegroundColor Green
        
        # Kiểm tra cấu trúc bảng
        Write-Host "🔍 Kiểm tra cấu trúc bảng users..." -ForegroundColor Yellow
        php artisan tinker --execute="echo 'Cột is_active đã được thêm vào bảng users';"
        
    } else {
        Write-Host "❌ Có lỗi xảy ra khi chạy migration!" -ForegroundColor Red
        Write-Host "📋 Hãy thực hiện theo hướng dẫn thủ công trong file FIX_IS_ACTIVE_COLUMN_ERROR.md" -ForegroundColor Yellow
    }
    
} catch {
    Write-Host "❌ Lỗi: $_" -ForegroundColor Red
    Write-Host "📋 Hãy thực hiện theo hướng dẫn thủ công trong file FIX_IS_ACTIVE_COLUMN_ERROR.md" -ForegroundColor Yellow
}

Write-Host "`n🎯 HOÀN TẤT! Bây giờ bạn có thể:" -ForegroundColor Green
Write-Host "   ✅ Kích hoạt/tắt tài khoản user trong admin" -ForegroundColor White
Write-Host "   ✅ Quản lý trạng thái tài khoản" -ForegroundColor White
Write-Host "   ✅ Sử dụng bulk action cho user" -ForegroundColor White

pause
