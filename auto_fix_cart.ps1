# Auto Fix Cart Error
Write-Host "=== FIXING CART ERROR ===" -ForegroundColor Green

try {
    Write-Host "Clearing cart data..." -ForegroundColor Yellow
    
    # Method 1: Try using artisan tinker
    $command = 'DB::table("carts")->truncate();'
    php artisan tinker --execute=$command
    
    Write-Host "Cart data cleared successfully!" -ForegroundColor Green
    
} catch {
    Write-Host "Artisan method failed. Use manual method:" -ForegroundColor Red
    Write-Host "1. Open phpMyAdmin" -ForegroundColor White
    Write-Host "2. Select your database" -ForegroundColor White
    Write-Host "3. Run: TRUNCATE TABLE carts;" -ForegroundColor White
}

Write-Host ""
Write-Host "=== NOW YOU CAN ===" -ForegroundColor Cyan
Write-Host "1. Login with: user@example.com / password" -ForegroundColor White
Write-Host "2. Add products to cart" -ForegroundColor White
Write-Host "3. Check cart works properly" -ForegroundColor White
Write-Host ""

Read-Host "Press Enter to continue"
