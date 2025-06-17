# Complete Laravel Setup Script
Write-Host "=== LARAVEL COMPLETE SETUP ===" -ForegroundColor Green
Write-Host ""

# Step 1: Generate APP_KEY
Write-Host "1. Generating Application Key..." -ForegroundColor Yellow
php artisan key:generate

# Step 2: Clear all caches
Write-Host "2. Clearing caches..." -ForegroundColor Yellow
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

# Step 3: Create storage link
Write-Host "3. Creating storage link..." -ForegroundColor Yellow
php artisan storage:link

# Step 4: Set up database
Write-Host "4. Setting up database..." -ForegroundColor Yellow
php artisan migrate:fresh --seed

# Step 5: Set permissions (if needed)
Write-Host "5. Setting permissions..." -ForegroundColor Yellow
# For Windows, these might not be necessary, but good to have

Write-Host ""
Write-Host "=== SETUP COMPLETED! ===" -ForegroundColor Green
Write-Host ""
Write-Host "🌐 Website: http://localhost:8000" -ForegroundColor Cyan
Write-Host "🔐 Admin Panel: http://localhost:8000/admin/login" -ForegroundColor Cyan
Write-Host ""
Write-Host "👤 Admin Credentials:" -ForegroundColor Yellow
Write-Host "   Email: admin@example.com" -ForegroundColor White
Write-Host "   Password: password" -ForegroundColor White
Write-Host ""
Write-Host "🚀 Ready to use!" -ForegroundColor Green
