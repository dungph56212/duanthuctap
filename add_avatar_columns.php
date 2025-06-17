<?php

require_once __DIR__ . '/vendor/autoload.php';

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

try {
    echo "🔄 Checking if avatar column exists...\n";
    
    // Check if avatar column exists
    $hasAvatar = Schema::hasColumn('users', 'avatar');
    $hasRole = Schema::hasColumn('users', 'role');
    $hasAddress = Schema::hasColumn('users', 'address');
    
    if (!$hasAvatar || !$hasRole || !$hasAddress) {
        echo "➕ Adding missing columns to users table...\n";
        
        Schema::table('users', function ($table) use ($hasAvatar, $hasRole, $hasAddress) {
            if (!$hasAvatar) {
                $table->string('avatar')->nullable()->after('email_verified_at');
                echo "✅ Added avatar column\n";
            }
            if (!$hasRole) {
                $table->string('role')->default('customer')->after('avatar');
                echo "✅ Added role column\n";
            }
            if (!$hasAddress) {
                $table->text('address')->nullable()->after('role');
                echo "✅ Added address column\n";
            }
        });
        
        echo "✅ Successfully added missing columns to users table!\n";
    } else {
        echo "✅ All columns already exist in users table.\n";
    }
    
    // Update existing users to have default role if needed
    $usersWithoutRole = DB::table('users')->whereNull('role')->count();
    if ($usersWithoutRole > 0) {
        echo "🔄 Updating {$usersWithoutRole} users with default role...\n";
        DB::table('users')->whereNull('role')->update(['role' => 'customer']);
        echo "✅ Updated users with default role.\n";
    }
    
    echo "\n🎉 Database migration completed successfully!\n";
    echo "You can now use the avatar upload feature.\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Please check your database connection and try again.\n";
}
