-- Script SQL để thêm cột is_active vào bảng users
-- Chạy script này trong phpMyAdmin hoặc MySQL Workbench

-- 1. Thêm cột is_active vào bảng users
ALTER TABLE `users` ADD COLUMN `is_active` TINYINT(1) NOT NULL DEFAULT 1 AFTER `is_admin`;

-- 2. Cập nhật tất cả user hiện tại thành active (nếu cần)
UPDATE `users` SET `is_active` = 1 WHERE `is_active` IS NULL;

-- 3. Kiểm tra kết quả
SELECT id, name, email, is_admin, is_active FROM `users` LIMIT 10;
