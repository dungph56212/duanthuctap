@echo off
title BookStore Website
echo ========================================
echo        KHOI DONG WEBSITE BOOKSTORE
echo ========================================
echo.

echo Dang kiem tra ket noi database...
php artisan tinker --execute="try { DB::connection()->getPdo(); echo 'Database connected successfully'; } catch (Exception $e) { echo 'Database connection failed: ' . $e->getMessage(); }" 2>NUL

echo.
echo Dang khoi dong website...
echo Website se chay tai: http://localhost:8000
echo.
echo De dung website, nhan Ctrl+C
echo.

php artisan serve
