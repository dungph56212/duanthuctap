<?php

/*
 * Script để cập nhật ảnh sản phẩm trong database
 * Chạy: php update_product_images.php
 */

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Product;

echo "=== UPDATING PRODUCT IMAGES ===\n\n";

// Lấy tất cả sản phẩm
$products = Product::all();

echo "Found {$products->count()} products to update...\n\n";

$bookTitles = [
    'Đắc Nhân Tâm', 'Nhà Giả Kim', 'Tôi Thấy Hoa Vàng Trên Cỏ Xanh',
    'Mắt Biếc', 'Dế Mèn Phiêu Lưu Ký', 'Totto-chan Bên Cửa Sổ',
    'Sherlock Holmes', 'Harry Potter', 'Doraemon',
    'Conan Thám Tử Lừng Danh', 'One Piece', 'Dragon Ball',
    'Kinh Tế Học Vĩ Mô', 'Marketing Căn Bản', 'Quản Trị Doanh Nghiệp',
    'Lập Trình PHP', 'JavaScript Nâng Cao', 'Python Cơ Bản'
];

$colors = ['4CAF50', '2196F3', 'FF9800', '9C27B0', 'F44336', '00BCD4', 'CDDC39', 'FF5722'];

foreach ($products as $index => $product) {
    $color = $colors[$index % count($colors)];
    $title = $bookTitles[$index % count($bookTitles)];
    
    // Tạo ảnh placeholder đẹp cho từng sản phẩm
    $images = [
        "https://via.placeholder.com/600x800/{$color}/FFFFFF?text=" . urlencode($title),
        "https://via.placeholder.com/600x800/" . dechex(rand(0x000000, 0xFFFFFF)) . "/FFFFFF?text=Cover+2"
    ];
    
    $gallery = [
        "https://via.placeholder.com/600x800/{$color}/FFFFFF?text=Gallery+1",
        "https://via.placeholder.com/600x800/" . dechex(rand(0x000000, 0xFFFFFF)) . "/FFFFFF?text=Gallery+2",
        "https://via.placeholder.com/600x800/" . dechex(rand(0x000000, 0xFFFFFF)) . "/FFFFFF?text=Gallery+3"
    ];
    
    $product->update([
        'images' => $images,
        'gallery' => $gallery,
        'name' => $title . ' - ' . $product->name,
        'author' => 'Tác giả ' . ($index + 1)
    ]);
    
    echo "Updated product {$product->id}: {$title}\n";
}

echo "\n=== COMPLETED ===\n";
echo "All products now have beautiful placeholder images!\n";
echo "Check your website to see the images.\n\n";
