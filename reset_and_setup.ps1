# Reset and Run Migrations Script
Write-Host "Resetting database and running migrations..." -ForegroundColor Green

# Drop all tables and run migrations fresh
Write-Host "Dropping all tables and running fresh migrations..." -ForegroundColor Yellow
php artisan migrate:fresh

# Run seeders
Write-Host "Seeding database with sample data..." -ForegroundColor Yellow  
php artisan db:seed

Write-Host "Database setup completed successfully!" -ForegroundColor Green
Write-Host ""
Write-Host "Admin login credentials:" -ForegroundColor Cyan
Write-Host "Email: admin@example.com" -ForegroundColor White
Write-Host "Password: password" -ForegroundColor White
