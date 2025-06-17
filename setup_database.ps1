# E-commerce Setup Script
# Run this script to set up your e-commerce database

Write-Host "Setting up E-commerce Database..." -ForegroundColor Green

# Run migrations
Write-Host "Running migrations..." -ForegroundColor Yellow
php artisan migrate

# Run seeders
Write-Host "Seeding database..." -ForegroundColor Yellow
php artisan db:seed

Write-Host "E-commerce database setup completed!" -ForegroundColor Green
Write-Host ""
Write-Host "Default admin credentials:" -ForegroundColor Cyan
Write-Host "Email: admin@example.com" -ForegroundColor White
Write-Host "Password: password" -ForegroundColor White
Write-Host ""
Write-Host "Database contains:" -ForegroundColor Cyan
Write-Host "- Categories with subcategories" -ForegroundColor White
Write-Host "- 220+ sample products" -ForegroundColor White
Write-Host "- Sample coupons" -ForegroundColor White
Write-Host "- 50+ sample users" -ForegroundColor White
