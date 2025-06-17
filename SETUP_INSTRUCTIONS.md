# HƯỚNG DẪN SETUP E-COMMERCE DATABASE

## Bước 1: Reset database và chạy migrations fresh
Chạy lệnh này để xóa tất cả bảng và tạo lại từ đầu:
```
php artisan migrate:fresh
```

## Bước 2: Chạy seeders để tạo dữ liệu mẫu
```
php artisan db:seed
```

## Hoặc chạy file PowerShell script (nếu máy hỗ trợ):
```
./reset_and_setup.ps1
```

## Thông tin đăng nhập Admin sau khi setup xong:
- **Email:** admin@example.com
- **Password:** password

## Dữ liệu sẽ được tạo:
- ✅ Danh mục sản phẩm (Categories) với danh mục con
- ✅ 220+ Sản phẩm mẫu (Products) 
- ✅ 50+ Người dùng (Users)
- ✅ Mã giảm giá (Coupons)
- ✅ Bảng đơn hàng, giỏ hàng, đánh giá, wishlist, địa chỉ

## Các bảng database đã tạo:
1. `users` - Người dùng (đã cập nhật thêm các trường cho e-commerce)
2. `categories` - Danh mục sản phẩm 
3. `products` - Sản phẩm
4. `orders` - Đơn hàng
5. `order_items` - Chi tiết đơn hàng
6. `carts` - Giỏ hàng
7. `coupons` - Mã giảm giá
8. `reviews` - Đánh giá sản phẩm
9. `wishlists` - Danh sách yêu thích
10. `addresses` - Địa chỉ giao hàng/thanh toán

## Lưu ý:
- Đã sửa lỗi unique constraint trên JSON column trong bảng carts
- Tất cả Models đã có relationships đầy đủ
- Factories và Seeders đã sẵn sàng tạo dữ liệu mẫu
