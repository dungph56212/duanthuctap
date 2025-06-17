# HƯỚNG DẪN SETUP AVATAR CHO USER

## Bước 1: Chạy script setup avatar
Mở trình duyệt và truy cập:
```
http://localhost/project/setup_avatar_complete.php
```

HOẶC mở terminal tại thư mục project và chạy:
```
php setup_avatar_complete.php
```

## Bước 2: Kiểm tra kết quả
Script sẽ:
- ✅ Thêm cột `avatar`, `role`, `address` vào bảng `users`
- ✅ Tạo thư mục `storage/app/public/avatars/`
- ✅ Tạo symbolic link `public/storage`
- ✅ Kiểm tra helper function `userAvatarUrl()`
- ✅ Cập nhật users với role mặc định

## Bước 3: Sử dụng tính năng avatar

### Trên Client:
1. Đăng nhập vào tài khoản
2. Vào trang Profile: `/profile`
3. Upload ảnh đại diện (tối đa 2MB)
4. Xem trước ảnh trước khi lưu
5. Có thể xóa ảnh nếu muốn

### Trên Admin:
- Xem avatar của users trong danh sách user
- Quản lý thông tin user bao gồm avatar

## Tính năng avatar bao gồm:

### 1. Upload ảnh
- Hỗ trợ: JPEG, PNG, JPG, GIF
- Kích thước tối đa: 2MB
- Tự động resize và tối ưu

### 2. Hiển thị ảnh
- Ưu tiên ảnh đã upload
- Fallback sang avatar mặc định từ UI Avatars
- Responsive trên mọi thiết bị

### 3. Quản lý ảnh
- Xóa ảnh cũ khi upload ảnh mới
- Xóa ảnh thủ công
- Preview ảnh trước khi lưu

## File đã được tạo/cập nhật:

### Database:
- `database/migrations/2024_06_17_000010_add_avatar_to_users_table.php`

### Models:
- `app/Models/User.php` (thêm fillable fields)

### Controllers:
- `app/Http/Controllers/Client/ProfileController.php`

### Views:
- `resources/views/client/auth/profile.blade.php`

### Helpers:
- `app/helpers.php` (thêm userAvatarUrl function)

### Routes:
- `routes/web.php` (thêm avatar routes)

### Scripts:
- `add_avatar_columns.php`
- `setup_avatar_storage.php`
- `setup_avatar_complete.php`

## Lỗi thường gặp và cách khắc phục:

### 1. "Column not found: avatar"
- Chạy lại script: `php setup_avatar_complete.php`
- Kiểm tra database connection

### 2. "Permission denied" khi upload
- Chmod 755 cho thư mục storage:
  ```
  chmod -R 755 storage/
  chmod -R 755 public/storage/
  ```

### 3. Không hiển thị ảnh
- Kiểm tra symbolic link: `public/storage -> storage/app/public`
- Kiểm tra file tồn tại trong `storage/app/public/avatars/`

### 4. Helper function không hoạt động
- Clear cache: xóa `bootstrap/cache/*.php`
- Kiểm tra `composer.json` autoload có `app/helpers.php`

## Kiểm tra hoạt động:

1. Truy cập `/profile` sau khi đăng nhập
2. Upload một ảnh bất kỳ
3. Kiểm tra ảnh hiển thị đúng
4. Kiểm tra file được lưu trong `storage/app/public/avatars/`

Nếu gặp vấn đề, hãy kiểm tra log Laravel trong `storage/logs/laravel.log`
