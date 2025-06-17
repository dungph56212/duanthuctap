<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        // Main categories
        $electronics = Category::create([
            'name' => 'Điện tử',
            'slug' => 'dien-tu',
            'description' => 'Các sản phẩm điện tử công nghệ',
            'is_active' => true,
            'sort_order' => 1,
        ]);

        $fashion = Category::create([
            'name' => 'Thời trang',
            'slug' => 'thoi-trang',
            'description' => 'Quần áo và phụ kiện thời trang',
            'is_active' => true,
            'sort_order' => 2,
        ]);

        $home = Category::create([
            'name' => 'Nhà cửa & Đời sống',
            'slug' => 'nha-cua-doi-song',
            'description' => 'Đồ dùng gia đình và trang trí',
            'is_active' => true,
            'sort_order' => 3,
        ]);

        $books = Category::create([
            'name' => 'Sách',
            'slug' => 'sach',
            'description' => 'Sách văn học, giáo dục, kỹ năng',
            'is_active' => true,
            'sort_order' => 4,
        ]);

        // Sub categories for Electronics
        Category::create([
            'name' => 'Điện thoại',
            'slug' => 'dien-thoai',
            'description' => 'Smartphone và phụ kiện',
            'parent_id' => $electronics->id,
            'is_active' => true,
            'sort_order' => 1,
        ]);

        Category::create([
            'name' => 'Laptop',
            'slug' => 'laptop',
            'description' => 'Máy tính xách tay',
            'parent_id' => $electronics->id,
            'is_active' => true,
            'sort_order' => 2,
        ]);

        Category::create([
            'name' => 'Tai nghe',
            'slug' => 'tai-nghe',
            'description' => 'Tai nghe và loa',
            'parent_id' => $electronics->id,
            'is_active' => true,
            'sort_order' => 3,
        ]);

        // Sub categories for Fashion
        Category::create([
            'name' => 'Quần áo nam',
            'slug' => 'quan-ao-nam',
            'description' => 'Thời trang dành cho nam',
            'parent_id' => $fashion->id,
            'is_active' => true,
            'sort_order' => 1,
        ]);

        Category::create([
            'name' => 'Quần áo nữ',
            'slug' => 'quan-ao-nu',
            'description' => 'Thời trang dành cho nữ',
            'parent_id' => $fashion->id,
            'is_active' => true,
            'sort_order' => 2,
        ]);

        Category::create([
            'name' => 'Giày dép',
            'slug' => 'giay-dep',
            'description' => 'Giày và dép các loại',
            'parent_id' => $fashion->id,
            'is_active' => true,
            'sort_order' => 3,
        ]);

        // Additional categories using factory
        Category::factory(10)->active()->create();
    }
}
