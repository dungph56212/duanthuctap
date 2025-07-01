@echo off
echo === CHAY TAT CA SEEDERS (BAO GOM ADMIN) ===
echo.

echo Dang chay migrate va seed database...
php artisan migrate --seed

echo.
echo === THONG TIN DANG NHAP ADMIN ===
echo URL Admin: http://localhost:8000/admin/login
echo Email: bookstore@admin.com
echo Password: admin123456
echo.

echo === THONG TIN DANG NHAP USER MAU ===
echo URL User: http://localhost:8000/login
echo Co 50+ user mau voi password: password
echo.

echo Nhan Enter de dong cua so...
pause > nul
