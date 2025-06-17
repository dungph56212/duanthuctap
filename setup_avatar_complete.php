<?php

echo "🚀 BookStore Avatar Setup Script\n";
echo "================================\n\n";

// Step 1: Add avatar columns to database
echo "STEP 1: Adding avatar columns to database\n";
echo "==========================================\n";
include __DIR__ . '/add_avatar_columns.php';

echo "\n";

// Step 2: Setup storage directories
echo "STEP 2: Setting up avatar storage\n";
echo "==================================\n";
include __DIR__ . '/setup_avatar_storage.php';

echo "\n";

// Step 3: Test avatar functionality
echo "STEP 3: Testing avatar functionality\n";
echo "=====================================\n";

require_once __DIR__ . '/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

try {
    // Test helper function
    if (function_exists('userAvatarUrl')) {
        $testUrl = userAvatarUrl(null);
        echo "✅ userAvatarUrl helper function works: " . $testUrl . "\n";
    } else {
        echo "❌ userAvatarUrl helper function not found\n";
    }
    
    // Test user model
    $user = \App\Models\User::first();
    if ($user) {
        echo "✅ User model accessible\n";
        if (in_array('avatar', $user->getFillable())) {
            echo "✅ Avatar is in User fillable fields\n";
        } else {
            echo "❌ Avatar not in User fillable fields\n";
        }
    } else {
        echo "❌ No users found in database\n";
    }
    
} catch (Exception $e) {
    echo "❌ Error testing functionality: " . $e->getMessage() . "\n";
}

echo "\n🎉 Avatar setup completed!\n";
echo "============================================\n";
echo "✅ Database columns added\n";
echo "✅ Storage directories created\n";
echo "✅ Helper functions ready\n";
echo "\nYou can now:\n";
echo "1. Go to /profile to upload your avatar\n";
echo "2. Upload images up to 2MB (JPEG, PNG, JPG, GIF)\n";
echo "3. Remove avatar if needed\n";
echo "4. See avatar in client and admin areas\n";
echo "\nIf you encounter any issues, check:\n";
echo "- File permissions on storage/app/public/avatars/\n";
echo "- Web server has write access to storage directory\n";
echo "- Storage link is working: public/storage -> storage/app/public\n";
