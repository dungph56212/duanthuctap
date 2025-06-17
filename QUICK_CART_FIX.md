# SỬA LỖI GIỎ HÀNG - NHANH CHÓNG

## 🚨 Lỗi:
```
Field 'price' doesn't have a default value
```

## ⚡ CÁCH SỬA NGAY:

### Bước 1: Xóa dữ liệu giỏ hàng cũ
1. Mở **phpMyAdmin**
2. Chọn database dự án
3. Click vào bảng **carts**
4. Click **Operations** tab
5. Click **Empty the table (TRUNCATE)**
6. Confirm **OK**

### Bước 2: Test lại
1. Đăng nhập: `user@example.com` / `password`
2. Thêm sản phẩm vào giỏ hàng
3. Kiểm tra thành công

## 📋 ĐÃ SỬA:
- ✅ CartController đã được cập nhật với trường `price`
- ✅ Code sử dụng `sale_price` nếu có, không thì dùng `price`

## 🔧 NẾU VẪN LỖI:

### Cách 1: Thủ công SQL
```sql
TRUNCATE TABLE carts;
```

### Cách 2: Kiểm tra User ID
```sql
SELECT id, name, email FROM users WHERE email = 'user@example.com';
```

### Cách 3: Tạo user mới nếu cần
```sql
INSERT INTO users (name, email, password, is_admin, is_active, created_at, updated_at) 
VALUES ('Test User', 'test@example.com', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 0, 1, NOW(), NOW());
```

## ⚠️ LƯU Ý:
Lỗi này chỉ xảy ra với dữ liệu cũ. Sau khi xóa và code đã sửa, sẽ không bị lại.

---
**Thời gian sửa**: < 2 phút
