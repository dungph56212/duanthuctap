<?php

/*
 * Script để check syntax lỗi trong Blade views
 * Chạy: php check_blade_syntax.php
 */

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== CHECKING BLADE SYNTAX ===\n\n";

// Các file view cần kiểm tra
$viewFiles = [
    'resources/views/client/home.blade.php',
    'resources/views/client/products/index.blade.php',
    'resources/views/client/products/show.blade.php',
    'resources/views/client/cart/index.blade.php',
    'resources/views/client/checkout.blade.php',
    'resources/views/client/orders/index.blade.php',
    'resources/views/client/orders/show.blade.php',
    'resources/views/client/order-success.blade.php'
];

foreach ($viewFiles as $file) {
    echo "Checking: $file\n";
    
    if (!file_exists($file)) {
        echo "❌ File not found: $file\n\n";
        continue;
    }
    
    $content = file_get_contents($file);
    
    // Kiểm tra các lỗi syntax thông thường
    $errors = [];
    
    // Đếm @if/@endif
    $ifCount = preg_match_all('/@if\s*\(/', $content);
    $endifCount = preg_match_all('/@endif/', $content);
    if ($ifCount !== $endifCount) {
        $errors[] = "Mismatch @if ($ifCount) and @endif ($endifCount)";
    }
    
    // Đếm @foreach/@endforeach
    $foreachCount = preg_match_all('/@foreach\s*\(/', $content);
    $endforeachCount = preg_match_all('/@endforeach/', $content);
    if ($foreachCount !== $endforeachCount) {
        $errors[] = "Mismatch @foreach ($foreachCount) and @endforeach ($endforeachCount)";
    }
    
    // Đếm @for/@endfor
    $forCount = preg_match_all('/@for\s*\(/', $content);
    $endforCount = preg_match_all('/@endfor/', $content);
    if ($forCount !== $endforCount) {
        $errors[] = "Mismatch @for ($forCount) and @endfor ($endforCount)";
    }
    
    // Kiểm tra double @endif
    if (preg_match('/@endif\s*@endif/', $content)) {
        $errors[] = "Found double @endif";
    }
    
    // Kiểm tra double @endforeach
    if (preg_match('/@endforeach\s*@endforeach/', $content)) {
        $errors[] = "Found double @endforeach";
    }
    
    if (empty($errors)) {
        echo "✅ Syntax OK\n\n";
    } else {
        echo "❌ Errors found:\n";
        foreach ($errors as $error) {
            echo "   - $error\n";
        }
        echo "\n";
    }
}

echo "=== CHECK COMPLETED ===\n";
