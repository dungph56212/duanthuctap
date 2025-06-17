-- XÓA DỮ LIỆU GIỎ HÀNG CŨ
-- Chạy câu lệnh này trên phpMyAdmin

TRUNCATE TABLE carts;

-- Kiểm tra bảng đã trống chưa
SELECT COUNT(*) as total_carts FROM carts;

-- Kết quả phải là 0
