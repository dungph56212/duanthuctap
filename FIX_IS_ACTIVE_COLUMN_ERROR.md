# HƯỚNG DẪN SỬA LỖI CỘT IS_ACTIVE - THAO TÁC THỦ CÔNG

## 🚨 LỖI HIỆN TẠI
```
Column not found: 1054 Unknown column 'is_active' in 'field list'
```

## 📋 NGUYÊN NHÂN
- Cột `is_active` chưa tồn tại trong bảng `users`
- Cần thêm cột này vào database

## 🔧 CÁCH SỬA (THAO TÁC THỦ CÔNG)

### Phương án 1: Sử dụng phpMyAdmin (Khuyến nghị)

1. **Mở phpMyAdmin:**
   - Truy cập: `http://localhost/phpmyadmin`
   - Hoặc click vào phpMyAdmin trong Laragon

2. **Chọn database:**
   - Click vào database `laravel_ecommerce` (hoặc tên database bạn đang dùng)

3. **Chọn bảng users:**
   - Click vào bảng `users` trong danh sách bảng

4. **Thêm cột mới:**
   - Click tab "Structure" (Cấu trúc)
   - Click nút "Add" (Thêm) ở cuối danh sách cột
   - Hoặc click "Add 1 column after is_admin"

5. **Điền thông tin cột:**
   ```
   Column name: is_active
   Type: TINYINT
   Length: 1
   Default: 1
   Null: No (bỏ tích)
   ```

6. **Lưu thay đổi:**
   - Click "Save" (Lưu)

### Phương án 2: Chạy SQL trực tiếp

1. **Mở phpMyAdmin:**
   - Truy cập: `http://localhost/phpmyadmin`

2. **Chọn database:**
   - Click vào database của bạn

3. **Mở tab SQL:**
   - Click tab "SQL" ở trên cùng

4. **Paste và chạy code SQL:**
   ```sql
   ALTER TABLE `users` ADD COLUMN `is_active` TINYINT(1) NOT NULL DEFAULT 1 AFTER `is_admin`;
   ```

5. **Click "Go" (Thực hiện)**

### Phương án 3: Sử dụng MySQL Workbench

1. **Mở MySQL Workbench**
2. **Kết nối đến database**
3. **Chạy câu lệnh SQL:**
   ```sql
   USE laravel_ecommerce;
   ALTER TABLE `users` ADD COLUMN `is_active` TINYINT(1) NOT NULL DEFAULT 1 AFTER `is_admin`;
   ```

## ✅ KIỂM TRA KẾT QUẢ

Sau khi thêm cột, chạy câu lệnh SQL để kiểm tra:
```sql
DESCRIBE users;
```

Hoặc:
```sql
SELECT id, name, email, is_admin, is_active FROM users LIMIT 5;
```

Bạn sẽ thấy cột `is_active` đã xuất hiện.

## 🎯 SAU KHI SỬA XONG

1. **Refresh trang web**
2. **Test lại chức năng edit user**
3. **Chức năng kích hoạt/tắt tài khoản sẽ hoạt động**

## 📊 CẤU TRÚC CỘT IS_ACTIVE

```sql
Field: is_active
Type: tinyint(1)
Null: NO
Key: 
Default: 1
Extra: 
```

## 🚀 TÍNH NĂNG SAU KHI SỬA

- ✅ Kích hoạt/tắt tài khoản user
- ✅ Quản lý trạng thái tài khoản trong admin
- ✅ Bulk action cho nhiều user cùng lúc
- ✅ Hiển thị trạng thái trong danh sách user

## 📞 HỖ TRỢ

Nếu gặp khó khăn, hãy:
1. Chụp ảnh màn hình lỗi
2. Kiểm tra tên database có đúng không
3. Đảm bảo đã chọn đúng bảng `users`

---
**Lưu ý:** Sau khi sửa xong, tất cả chức năng quản lý user sẽ hoạt động bình thường!
