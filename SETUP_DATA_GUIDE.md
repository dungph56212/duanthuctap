# 📋 HƯỚNG DẪN SETUP DỮ LIỆU HOÀN CHỈNH

## 🚀 CHẠY SCRIPT SETUP

### Cách 1: Chạy script tự động
```bash
cd c:\laragon\www\project
php setup_complete_data.php
```

### Cách 2: Setup thủ công trong phpMyAdmin
1. Mở phpMyAdmin: http://localhost/phpmyadmin
2. Chọn database `project`
3. Import file: `chatbot_database.sql`

## 📊 DỮ LIỆU SẼ ĐƯỢC TẠO

### 1. Categories (8 danh mục):
- Tiểu thuyết
- Kỹ năng sống  
- Kinh tế
- Văn học
- Thiếu nhi
- Khoa học
- Lịch sử
- Tâm lý học

### 2. Products (23 sản phẩm):
- **Tiểu thuyết**: Tôi Thấy Hoa Vàng Trên Cỏ Xanh, Mắt Biếc, Dế Mèn Phiêu Lưu Ký...
- **Kỹ năng sống**: Đắc Nhân Tâm, Nghĩ Giàu Làm Giàu, 7 Thói Quen Hiệu Quả...
- **Kinh tế**: Cha Giàu Cha Nghèo, Nhà Đầu Tư Thông Minh...
- **Văn học**: Truyện Kiều, Chiến Tranh Và Hòa Bình, Nhà Giả Kim...
- **Thiếu nhi**: Doraemon, Thám Tử Conan, Truyện Cổ Tích...
- **Khoa học**: Vật Lý Đại Cương, Hóa Học Hữu Cơ...
- **Lịch sử**: Lịch Sử Việt Nam, Lịch Sử Thế Giới...
- **Tâm lý học**: Tâm Lý Học Đại Cương, Hiểu Về Trái Tim...

### 3. Admin User:
- **Email**: admin@bookstore.com
- **Password**: admin123
- **Role**: Administrator

### 4. Database Tables:
- ✅ chat_messages (cho ChatBot)
- ✅ products.sold_count (số lượng đã bán)
- ✅ Tất cả dữ liệu mẫu

## 🎯 SAU KHI SETUP XONG

### Có thể test:
1. **Trang chủ**: http://127.0.0.1:8000/
2. **Sản phẩm**: http://127.0.0.1:8000/products  
3. **Danh mục**: http://127.0.0.1:8000/category/1
4. **Tìm kiếm**: http://127.0.0.1:8000/search?q=tiểu+thuyết
5. **Admin**: http://127.0.0.1:8000/admin/login
6. **ChatBot**: Nút góc phải dưới trang client

### ChatBot sẽ hoạt động:
- Tìm kiếm sách theo tên, tác giả
- Hiển thị sách bán chạy (dựa trên sold_count)
- Hướng dẫn đặt hàng
- Thông tin liên hệ
- Lưu lịch sử chat

### Test ChatBot:
```
"Xin chào" → Chào mừng + menu
"Tìm sách tiểu thuyết" → Danh sách sách tiểu thuyết
"Sách bán chạy" → Top sách có sold_count cao
"Cách đặt hàng" → Hướng dẫn 7 bước
"Liên hệ" → Thông tin hotline
```

## 🐛 TROUBLESHOOTING

### Nếu script báo lỗi:
1. Kiểm tra database name trong .env phải là `project`
2. Đảm bảo MySQL đang chạy
3. Kiểm tra user `root` không có password

### Nếu ChatBot không hiển thị:
1. Clear cache browser (Ctrl+F5)
2. Kiểm tra Console (F12) có lỗi JS không
3. Đảm bảo đang ở trang client (không phải admin)

### Nếu không có sản phẩm:
1. Chạy lại script: `php setup_complete_data.php`
2. Hoặc check database có bảng `products` với dữ liệu không

## ✅ CHECKLIST HOÀN THÀNH

- [ ] Chạy `php setup_complete_data.php`
- [ ] Thấy thông báo "Setup completed successfully!"
- [ ] Truy cập http://127.0.0.1:8000/ thấy sản phẩm
- [ ] ChatBot hiển thị góc phải dưới
- [ ] Test login admin với admin@bookstore.com/admin123
- [ ] Test tìm kiếm và danh mục sản phẩm

🎉 **Khi tất cả đều hoạt động = SETUP THÀNH CÔNG!**
