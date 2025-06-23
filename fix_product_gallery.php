<?php

/*
 * Script để fix dữ liệu sản phẩm có gallery bị lỗi
 * Chạy: php fix_product_gallery.php
 */

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Product;

echo "=== FIXING PRODUCT GALLERY DATA ===\n\n";

// Lấy tất cả sản phẩm có gallery bị lỗi
$products = Product::whereNotNull('gallery')->get();

echo "Found {$products->count()} products to check...\n\n";

$fixedCount = 0;

foreach ($products as $product) {
    $needsFix = false;
    $gallery = $product->gallery ?? [];
    
    // Kiểm tra nếu gallery chứa array rỗng hoặc invalid data
    $cleanGallery = [];
    foreach ($gallery as $item) {
        if (is_string($item) && !empty($item)) {
            $cleanGallery[] = $item;
        } else {
            $needsFix = true;
        }
    }
    
    if ($needsFix) {
        $product->update(['gallery' => $cleanGallery]);
        $fixedCount++;
        echo "Fixed product {$product->id}: {$product->name}\n";
    }
}

echo "\n=== COMPLETED ===\n";
echo "Fixed {$fixedCount} products with invalid gallery data.\n\n";
