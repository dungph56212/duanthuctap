# HƯỚNG DẪN TEST API ĐỊA CHỈ

## ✅ Đã thêm vào checkout:

### 1. Form fields:
- `#tinh` - Select tỉnh/thành phố
- `#quan` - Select quận/huyện  
- `#phuong` - Select phường/xã
- `#address` - Địa chỉ cụ thể

### 2. Hidden fields lưu tên:
- `#ten_tinh` - Tên tỉnh
- `#ten_quan` - Tên quận
- `#ten_phuong` - Tên phường

### 3. API endpoint:
- Tỉnh: `https://provinces.open-api.vn/api/p/`
- Quận: `https://provinces.open-api.vn/api/p/{provinceCode}?depth=2`
- Phường: `https://provinces.open-api.vn/api/d/{districtCode}?depth=2`

## 🧪 Cách test:

### 1. Kiểm tra API có hoạt động:
Mở Developer Tools > Console và chạy:
```javascript
$.getJSON('https://provinces.open-api.vn/api/p/', function(data) {
    console.log('API works:', data.length + ' provinces loaded');
});
```

### 2. Kiểm tra select box:
1. Vào trang checkout: `/checkout`
2. Mở Developer Tools > Elements
3. Tìm `<select id="tinh">` 
4. Xem có options không

### 3. Test flow đầy đủ:
1. Chọn tỉnh → Xem quận có load không
2. Chọn quận → Xem phường có load không  
3. Chọn phường → Kiểm tra hidden fields có giá trị

## 🐛 Troubleshooting:

### Nếu không load tỉnh:
- Kiểm tra internet connection
- Kiểm tra API còn hoạt động không
- Xem console có lỗi CORS không

### Nếu không load quận/phường:
- Kiểm tra province/district code
- Xem network tab có request không
- Kiểm tra response data structure

### Backup tĩnh (nếu API lỗi):
```javascript
// Thêm vào script nếu API không hoạt động
const provinces = [
    {code: "01", name: "Hà Nội"},
    {code: "79", name: "TP. Hồ Chí Minh"},
    {code: "48", name: "Đà Nẵng"},
    {code: "92", name: "Cần Thơ"}
];
provinces.forEach(function(province) {
    $("#tinh").append(`<option value="${province.code}">${province.name}</option>`);
});
```

## 💾 Dữ liệu được lưu:
- `city` = province code hoặc tên tỉnh
- `address` = địa chỉ đầy đủ: "Phường, Quận, Tỉnh, Địa chỉ cụ thể"
