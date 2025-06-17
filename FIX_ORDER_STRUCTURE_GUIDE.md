# Lỗi Order Database Structure - Hướng Dẫn Khắc Phục

## 🔴 Lỗi Gặp Phải

```
SQLSTATE[HY000]: General error: 1364 Field 'total_amount' doesn't have a default value
Cannot access offset of type string on string
```

## 🔍 Nguyên Nhân

### 1. **Lỗi Migration Structure**
- Migration `orders` table có trường `total_amount` nhưng OrderController dùng `total`
- Migration `order_items` table có trường `product_name`, `product_sku`, `total_price` nhưng Controller dùng `price`
- Các trường địa chỉ được lưu dạng JSON nhưng view cố truy cập như array

### 2. **Lỗi JSON Handling**
- `shipping_address` và `billing_address` được lưu dạng JSON
- Views cố truy cập `$order->shipping_address['name']` nhưng có thể là string
- Laravel Cast không hoạt động đúng với dữ liệu cũ

## ✅ Các Bước Đã Sửa

### 1. **Cập Nhật OrderController**

**File:** `app/Http/Controllers/Client/OrderController.php`

**Thay đổi:**
```php
// CŨ (sai)
'total' => $total,
'coupon_discount' => $discount,
'shipping_fee' => $shipping,

// MỚI (đúng)
'total_amount' => $total,
'discount_amount' => $discount,
'shipping_amount' => $shipping,
'billing_address' => json_encode([...]),
'shipping_address' => json_encode([...]),
```

### 2. **Cập Nhật OrderItem Creation**

**Thay đổi:**
```php
// CŨ (sai)
OrderItem::create([
    'order_id' => $order->id,
    'product_id' => $item->product->id,
    'quantity' => $item->quantity,
    'price' => $item->product->price
]);

// MỚI (đúng)
OrderItem::create([
    'order_id' => $order->id,
    'product_id' => $item->product->id,
    'product_name' => $item->product->name,
    'product_sku' => $item->product->sku ?? 'N/A',
    'product_price' => $item->product->price,
    'quantity' => $item->quantity,
    'total_price' => $item->product->price * $item->quantity,
    'product_attributes' => json_encode([])
]);
```

### 3. **Sửa Views - Safe JSON Access**

**File:** `resources/views/client/order-success.blade.php`

**Thay đổi:**
```php
// CŨ (lỗi)
{{ $order->shipping_address['name'] }}

// MỚI (an toàn)
@php
    $shippingAddr = is_array($order->shipping_address) ? $order->shipping_address : json_decode($order->shipping_address, true);
@endphp
{{ $shippingAddr['name'] ?? 'N/A' }}
```

### 4. **Cập Nhật Field Names**

**Các trường đã đổi tên:**
- `total` → `total_amount`
- `shipping_fee` → `shipping_amount`
- `coupon_discount` → `discount_amount`
- `shipping_name/email/phone` → lưu trong `shipping_address` JSON
- `price` (order_items) → `product_price` + `total_price`

## 🛠 Script Khắc Phục

### Chạy Script PowerShell:
```powershell
.\fix_orders_structure.ps1
```

### Hoặc Chạy Thủ Công:
```sql
-- Xóa bảng cũ
DROP TABLE IF EXISTS order_items;
DROP TABLE IF EXISTS orders;

-- Tạo lại với cấu trúc đúng
-- (xem file fix_orders_structure.sql)
```

### Clear Cache Laravel:
```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

## 📋 Kiểm Tra Sau Khi Sửa

### 1. **Test Checkout Process**
- Truy cập: `http://localhost:8000/checkout`
- Thêm sản phẩm vào giỏ hàng
- Thực hiện checkout với địa chỉ đầy đủ
- Kiểm tra trang order success

### 2. **Kiểm Tra Database**
```sql
-- Kiểm tra cấu trúc orders
DESCRIBE orders;

-- Kiểm tra cấu trúc order_items  
DESCRIBE order_items;

-- Kiểm tra dữ liệu mẫu
SELECT * FROM orders LIMIT 1;
SELECT * FROM order_items LIMIT 1;
```

### 3. **Kiểm Tra Views**
- Order success page: `/order-success/{id}`
- Order list: `/orders`
- Order detail: `/orders/{id}`

## 🎯 Kết Quả Mong Đợi

Sau khi sửa, hệ thống sẽ:
- ✅ Checkout thành công không lỗi `total_amount`
- ✅ Hiển thị thông tin địa chỉ đúng dạng JSON
- ✅ Order items hiển thị đúng tên sản phẩm, giá
- ✅ Tính toán tổng tiền chính xác
- ✅ Không còn lỗi "Cannot access offset"

## 📝 Files Đã Thay Đổi

1. `app/Http/Controllers/Client/OrderController.php`
2. `resources/views/client/order-success.blade.php`
3. `resources/views/client/orders/index.blade.php`
4. `resources/views/client/orders/show.blade.php`
5. `fix_orders_structure.ps1` (script khắc phục)
6. `fix_orders_structure.sql` (SQL commands)

## ⚠️ Lưu Ý

- Script sẽ **XÓA TOÀN BỘ** dữ liệu orders hiện có
- Backup sẽ được tạo tự động trước khi xóa
- Chỉ chạy script này trên môi trường development
- Kiểm tra kỹ trước khi deploy production
