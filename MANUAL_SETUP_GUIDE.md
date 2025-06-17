# HƯỚNG DẪN SETUP THỦ CÔNG - Website Bán Hàng Laravel

## ✅ TÌNH TRẠNG HIỆN Tại
- ✅ Database structure đã hoàn thành
- ✅ Models, Controllers, Routes đã có đầy đủ  
- ✅ Views admin đã tạo xong
- ✅ Authentication system đã setup
- ✅ Admin middleware đã cấu hình
- ✅ .env đã cấu hình database

## 🚀 CÁC BƯỚC SETUP THỰC HIỆN

### BƯỚC 1: Khởi động Laragon và MySQL
1. Mở **Laragon**
2. Click **Start All** để khởi động Apache và MySQL
3. Đảm bảo MySQL đang chạy (đèn xanh)

### BƯỚC 2: Tạo Database
1. Click vào **Database** trong Laragon hoặc mở **phpMyAdmin**
2. Tạo database mới tên `project` (nếu chưa có)
3. Hoặc vào http://localhost/phpmyadmin
4. Tạo database `project` với collation `utf8mb4_unicode_ci`

### BƯỚC 3: Cài đặt Dependencies
Vì không chạy được CMD, hãy sử dụng **Terminal** trong VS Code:

1. Trong VS Code, mở **Terminal** (Ctrl + `)
2. Chạy từng lệnh một:
```bash
composer install
npm install
```

### BƯỚC 4: Chạy Migrations và Seeders
Trong Terminal VS Code:
```bash
php artisan migrate:fresh --seed
```

Hoặc chạy từng lệnh:
```bash
php artisan migrate:fresh
php artisan db:seed
```

### BƯỚC 5: Tạo Storage Link
```bash
php artisan storage:link
```

### BƯỚC 6: Kiểm tra và Sửa APP_KEY
File .env đã có APP_KEY hợp lệ. Nếu có lỗi, chạy:
```bash
php artisan key:generate
```

### BƯỚC 7: Khởi động Development Server
```bash
php artisan serve
```

## 🔐 THÔNG TIN ĐĂNG NHẬP

### Admin Account
- **URL**: http://localhost:8000/admin/login
- **Email**: tiendung08102005@gmail.com  
- **Password**: Dung08102005#

### Demo User Account
- **Email**: user@demo.com
- **Password**: password

## 📂 CẤU TRÚC HỆ THỐNG

### Database Tables
- `users` - Người dùng và admin
- `categories` - Danh mục sản phẩm  
- `products` - Sản phẩm
- `orders` - Đơn hàng
- `order_items` - Chi tiết đơn hàng
- `carts` - Giỏ hàng
- `coupons` - Mã giảm giá
- `reviews` - Đánh giá sản phẩm
- `wishlists` - Danh sách yêu thích
- `addresses` - Địa chỉ giao hàng

### Admin Features
- ✅ Dashboard thống kê
- ✅ Quản lý danh mục (CRUD)
- ✅ Quản lý sản phẩm (CRUD + Bulk actions)
- ✅ Quản lý đơn hàng (View + Update status)
- ✅ Quản lý người dùng 
- ✅ Quản lý mã giảm giá
- ✅ Upload ảnh sản phẩm
- ✅ Phân quyền admin

## 🔧 KHẮC PHỤC SỰ CỐ THƯỜNG GẶP

### Lỗi "Route [login] not defined"
➡️ **ĐÃ SỬA**: Đã thêm fallback route và cấu hình redirectGuestsTo

### Lỗi "View [admin.xxx] not found"  
➡️ **ĐÃ SỬA**: Tất cả view admin đã được tạo

### Lỗi "Route [admin.products.bulk-action] not defined"
➡️ **ĐÃ SỬA**: Route và method bulkAction đã có

### Lỗi Database Connection
1. Kiểm tra Laragon MySQL đang chạy
2. Kiểm tra database `project` đã tạo
3. Kiểm tra .env:
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=project
DB_USERNAME=root
DB_PASSWORD=
```

### Lỗi Migrations
Nếu có lỗi migration, reset database:
```bash
php artisan migrate:fresh --seed
```

### Lỗi Permissions (Storage)
Nếu không upload được ảnh:
```bash
php artisan storage:link
```

## 📱 TRUY CẬP HỆ THỐNG

### Client (Frontend)
- **URL**: http://localhost:8000
- Trang chủ với danh sách sản phẩm

### Admin Panel  
- **URL**: http://localhost:8000/admin
- **Login**: http://localhost:8000/admin/login
- **Dashboard**: http://localhost:8000/admin/dashboard

### Admin Menu
- Dashboard: `/admin`
- Categories: `/admin/categories`
- Products: `/admin/products`  
- Orders: `/admin/orders`
- Users: `/admin/users`
- Coupons: `/admin/coupons`

## 🎯 TÍNH NĂNG HOÀN THÀNH

### ✅ Authentication & Authorization
- Login/Logout admin
- Admin middleware protection
- User role management

### ✅ Category Management
- CRUD operations
- Active/Inactive status
- Parent-child relationships

### ✅ Product Management  
- CRUD operations
- Image upload (multiple)
- Category assignment
- Stock management
- SEO fields (slug, meta)
- Bulk actions (delete, activate, deactivate)

### ✅ Order Management
- View all orders
- Order details
- Update order status
- Update payment status

### ✅ User Management
- View users
- Admin role toggle
- User details

### ✅ Coupon Management
- CRUD operations
- Usage tracking
- Expiry dates
- Discount types

## 🎨 UI/UX Features
- Responsive admin dashboard
- Bootstrap-based interface
- Data tables with pagination
- Search and filters
- Bulk selection
- Status indicators
- Success/Error notifications

## 🔄 THAO TÁC TIẾP THEO

1. **Khởi động hệ thống** theo các bước trên
2. **Đăng nhập admin** và kiểm tra các tính năng
3. **Thêm dữ liệu mẫu** để test
4. **Tùy chỉnh giao diện** nếu cần

## 📞 HỖ TRỢ

Nếu gặp vấn đề:
1. Kiểm tra Laravel log: `storage/logs/laravel.log`
2. Kiểm tra browser console cho lỗi JS
3. Đảm bảo Laragon MySQL đang chạy
4. Kiểm tra file .env cấu hình đúng

---
**✨ HỆ THỐNG E-COMMERCE LARAVEL ĐÃ SẴN SÀNG!**
