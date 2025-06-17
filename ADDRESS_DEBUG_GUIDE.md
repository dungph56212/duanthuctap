# DEBUG ĐỊA CHỈ CHECKOUT FORM

## 🔍 Cách debug khi form bị trắng:

### Bước 1: Mở Developer Tools
1. **Nhấn F12** trên trang checkout
2. **Chọn tab Console**
3. **Reload trang và xem log**

### Bước 2: Kiểm tra log messages
Bạn sẽ thấy các message:
```
Loading provinces...
Provinces loaded: 63
Province selected: [code] [name]
Loading districts for province: [code]
Districts loaded: [number]
```

### Bước 3: Kiểm tra lỗi
Nếu có lỗi, sẽ hiển thị:
```
Error loading provinces: [error details]
Error loading districts: [error details]
Error loading wards: [error details]
```

### Bước 4: Các nguyên nhân có thể:

#### 1. Lỗi Internet/CORS
- **Triệu chứng**: "CORS error" hoặc "Network error"
- **Giải pháp**: Kiểm tra kết nối internet

#### 2. jQuery chưa load
- **Triệu chứng**: "$ is not defined"
- **Giải pháp**: Kiểm tra jQuery đã load trong layout

#### 3. API không response
- **Triệu chứng**: "Failed to load resource"
- **Giải pháp**: Test API trực tiếp: https://provinces.open-api.vn/api/p/

#### 4. Select elements không tồn tại
- **Triệu chứng**: Không có log "Province selected"
- **Giải pháp**: Kiểm tra ID của select boxes

### Bước 5: Test thủ công API
Thử access trực tiếp:
- **Tỉnh**: https://provinces.open-api.vn/api/p/
- **Quận HCM**: https://provinces.open-api.vn/api/p/79?depth=2
- **Phường Q1**: https://provinces.open-api.vn/api/d/760?depth=2

### Bước 6: Fallback nếu API lỗi
Nếu API external bị lỗi, có thể thay bằng data cứng:
```javascript
// Backup data nếu API lỗi
const backupProvinces = [
    {code: "79", name: "TP. Hồ Chí Minh"},
    {code: "01", name: "Hà Nội"},
    {code: "48", name: "Đà Nẵng"}
];
```

## 🎯 **Hướng dẫn user:**
1. **Mở Console** (F12 > Console tab)
2. **Chọn tỉnh** và xem có log không
3. **Báo cáo lỗi** nếu có trong console
4. **Screenshot** console nếu cần hỗ trợ
