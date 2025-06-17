<?php

require_once 'vendor/autoload.php';

use Illuminate\Database\Capsule\Manager as DB;

// Cấu hình database
$capsule = new DB;
$capsule->addConnection([
    'driver' => 'mysql',
    'host' => '127.0.0.1',
    'database' => 'project',  // Từ .env
    'username' => 'root',
    'password' => '',
    'charset' => 'utf8mb4',
    'collation' => 'utf8mb4_unicode_ci',
]);
$capsule->setAsGlobal();
$capsule->bootEloquent();

echo "🚀 Setting up BookStore Database...\n\n";

try {
    // 1. Tạo bảng chat_messages
    echo "1. Creating chat_messages table...\n";
    
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
    
    // 2. Thêm trường sold_count vào products
    echo "2. Adding sold_count to products...\n";
    
    $hasColumn = DB::schema()->hasColumn('products', 'sold_count');
    if (!$hasColumn) {
        DB::schema()->table('products', function ($table) {
            $table->integer('sold_count')->default(0)->after('stock');
        });
        echo "   ✅ Added sold_count column\n";
    } else {
        echo "   ✅ sold_count column already exists\n";
    }
    
    // 3. Kiểm tra và tạo dữ liệu mẫu
    echo "3. Checking sample data...\n";
    
    $productCount = DB::table('products')->count();
    $categoryCount = DB::table('categories')->count();
    $userCount = DB::table('users')->count();
    
    echo "   📊 Current data:\n";
    echo "   - Products: {$productCount}\n";
    echo "   - Categories: {$categoryCount}\n";
    echo "   - Users: {$userCount}\n";
    
    // 4. Tạo categories nếu chưa có
    if ($categoryCount == 0) {
        echo "4. Creating sample categories...\n";
        
        $categories = [
            ['name' => 'Tiểu thuyết', 'slug' => 'tieu-thuyet', 'description' => 'Sách tiểu thuyết hay nhất', 'is_active' => 1],
            ['name' => 'Kỹ năng sống', 'slug' => 'ky-nang-song', 'description' => 'Sách phát triển bản thân', 'is_active' => 1],
            ['name' => 'Kinh tế', 'slug' => 'kinh-te', 'description' => 'Sách về kinh tế và tài chính', 'is_active' => 1],
            ['name' => 'Văn học', 'slug' => 'van-hoc', 'description' => 'Tác phẩm văn học kinh điển', 'is_active' => 1],
            ['name' => 'Thiếu nhi', 'slug' => 'thieu-nhi', 'description' => 'Sách dành cho trẻ em', 'is_active' => 1],
            ['name' => 'Khoa học', 'slug' => 'khoa-hoc', 'description' => 'Sách khoa học và công nghệ', 'is_active' => 1],
            ['name' => 'Lịch sử', 'slug' => 'lich-su', 'description' => 'Sách lịch sử thế giới', 'is_active' => 1],
            ['name' => 'Tâm lý học', 'slug' => 'tam-ly-hoc', 'description' => 'Sách tâm lý và hành vi', 'is_active' => 1],
        ];
        
        foreach ($categories as $category) {
            $category['created_at'] = now();
            $category['updated_at'] = now();
            DB::table('categories')->insert($category);
        }
        
        echo "   ✅ Created 8 sample categories\n";
        $categoryCount = 8;
    }
    
    // 5. Tạo products nếu chưa có
    if ($productCount == 0) {
        echo "5. Creating sample products...\n";
        
        $books = [
            // Tiểu thuyết
            ['name' => 'Tôi Thấy Hoa Vàng Trên Cỏ Xanh', 'author' => 'Nguyễn Nhật Ánh', 'category_id' => 1, 'price' => 89000],
            ['name' => 'Mắt Biếc', 'author' => 'Nguyễn Nhật Ánh', 'category_id' => 1, 'price' => 79000],
            ['name' => 'Dế Mèn Phiêu Lưu Ký', 'author' => 'Tô Hoài', 'category_id' => 1, 'price' => 65000],
            ['name' => 'Số Đỏ', 'author' => 'Vũ Trọng Phụng', 'category_id' => 1, 'price' => 95000],
            
            // Kỹ năng sống
            ['name' => 'Đắc Nhân Tâm', 'author' => 'Dale Carnegie', 'category_id' => 2, 'price' => 120000],
            ['name' => 'Nghĩ Giàu Làm Giàu', 'author' => 'Napoleon Hill', 'category_id' => 2, 'price' => 110000],
            ['name' => '7 Thói Quen Hiệu Quả', 'author' => 'Stephen Covey', 'category_id' => 2, 'price' => 150000],
            ['name' => 'Quẳng Gánh Lo Đi Và Vui Sống', 'author' => 'Dale Carnegie', 'category_id' => 2, 'price' => 98000],
            
            // Kinh tế
            ['name' => 'Kinh Tế Học Vi Mô', 'author' => 'N. Gregory Mankiw', 'category_id' => 3, 'price' => 180000],
            ['name' => 'Cha Giàu Cha Nghèo', 'author' => 'Robert Kiyosaki', 'category_id' => 3, 'price' => 135000],
            ['name' => 'Nhà Đầu Tư Thông Minh', 'author' => 'Benjamin Graham', 'category_id' => 3, 'price' => 210000],
            
            // Văn học
            ['name' => 'Truyện Kiều', 'author' => 'Nguyễn Du', 'category_id' => 4, 'price' => 75000],
            ['name' => 'Chiến Tranh Và Hòa Bình', 'author' => 'Leo Tolstoy', 'category_id' => 4, 'price' => 250000],
            ['name' => 'Nhà Giả Kim', 'author' => 'Paulo Coelho', 'category_id' => 4, 'price' => 89000],
            
            // Thiếu nhi
            ['name' => 'Doraemon Tập 1', 'author' => 'Fujiko F. Fujio', 'category_id' => 5, 'price' => 25000],
            ['name' => 'Thám Tử Conan Tập 1', 'author' => 'Aoyama Gosho', 'category_id' => 5, 'price' => 30000],
            ['name' => 'Truyện Cổ Tích Việt Nam', 'author' => 'Nhiều tác giả', 'category_id' => 5, 'price' => 45000],
            
            // Khoa học
            ['name' => 'Vật Lý Đại Cương', 'author' => 'David Halliday', 'category_id' => 6, 'price' => 320000],
            ['name' => 'Hóa Học Hữu Cơ', 'author' => 'Paula Bruice', 'category_id' => 6, 'price' => 280000],
            
            // Lịch sử
            ['name' => 'Lịch Sử Việt Nam', 'author' => 'Trần Trọng Kim', 'category_id' => 7, 'price' => 160000],
            ['name' => 'Lịch Sử Thế Giới', 'author' => 'H.G. Wells', 'category_id' => 7, 'price' => 190000],
            
            // Tâm lý học
            ['name' => 'Tâm Lý Học Đại Cương', 'author' => 'David G. Myers', 'category_id' => 8, 'price' => 220000],
            ['name' => 'Hiểu Về Trái Tim', 'author' => 'Kahlil Gibran', 'category_id' => 8, 'price' => 85000],
        ];
        
        foreach ($books as $book) {
            $product = [
                'name' => $book['name'],
                'slug' => \Illuminate\Support\Str::slug($book['name']),
                'description' => 'Mô tả chi tiết về cuốn sách ' . $book['name'] . '. Đây là một tác phẩm hay và ý nghĩa.',
                'short_description' => 'Cuốn sách hay về ' . $book['name'],
                'sku' => 'BOOK' . str_pad(rand(1, 99999), 5, '0', STR_PAD_LEFT),
                'price' => $book['price'],
                'sale_price' => rand(0, 1) ? $book['price'] * 0.9 : null, // 50% chance có sale
                'stock' => rand(10, 100),
                'sold_count' => rand(0, 200),
                'manage_stock' => 1,
                'in_stock' => 1,
                'is_active' => 1,
                'is_featured' => rand(0, 1),
                'weight' => rand(200, 800),
                'images' => json_encode(['https://via.placeholder.com/400x600/4A90E2/FFFFFF?text=' . urlencode($book['name'])]),
                'category_id' => $book['category_id'],
                'author' => $book['author'],
                'created_at' => now(),
                'updated_at' => now(),
            ];
            
            DB::table('products')->insert($product);
        }
        
        echo "   ✅ Created " . count($books) . " sample products\n";
        $productCount = count($books);
    }
    
    // 6. Tạo user admin nếu chưa có
    if ($userCount == 0) {
        echo "6. Creating admin user...\n";
        
        $adminUser = [
            'name' => 'Admin User',
            'email' => 'admin@bookstore.com',
            'email_verified_at' => now(),
            'password' => password_hash('admin123', PASSWORD_BCRYPT),
            'is_admin' => 1,
            'is_active' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ];
        
        DB::table('users')->insert($adminUser);
        echo "   ✅ Created admin user (admin@bookstore.com / admin123)\n";
        $userCount = 1;
    }
    
    // 7. Cập nhật sold_count cho products
    echo "7. Updating sold_count for products...\n";
    
    $products = DB::table('products')->get();
    $updated = 0;
    
    foreach ($products as $product) {
        if ($product->sold_count == 0) {
            $soldCount = rand(0, 300);
            
            DB::table('products')
                ->where('id', $product->id)
                ->update(['sold_count' => $soldCount]);
            
            $updated++;
        }
    }
    
    echo "   ✅ Updated sold_count for {$updated} products\n";
    
    // 8. Thống kê cuối cùng
    echo "\n📊 Final Statistics:\n";
    
    $finalProductCount = DB::table('products')->count();
    $finalCategoryCount = DB::table('categories')->count();
    $finalUserCount = DB::table('users')->count();
    $totalSold = DB::table('products')->sum('sold_count');
    $activeProducts = DB::table('products')->where('is_active', 1)->count();
    
    echo "   📚 Total products: {$finalProductCount}\n";
    echo "   ✅ Active products: {$activeProducts}\n";
    echo "   📂 Categories: {$finalCategoryCount}\n";
    echo "   👥 Users: {$finalUserCount}\n";
    echo "   🔥 Total books sold: {$totalSold}\n";
    
    echo "\n🎉 Setup completed successfully!\n";
    echo "\n💡 You can now:\n";
    echo "   - Browse products at: http://127.0.0.1:8000/products\n";
    echo "   - Use ChatBot at client pages\n";
    echo "   - Login admin at: http://127.0.0.1:8000/admin/login\n";
    echo "   - Test search and category pages\n";
    echo "\n🔑 Admin Login:\n";
    echo "   Email: admin@bookstore.com\n";
    echo "   Password: admin123\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}

echo "\n✅ Done!\n";
?>
