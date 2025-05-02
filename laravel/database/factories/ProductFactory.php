<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Product;
use App\Models\Category;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->word,
            'category_id' => Category::factory(),

            'pricing' => $this->faker->randomFloat(2, 1, 100),
            // 'description' => $this->faker->sentence,
            // Add more fields if needed
        ];
    }
}
