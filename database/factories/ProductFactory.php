<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        $name = $this->faker->words(3, true);
        $price = $this->faker->randomFloat(2, 10, 1000);
        $salePrice = $this->faker->boolean(30) ? $this->faker->randomFloat(2, 5, $price - 1) : null;
        
        return [
            'name' => ucwords($name),
            'slug' => Str::slug($name . '-' . $this->faker->randomNumber(4)),
            'description' => $this->faker->paragraphs(3, true),
            'short_description' => $this->faker->sentence(15),
            'sku' => 'SKU-' . strtoupper($this->faker->bothify('??##??##')),
            'price' => $price,
            'sale_price' => $salePrice,
            'stock' => $this->faker->numberBetween(0, 100),
            'manage_stock' => $this->faker->boolean(80),
            'in_stock' => $this->faker->boolean(85),
            'is_active' => $this->faker->boolean(90),
            'is_featured' => $this->faker->boolean(20),
            'weight' => $this->faker->randomFloat(2, 0.1, 10),
            'dimensions' => [
                'length' => $this->faker->randomFloat(2, 1, 50),
                'width' => $this->faker->randomFloat(2, 1, 50),
                'height' => $this->faker->randomFloat(2, 1, 50),
            ],            'images' => [
                'https://via.placeholder.com/600x800/4CAF50/FFFFFF?text=Book+1',
                'https://via.placeholder.com/600x800/2196F3/FFFFFF?text=Book+2',
            ],
            'gallery' => [
                'https://via.placeholder.com/600x800/FF9800/FFFFFF?text=Gallery+1',
                'https://via.placeholder.com/600x800/9C27B0/FFFFFF?text=Gallery+2',
                'https://via.placeholder.com/600x800/F44336/FFFFFF?text=Gallery+3',
            ],
            'category_id' => Category::factory(),
            'attributes' => [
                'color' => $this->faker->colorName(),
                'size' => $this->faker->randomElement(['S', 'M', 'L', 'XL']),
                'material' => $this->faker->randomElement(['Cotton', 'Polyester', 'Wool', 'Silk']),
            ],
            'sort_order' => $this->faker->numberBetween(0, 100),
        ];
    }

    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => true,
            'in_stock' => true,
        ]);
    }

    public function featured(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_featured' => true,
            'is_active' => true,
        ]);
    }

    public function onSale(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'sale_price' => $this->faker->randomFloat(2, 5, $attributes['price'] - 1),
            ];
        });
    }
}
