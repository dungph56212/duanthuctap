<?php

require_once 'vendor/autoload.php';

use Illuminate\Database\Capsule\Manager as DB;

// Cấu hình database
$capsule = new DB;
$capsule->addConnection([
    'driver' => 'mysql',
    'host' => '127.0.0.1',
    'database' => 'project',  // Sử dụng tên database từ .env
    'username' => 'root',
    'password' => '',
    'charset' => 'utf8mb4',
    'collation' => 'utf8mb4_unicode_ci',
]);
$capsule->setAsGlobal();
$capsule->bootEloquent();

echo "🤖 Setting up ChatBot AI...\n\n";

try {
    // 1. Tạo bảng chat_messages nếu chưa có
    echo "1. Checking chat_messages table...\n";
    
    $hasTable = DB::schema()->hasTable('chat_messages');
    if (!$hasTable) {
        DB::schema()->create('chat_messages', function ($table) {
            $table->id();
            $table->string('session_id');
            $table->text('message');
            $table->enum('sender', ['user', 'bot']);
            $table->text('response')->nullable();
            $table->json('context')->nullable();
            $table->timestamps();
            
            $table->index('session_id');
        });
        echo "   ✅ Created chat_messages table\n";
    } else {
        echo "   ✅ chat_messages table already exists\n";
    }
    
    // 2. Kiểm tra và thêm trường sold_count nếu chưa có
    echo "2. Checking sold_count column...\n";
    
    $hasColumn = DB::schema()->hasColumn('products', 'sold_count');
    if (!$hasColumn) {
        DB::schema()->table('products', function ($table) {
            $table->integer('sold_count')->default(0)->after('stock');
        });
        echo "   ✅ Added sold_count column to products\n";
    } else {
        echo "   ✅ sold_count column already exists\n";
    }
    
    // 3. Cập nhật sold_count ngẫu nhiên cho sản phẩm
    echo "3. Updating sold_count for products...\n";
    
    $products = DB::table('products')->get();
    $updated = 0;
    
    foreach ($products as $product) {
        $soldCount = rand(0, 300);
        
        DB::table('products')
            ->where('id', $product->id)
            ->update(['sold_count' => $soldCount]);
        
        $updated++;
    }
    
    echo "   ✅ Updated sold_count for {$updated} products\n";
    
    // 4. Thống kê
    echo "\n📊 Statistics:\n";
    
    $totalProducts = DB::table('products')->count();
    $activeProducts = DB::table('products')->where('is_active', 1)->count();
    $totalCategories = DB::table('categories')->where('is_active', 1)->count();
    $totalSold = DB::table('products')->sum('sold_count');
    
    echo "   📚 Total products: {$totalProducts}\n";
    echo "   ✅ Active products: {$activeProducts}\n";
    echo "   📂 Categories: {$totalCategories}\n";
    echo "   🔥 Total books sold: {$totalSold}\n";
    
    echo "\n🎉 ChatBot AI setup completed successfully!\n";
    echo "\n💡 Features:\n";
    echo "   - Search books by name, author, category\n";
    echo "   - Product recommendations\n";
    echo "   - Order guidance\n";
    echo "   - Contact information\n";
    echo "   - Chat history storage\n";
    echo "\n📱 Access: Chat button will appear at bottom-right of client pages\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}

echo "\n✅ Done!\n";
?>
