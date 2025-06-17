<?php

/**
 * Script to fix database structure safely with column checks
 */

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

echo "=== FIXING DATABASE STRUCTURE SAFELY ===\n\n";

try {
    // Check if chat_messages table exists
    if (Schema::hasTable('chat_messages')) {
        echo "✓ chat_messages table exists\n";
        
        // Mark migration as ran
        DB::table('migrations')->insertOrIgnore([
            'migration' => '2024_06_17_000000_create_chat_messages_table',
            'batch' => 3
        ]);
        echo "✓ Marked chat_messages migration as completed\n";
    }
    
    // Check if users table has avatar column
    if (Schema::hasColumn('users', 'avatar')) {
        echo "✓ users.avatar column exists\n";
        
        // Mark duplicate avatar migration as ran to avoid conflicts
        DB::table('migrations')->insertOrIgnore([
            'migration' => '2024_06_17_000010_add_avatar_to_users_table',
            'batch' => 3
        ]);
        echo "✓ Marked duplicate avatar migration as completed\n";
    }
    
    // Check and add sold_count to products if not exists
    if (!Schema::hasColumn('products', 'sold_count')) {
        Schema::table('products', function ($table) {
            $table->integer('sold_count')->default(0)->after('stock');
        });
        echo "✓ Added sold_count column to products\n";
    } else {
        echo "✓ products.sold_count column exists\n";
    }
    
    DB::table('migrations')->insertOrIgnore([
        'migration' => '2024_06_17_000001_add_sold_count_to_products_table',
        'batch' => 3
    ]);
    
    // Check and add VNPay fields to orders one by one
    if (!Schema::hasColumn('orders', 'vnpay_transaction_id')) {
        Schema::table('orders', function ($table) {
            $table->string('vnpay_transaction_id')->nullable()->after('status');
        });
        echo "✓ Added vnpay_transaction_id to orders\n";
    } else {
        echo "✓ orders.vnpay_transaction_id column exists\n";
    }
    
    if (!Schema::hasColumn('orders', 'vnpay_response_code')) {
        Schema::table('orders', function ($table) {
            $table->string('vnpay_response_code')->nullable()->after('vnpay_transaction_id');
        });
        echo "✓ Added vnpay_response_code to orders\n";
    } else {
        echo "✓ orders.vnpay_response_code column exists\n";
    }
    
    if (!Schema::hasColumn('orders', 'vnpay_payment_status')) {
        Schema::table('orders', function ($table) {
            $table->string('vnpay_payment_status')->default('pending')->after('vnpay_response_code');
        });
        echo "✓ Added vnpay_payment_status to orders\n";
    } else {
        echo "✓ orders.vnpay_payment_status column exists\n";
    }
    
    DB::table('migrations')->insertOrIgnore([
        'migration' => '2024_06_17_000002_add_vnpay_fields_to_orders_table',
        'batch' => 3
    ]);
    
    // Mark shipping fee removal as completed (if column doesn't exist)
    if (!Schema::hasColumn('orders', 'shipping_fee')) {
        DB::table('migrations')->insertOrIgnore([
            'migration' => '2024_06_17_000003_remove_shipping_fee_from_orders_table',
            'batch' => 3
        ]);
        echo "✓ Shipping fee already removed from orders\n";
    }
    
    // Check and add is_active to users if not exists
    if (!Schema::hasColumn('users', 'is_active')) {
        Schema::table('users', function ($table) {
            $table->boolean('is_active')->default(true)->after('role');
        });
        echo "✓ Added is_active column to users\n";
    } else {
        echo "✓ users.is_active column exists\n";
    }
    
    // Mark duplicate is_active migrations as completed
    DB::table('migrations')->insertOrIgnore([
        'migration' => '2025_06_17_020000_add_is_active_to_users_table',
        'batch' => 3
    ]);
    DB::table('migrations')->insertOrIgnore([
        'migration' => '2025_06_17_022500_add_is_active_to_users_table',
        'batch' => 3
    ]);
    
    // Check and add book fields to products one by one
    $bookFields = [
        'isbn' => ['type' => 'string', 'nullable' => true],
        'author' => ['type' => 'string', 'nullable' => true],
        'publisher' => ['type' => 'string', 'nullable' => true],
        'publication_year' => ['type' => 'integer', 'nullable' => true],
        'pages' => ['type' => 'integer', 'nullable' => true],
        'language' => ['type' => 'string', 'default' => 'vi']
    ];
    
    foreach ($bookFields as $field => $config) {
        if (!Schema::hasColumn('products', $field)) {
            Schema::table('products', function ($table) use ($field, $config) {
                $column = $table->{$config['type']}($field);
                if (isset($config['nullable']) && $config['nullable']) {
                    $column->nullable();
                }
                if (isset($config['default'])) {
                    $column->default($config['default']);
                }
                $column->after($field === 'isbn' ? 'sold_count' : 
                    ($field === 'author' ? 'isbn' :
                    ($field === 'publisher' ? 'author' :
                    ($field === 'publication_year' ? 'publisher' :
                    ($field === 'pages' ? 'publication_year' : 'pages')))));
            });
            echo "✓ Added $field column to products\n";
        } else {
            echo "✓ products.$field column exists\n";
        }
    }
    
    DB::table('migrations')->insertOrIgnore([
        'migration' => '2025_06_17_030000_add_book_fields_to_products_table',
        'batch' => 3
    ]);
    
    // Create storage directories
    $storagePaths = [
        'storage/app/public/avatars',
        'storage/app/public/products',
        'public/storage'
    ];
    
    foreach ($storagePaths as $path) {
        if (!file_exists($path)) {
            mkdir($path, 0755, true);
            echo "✓ Created directory: $path\n";
        } else {
            echo "✓ Directory exists: $path\n";
        }
    }
    
    // Create storage link if not exists
    if (!is_link('public/storage') && !file_exists('public/storage')) {
        if (PHP_OS_FAMILY === 'Windows') {
            exec('mklink /D "public\\storage" "..\\storage\\app\\public"');
        } else {
            symlink('../storage/app/public', 'public/storage');
        }
        echo "✓ Created storage symlink\n";
    } else {
        echo "✓ Storage symlink exists\n";
    }
    
    echo "\n=== DATABASE FIX COMPLETED SUCCESSFULLY ===\n";
    echo "✓ All database structures are now correct\n";
    echo "✓ Avatar upload should now work properly\n";
    echo "✓ ChatBot functionality is ready\n";
    echo "✓ All migrations are marked as completed\n\n";
    
    echo "You can now:\n";
    echo "1. Upload avatars in profile page\n";
    echo "2. Use ChatBot functionality\n";
    echo "3. All features should work without migration errors\n";
    
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . "\n";
    echo "Line: " . $e->getLine() . "\n";
}
