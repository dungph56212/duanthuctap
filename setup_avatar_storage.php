<?php

echo "🔄 Setting up avatar storage directories...\n";

$directories = [
    'storage/app/public/avatars',
    'public/storage',
];

foreach ($directories as $dir) {
    $fullPath = __DIR__ . '/' . $dir;
    
    if (!is_dir($fullPath)) {
        if (mkdir($fullPath, 0755, true)) {
            echo "✅ Created directory: {$dir}\n";
        } else {
            echo "❌ Failed to create directory: {$dir}\n";
        }
    } else {
        echo "✅ Directory already exists: {$dir}\n";
    }
}

// Create symbolic link for storage
$target = __DIR__ . '/storage/app/public';
$link = __DIR__ . '/public/storage';

if (!is_link($link) && !is_dir($link)) {
    if (symlink($target, $link)) {
        echo "✅ Created storage symbolic link\n";
    } else {
        echo "❌ Failed to create storage symbolic link\n";
        echo "You may need to create it manually or copy files instead of symlinking\n";
    }
} else {
    echo "✅ Storage link already exists\n";
}

echo "\n🎉 Avatar storage setup completed!\n";
echo "Avatar files will be stored in: storage/app/public/avatars/\n";
echo "They will be accessible via: public/storage/avatars/\n";
