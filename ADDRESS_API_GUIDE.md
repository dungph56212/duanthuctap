# HƯỚNG DẪN SỬ DỤNG API ĐỊA CHỈ VIỆT NAM

## 📍 Đã tích hợp API địa chỉ vào hệ thống

### ✅ Tính năng đã thêm:
- **API tỉnh/thành phố** từ provinces.open-api.vn
- **Tự động load quận/huyện** khi chọn tỉnh
- **Tự động load phường/xã** khi chọn quận
- **Validation đầy đủ** cho các trường địa chỉ

### 📝 Cách sử dụng:

#### 1. Trong form HTML:
```html
<div class="row">
    <div class="col-md-4 mb-3">
        <label for="tinh" class="form-label">Tỉnh/Thành phố <span class="text-danger">*</span></label>
        <select class="form-select" id="tinh" name="city" required>
            <option value="">Chọn Tỉnh/Thành phố</option>
        </select>
        <input type="hidden" id="ten_tinh" name="ten_tinh">
    </div>
    <div class="col-md-4 mb-3">
        <label for="quan" class="form-label">Quận/Huyện <span class="text-danger">*</span></label>
        <select class="form-select" id="quan" name="quan" required>
            <option value="">Chọn Quận/Huyện</option>
        </select>
        <input type="hidden" id="ten_quan" name="ten_quan">
    </div>
    <div class="col-md-4 mb-3">
        <label for="phuong" class="form-label">Phường/Xã <span class="text-danger">*</span></label>
        <select class="form-select" id="phuong" name="phuong" required>
            <option value="">Chọn Phường/Xã</option>
        </select>
        <input type="hidden" id="ten_phuong" name="ten_phuong">
    </div>
</div>
```

#### 2. Script tự động load (đã tích hợp vào layout):
- Script sẽ tự động chạy khi có elements với id: `tinh`, `quan`, `phuong`
- Không cần thêm script riêng trong từng trang

### 🎯 Đã áp dụng trong:
- ✅ **Checkout page** (`/checkout`)
- 🔄 **Profile page** (có thể thêm)
- 🔄 **Register page** (có thể thêm)

### 📡 API endpoints được sử dụng:
- **Tỉnh/TP**: https://provinces.open-api.vn/api/p/
- **Quận/Huyện**: https://provinces.open-api.vn/api/p/{province_code}?depth=2
- **Phường/Xã**: https://provinces.open-api.vn/api/d/{district_code}?depth=2

### 💾 Dữ liệu được lưu:
- **city**: Tên tỉnh/thành phố (text)
- **quan**: Code quận/huyện  
- **phuong**: Code phường/xã
- **Hidden fields**: Tên đầy đủ cho display

### 🔧 Cách mở rộng:

#### Thêm vào form khác:
1. Copy HTML structure với đúng id: `tinh`, `quan`, `phuong`
2. Script sẽ tự động hoạt động
3. Không cần config thêm

#### Customize validation:
```javascript
// Thêm validation tùy chỉnh
$("#tinh").on('change', function() {
    // Custom logic here
});
```

### ⚠️ Lưu ý:
- **API miễn phí** với rate limit hợp lý
- **Cần internet** để load địa chỉ
- **Fallback**: Có thể thêm static data backup nếu API lỗi

### 🎉 Kết quả:
Người dùng có thể chọn địa chỉ chính xác theo cấu trúc hành chính Việt Nam một cách dễ dàng và nhanh chóng.

---
**API nguồn**: [provinces.open-api.vn](https://provinces.open-api.vn)
