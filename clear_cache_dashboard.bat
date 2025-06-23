@echo off
echo Clearing Laravel cache...

echo Clearing config cache...
php artisan config:clear

echo Clearing route cache...
php artisan route:clear

echo Clearing view cache...
php artisan view:clear

echo Clearing cache...
php artisan cache:clear

echo Optimizing for production...
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo Cache cleared and optimized!
pause
