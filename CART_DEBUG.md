# DEBUG GIỎ HÀNG - KIỂM TRA NHANH

## 🔍 KIỂM TRA DỮ LIỆU:

### 1. Kiểm tra bảng carts (phpMyAdmin):
```sql
SELECT * FROM carts;
```

### 2. Kiểm tra user hiện tại:
```sql
SELECT id, name, email FROM users WHERE email = 'user@example.com';
```

### 3. Kiểm tra products:
```sql
SELECT id, name, price, sale_price, stock FROM products WHERE is_active = 1 LIMIT 5;
```

## 🛠️ CÁCH TEST:

### Bước 1: Clear dữ liệu cũ
```sql
TRUNCATE TABLE carts;
```

### Bước 2: Đăng nhập
- Email: `user@example.com`
- Password: `password`

### Bước 3: Thêm sản phẩm
1. Vào trang sản phẩm
2. Click "Thêm vào giỏ hàng"
3. Kiểm tra notification thành công

### Bước 4: Xem giỏ hàng
1. Click vào icon giỏ hàng (header)
2. Hoặc truy cập: `/cart`

## 🚨 NẾU VẪN TRỐNG:

### Kiểm tra Console (F12):
1. Mở Developer Tools
2. Tab Console
3. Tìm lỗi JavaScript

### Kiểm tra Network:
1. Tab Network
2. Thử thêm sản phẩm
3. Xem request/response

### Kiểm tra Database:
```sql
-- Sau khi thêm sản phẩm, kiểm tra:
SELECT * FROM carts WHERE user_id = 1;
```

## ✅ ĐÃ SỬA:
- ✅ Tạo lại cart/index.blade.php hoàn chỉnh
- ✅ Sửa CartController để tính tổng đúng
- ✅ Thêm AJAX functions cho update/remove
- ✅ Responsive design

## 📞 SUPPORT:
Nếu vẫn không work, cung cấp:
1. Screenshot lỗi console
2. Kết quả query `SELECT * FROM carts;`
3. Response khi thêm sản phẩm
