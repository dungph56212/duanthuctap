# HƯỚNG DẪN SỬA LỖI ĐĂNG NHẬP CLIENT

## Lỗi gặp phải:
```
The POST method is not supported for route client-login. Supported methods: GET, HEAD.
```

## Nguyên nhân:
- Form đăng nhập POST tới route `client.login` nhưng route này chỉ hỗ trợ GET
- Thiếu route POST cho `/client-login`

## Đã sửa:

### 1. Cập nhật Routes (routes/web.php)
```php
// Client Auth Routes
Route::get('/login', [ClientAuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [ClientAuthController::class, 'login'])->name('login.post');
Route::get('/client-login', [ClientAuthController::class, 'showLoginForm'])->name('client.login');
Route::get('/register', [ClientAuthController::class, 'showRegisterForm'])->name('client.register');
Route::post('/register', [ClientAuthController::class, 'register']);
Route::post('/logout', [ClientAuthController::class, 'logout'])->name('client.logout');
```

### 2. Cập nhật Form Login (resources/views/client/auth/login.blade.php)
```html
<form method="POST" action="{{ route('login') }}" id="login-form">
```

## Cách test:

### 1. Đăng nhập User bình thường:
- URL: `/login` hoặc `/client-login`
- Email: `user@example.com`
- Password: `password`
- Sau khi đăng nhập sẽ redirect về trang chủ

### 2. Đăng nhập Admin:
- URL: `/login` 
- Email: `admin@bookstore.com`
- Password: `password`
- Sau khi đăng nhập sẽ redirect về `/admin`

### 3. Đăng ký tài khoản mới:
- URL: `/register`
- Điền đầy đủ thông tin
- Tài khoản mới sẽ là user bình thường (không phải admin)

## Phân quyền:

### User bình thường (is_admin = 0):
- Mua sắm trên website
- Xem/quản lý đơn hàng cá nhân
- Cập nhật profile

### Admin (is_admin = 1):
- Truy cập admin panel
- Quản lý toàn bộ hệ thống

## Troubleshooting:

### Nếu vẫn gặp lỗi route:
1. Clear cache: 
   - Trên phpMyAdmin chạy: `DELETE FROM cache WHERE 1;` (nếu có bảng cache)
   - Hoặc xóa file cache thủ công

### Nếu không đăng nhập được:
1. Kiểm tra email/password
2. Kiểm tra user có `is_active = 1` không
3. Kiểm tra bảng users có dữ liệu không

### Kiểm tra dữ liệu user (trên phpMyAdmin):
```sql
SELECT id, name, email, is_admin, is_active FROM users;
```

## Tài khoản test mặc định:
- **Admin**: admin@bookstore.com / password
- **User**: user@example.com / password

## Lưu ý:
- Form login và register đã được style đẹp với Bootstrap 5
- Có validation đầy đủ cho cả frontend và backend
- Phân quyền tự động dựa trên `is_admin` field
