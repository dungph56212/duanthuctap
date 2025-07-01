@echo off
echo === TAO TAI KHOAN ADMIN BOOKSTORE ===
echo.

echo Dang tao tai khoan admin...
php artisan db:seed --class=AdminSeeder

echo.
echo === THONG TIN DANG NHAP ===
echo URL Admin: http://localhost:8000/admin/login
echo Email: bookstore@admin.com
echo Password: admin123456
echo.

echo Nhan Enter de dong cua so...
pause > nul
