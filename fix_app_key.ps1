# Fix Laravel APP_KEY Error
Write-Host "Fixing Laravel APP_KEY..." -ForegroundColor Yellow

# Generate new application key
php artisan key:generate

Write-Host "APP_KEY has been generated successfully!" -ForegroundColor Green
Write-Host "You can now run the application." -ForegroundColor Cyan

# Optional: Run other setup commands
Write-Host ""
Write-Host "Running additional setup..." -ForegroundColor Yellow

# Clear cache
php artisan config:clear
php artisan cache:clear

# Run migrations if needed
Write-Host "Setting up database..." -ForegroundColor Yellow
php artisan migrate:fresh --seed

Write-Host ""
Write-Host "Setup completed! You can now access:" -ForegroundColor Green
Write-Host "- Website: http://localhost:8000" -ForegroundColor White
Write-Host "- Admin: http://localhost:8000/admin/login" -ForegroundColor White
Write-Host "- Admin credentials: admin@example.com / password" -ForegroundColor Cyan
