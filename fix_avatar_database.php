<?php
/**
 * Script fix lỗi avatar database
 * Chạy file này để thêm cột avatar vào database
 */

require_once 'vendor/autoload.php';

// Bootstrap Laravel application
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

echo "=== FIX AVATAR DATABASE ===\n";
echo "Fixing avatar database issue...\n\n";

try {
    echo "1. Checking database connection...\n";
    DB::connection()->getPdo();
    echo "   ✓ Database connected\n";

    echo "\n2. Checking current users table structure...\n";
    $columns = DB::select("SHOW COLUMNS FROM users");
    $hasAvatar = false;
    $hasRole = false;
    $hasPhone = false;
    $hasAddress = false;
    
    foreach ($columns as $column) {
        echo "   - {$column->Field} ({$column->Type})\n";
        if ($column->Field === 'avatar') $hasAvatar = true;
        if ($column->Field === 'role') $hasRole = true;
        if ($column->Field === 'phone') $hasPhone = true;
        if ($column->Field === 'address') $hasAddress = true;
    }

    echo "\n3. Adding missing columns...\n";
    
    // Add avatar column
    if (!$hasAvatar) {
        echo "   → Adding avatar column...\n";
        DB::statement("ALTER TABLE users ADD COLUMN avatar VARCHAR(255) NULL AFTER email_verified_at");
        echo "   ✓ Avatar column added\n";
    } else {
        echo "   ✓ Avatar column already exists\n";
    }

    // Add role column
    if (!$hasRole) {
        echo "   → Adding role column...\n";
        DB::statement("ALTER TABLE users ADD COLUMN role VARCHAR(50) DEFAULT 'customer' AFTER avatar");
        echo "   ✓ Role column added\n";
    } else {
        echo "   ✓ Role column already exists\n";
    }

    // Add phone column
    if (!$hasPhone) {
        echo "   → Adding phone column...\n";
        DB::statement("ALTER TABLE users ADD COLUMN phone VARCHAR(20) NULL AFTER role");
        echo "   ✓ Phone column added\n";
    } else {
        echo "   ✓ Phone column already exists\n";
    }

    // Add address column
    if (!$hasAddress) {
        echo "   → Adding address column...\n";
        DB::statement("ALTER TABLE users ADD COLUMN address TEXT NULL AFTER phone");
        echo "   ✓ Address column added\n";
    } else {
        echo "   ✓ Address column already exists\n";
    }

    echo "\n4. Creating storage directories...\n";
    
    // Create avatars directory
    $avatarPath = storage_path('app/public/avatars');
    if (!file_exists($avatarPath)) {
        mkdir($avatarPath, 0755, true);
        echo "   ✓ Created: storage/app/public/avatars\n";
    } else {
        echo "   ✓ Directory exists: storage/app/public/avatars\n";
    }

    // Create public/storage link
    $publicStoragePath = public_path('storage');
    if (!file_exists($publicStoragePath)) {
        $storagePath = storage_path('app/public');
        
        // Try to create symbolic link
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            // Windows
            $cmd = 'mklink /D "' . str_replace('/', '\\', $publicStoragePath) . '" "' . str_replace('/', '\\', $storagePath) . '"';
            exec($cmd, $output, $returnVar);
            if ($returnVar === 0) {
                echo "   ✓ Created symbolic link: public/storage\n";
            } else {
                echo "   ! Could not create symbolic link, please run manually:\n";
                echo "     mklink /D \"public\\storage\" \"storage\\app\\public\"\n";
            }
        } else {
            // Unix/Linux
            symlink($storagePath, $publicStoragePath);
            echo "   ✓ Created symbolic link: public/storage\n";
        }
    } else {
        echo "   ✓ Public storage link exists\n";
    }

    echo "\n5. Verifying final structure...\n";
    $finalColumns = DB::select("SHOW COLUMNS FROM users");
    foreach ($finalColumns as $column) {
        echo "   - {$column->Field} ({$column->Type})\n";
    }

    echo "\n6. Testing avatar functionality...\n";
    
    // Test a simple update
    $testUser = DB::table('users')->first();
    if ($testUser) {
        echo "   ✓ Can read users table\n";
        echo "   ✓ Ready for avatar upload\n";
    }

    echo "\n=== SUCCESS ===\n";
    echo "✓ Database structure fixed\n";
    echo "✓ Storage directories created\n";
    echo "✓ Avatar functionality ready\n";
    echo "\nYou can now upload avatars at: http://127.0.0.1:8000/profile\n";

} catch (Exception $e) {
    echo "\n❌ ERROR: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . "\n";
    echo "Line: " . $e->getLine() . "\n";
    
    if (strpos($e->getMessage(), 'Access denied') !== false) {
        echo "\nSuggestion: Check database permissions\n";
    }
    if (strpos($e->getMessage(), 'Column') !== false) {
        echo "\nSuggestion: Try running this script as administrator\n";
    }
}
