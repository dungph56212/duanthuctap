-- SQL Script để tạo bảng ChatBot
-- Chạy trong phpMyAdmin hoặc MySQL Workbench

-- 1. Tạo bảng chat_messages
CREATE TABLE IF NOT EXISTS `chat_messages` (
    `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
    `session_id` varchar(255) NOT NULL,
    `message` text NOT NULL,
    `sender` enum('user','bot') NOT NULL,
    `response` text DEFAULT NULL,
    `context` json DEFAULT NULL,
    `created_at` timestamp NULL DEFAULT NULL,
    `updated_at` timestamp NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `session_id` (`session_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 2. Thêm cột sold_count vào bảng products (nếu chưa có)
ALTER TABLE `products` 
ADD COLUMN `sold_count` int(11) NOT NULL DEFAULT '0' AFTER `stock`;

-- 3. Cập nhật sold_count ngẫu nhiên cho các sản phẩm hiện có
UPDATE `products` 
SET `sold_count` = FLOOR(RAND() * 300) 
WHERE `sold_count` = 0;
