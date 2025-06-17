<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $categories = Category::all();

        // Create some featured products
        Product::factory(20)
            ->featured()
            ->active()
            ->create([
                'category_id' => $categories->random()->id,
            ]);

        // Create some products on sale
        Product::factory(30)
            ->onSale()
            ->active()
            ->create([
                'category_id' => $categories->random()->id,
            ]);

        // Create regular products
        Product::factory(150)
            ->active()
            ->create([
                'category_id' => $categories->random()->id,
            ]);

        // Create some inactive products
        Product::factory(20)->create([
            'category_id' => $categories->random()->id,
            'is_active' => false,
        ]);
    }
}
