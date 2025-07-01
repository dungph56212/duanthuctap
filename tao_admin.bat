@echo off
echo === TAO TAI KHOAN ADMIN BOOKSTORE ===
echo.
echo Dang tao tai khoan admin...
echo Email: bookstore@admin.com
echo Password: admin123456
echo.

cd /d "%~dp0"
php create_admin_account.php

echo.
echo Nhan Enter de dong cua so...
pause
