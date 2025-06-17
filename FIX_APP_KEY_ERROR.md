# HƯỚNG DẪN SỬA LỖI LARAVEL APP_KEY

## 🚨 LỖI: Unsupported cipher or incorrect key length

### ✅ CÁCH SỬA (Chọn 1 trong các cách sau):

## CÁCH 1: Chạy lệnh (Nếu terminal hoạt động)
```bash
php artisan key:generate
php artisan config:clear
php artisan cache:clear
```

## CÁCH 2: Thủ công (Nếu không chạy được lệnh)
Mở file `.env` và thay đổi dòng:
```
APP_KEY=base64:SGVsbG9Xb3JsZFNlY3JldEtleUZvckxhcmF2ZWxBcHA=
```

## CÁCH 3: Chạy script PowerShell
```powershell
./complete_setup.ps1
```

## 📋 CÁC BƯỚC SETUP HOÀN CHỈNH:

### 1. Fix APP_KEY
- ✅ Đã được sửa trong file .env

### 2. Tạo database
```sql
CREATE DATABASE project CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### 3. Chạy migrations
```bash
php artisan migrate:fresh --seed
```

### 4. Tạo storage link
```bash
php artisan storage:link
```

### 5. Clear cache
```bash
php artisan config:clear
php artisan cache:clear
```

## 🎯 SAU KHI SỬA:

### Truy cập website:
- **Trang chủ:** http://localhost:8000
- **Admin:** http://localhost:8000/admin/login

### Đăng nhập admin:
- **Email:** admin@example.com
- **Password:** password

## 🔧 NẾU VẪN LỖI:

1. Kiểm tra file `.env` có APP_KEY không
2. Xóa cache: `php artisan config:clear`
3. Khởi động lại server: `php artisan serve`

## ✅ APP_KEY ĐÃ ĐƯỢC THIẾT LẬP:
```
APP_KEY=base64:SGVsbG9Xb3JsZFNlY3JldEtleUZvckxhcmF2ZWxBcHA=
```

**🎉 SẴN SÀNG SỬ DỤNG!**
