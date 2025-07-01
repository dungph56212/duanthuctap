@echo off
echo ========================================
echo    KHAC PHUC LOI MYSQL CONNECTION
echo ========================================
echo.

echo Buoc 1: Kiem tra Laragon...
tasklist /FI "IMAGENAME eq laragon.exe" 2>NUL | find /I /N "laragon.exe" >NUL
if "%ERRORLEVEL%"=="0" (
    echo ✓ Laragon dang chay!
) else (
    echo ✗ Laragon chua chay!
    echo Vui long mo Laragon va bam "Start All"
    echo Sau do chay lai file nay.
    echo.
    pause
    exit
)

echo.
echo Buoc 2: Doi DB_HOST trong file .env...
if exist ".env" (
    powershell -Command "(Get-Content .env) -replace 'DB_HOST=127.0.0.1', 'DB_HOST=localhost' | Set-Content .env"
    echo ✓ Da cap nhat DB_HOST thanh localhost!
) else (
    echo ✗ Khong tim thay file .env!
)

echo.
echo Buoc 3: Xoa cache Laravel...
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
echo ✓ Da xoa cache Laravel!

echo.
echo Buoc 4: Tao database 'project'...
mysql -u root -h localhost -e "CREATE DATABASE IF NOT EXISTS project;" 2>NUL
if %ERRORLEVEL% EQU 0 (
    echo ✓ Database 'project' da duoc tao!
) else (
    echo ✗ Khong the tao database. Hay thu tao bang tay.
)

echo.
echo Buoc 5: Tao bang sessions...
php artisan session:table 2>NUL
php artisan migrate --force 2>NUL
echo ✓ Da tao bang sessions!

echo.
echo ========================================
echo              KET QUA
echo ========================================
echo 1. Neu van gap loi, hay khoi dong lai Laragon
echo 2. Kiem tra trong Laragon: Apache va MySQL deu phai co mau xanh
echo 3. Thu truy cap phpMyAdmin: http://localhost/phpmyadmin
echo.
echo Bay gio hay thu chay website:
echo php artisan serve
echo.
echo Nhan Enter de dong cua so nay...
pause
