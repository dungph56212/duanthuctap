# Test Checkout Form Script
# Kiểm tra xem website có hoạt động đúng không

Write-Host "=== KIỂM TRA WEBSITE BOOKSTORE ===" -ForegroundColor Yellow
Write-Host ""

# Kiểm tra Laravel server có chạy không
Write-Host "1. Kiểm tra Laravel server..." -ForegroundColor Cyan
try {
    $response = Invoke-WebRequest -Uri "http://localhost:8000" -Method GET -TimeoutSec 10
    if ($response.StatusCode -eq 200) {
        Write-Host "✓ Laravel server đang chạy (Port 8000)" -ForegroundColor Green
    }
} catch {
    Write-Host "✗ Laravel server không chạy hoặc lỗi" -ForegroundColor Red
    Write-Host "Chạy: php artisan serve --host=0.0.0.0 --port=8000" -ForegroundColor Yellow
}

Write-Host ""

# Kiểm tra database connection
Write-Host "2. Kiểm tra kết nối database..." -ForegroundColor Cyan
try {
    $dbCheck = php -r "
        require 'vendor/autoload.php';
        use Illuminate\Support\Facades\DB;
        $app = require_once 'bootstrap/app.php';
        $app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
        try {
            DB::connection()->getPdo();
            echo 'SUCCESS';
        } catch(Exception \$e) {
            echo 'ERROR: ' . \$e->getMessage();
        }
    "
    
    if ($dbCheck -like "*SUCCESS*") {
        Write-Host "✓ Database kết nối thành công" -ForegroundColor Green
    } else {
        Write-Host "✗ Database lỗi: $dbCheck" -ForegroundColor Red
    }
} catch {
    Write-Host "✗ Không thể kiểm tra database" -ForegroundColor Red
}

Write-Host ""

# Kiểm tra các route quan trọng
Write-Host "3. Kiểm tra các route quan trọng..." -ForegroundColor Cyan

$routes = @(
    @{url="http://localhost:8000"; name="Trang chủ"},
    @{url="http://localhost:8000/products"; name="Danh sách sản phẩm"},
    @{url="http://localhost:8000/cart"; name="Giỏ hàng"},
    @{url="http://localhost:8000/checkout"; name="Thanh toán"},
    @{url="http://localhost:8000/login"; name="Đăng nhập"},
    @{url="http://localhost:8000/admin/login"; name="Admin login"}
)

foreach ($route in $routes) {
    try {
        $response = Invoke-WebRequest -Uri $route.url -Method GET -TimeoutSec 5
        if ($response.StatusCode -eq 200) {
            Write-Host "✓ $($route.name)" -ForegroundColor Green
        } else {
            Write-Host "? $($route.name) - Status: $($response.StatusCode)" -ForegroundColor Yellow
        }
    } catch {
        Write-Host "✗ $($route.name) - Lỗi: $($_.Exception.Message)" -ForegroundColor Red
    }
}

Write-Host ""

# Kiểm tra API địa chỉ
Write-Host "4. Kiểm tra API địa chỉ Việt Nam..." -ForegroundColor Cyan
try {
    $response = Invoke-WebRequest -Uri "https://provinces.open-api.vn/api/p/" -Method GET -TimeoutSec 10
    if ($response.StatusCode -eq 200) {
        $data = $response.Content | ConvertFrom-Json
        Write-Host "✓ API địa chỉ hoạt động - Có $($data.Count) tỉnh thành" -ForegroundColor Green
    }
} catch {
    Write-Host "✗ API địa chỉ lỗi - Sẽ dùng dữ liệu fallback" -ForegroundColor Yellow
}

Write-Host ""

# Kiểm tra file quan trọng
Write-Host "5. Kiểm tra các file quan trọng..." -ForegroundColor Cyan

$files = @(
    "resources/views/client/checkout.blade.php",
    "app/Http/Controllers/Client/OrderController.php", 
    "app/Http/Controllers/Client/CartController.php",
    "routes/web.php",
    ".env"
)

foreach ($file in $files) {
    if (Test-Path $file) {
        Write-Host "✓ $file" -ForegroundColor Green
    } else {
        Write-Host "✗ $file - Không tồn tại" -ForegroundColor Red
    }
}

Write-Host ""

# Hướng dẫn debug
Write-Host "=== HƯỚNG DẪN DEBUG ===" -ForegroundColor Yellow
Write-Host ""
Write-Host "Nếu form checkout bị trắng:" -ForegroundColor Cyan
Write-Host "1. Mở Developer Tools (F12)" -ForegroundColor White
Write-Host "2. Vào tab Console để xem lỗi JavaScript" -ForegroundColor White
Write-Host "3. Vào tab Network để xem API calls" -ForegroundColor White
Write-Host "4. Kiểm tra jQuery có load không" -ForegroundColor White
Write-Host ""

Write-Host "Các lệnh hữu ích:" -ForegroundColor Cyan
Write-Host "php artisan serve --host=0.0.0.0 --port=8000" -ForegroundColor White
Write-Host "php artisan migrate:fresh --seed" -ForegroundColor White
Write-Host "php artisan cache:clear" -ForegroundColor White
Write-Host "php artisan config:clear" -ForegroundColor White
Write-Host "php artisan route:clear" -ForegroundColor White
Write-Host ""

Write-Host "=== KIỂM TRA HOÀN TẤT ===" -ForegroundColor Yellow
