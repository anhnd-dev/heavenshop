<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $product_name = $this->faker->unique()->word($nb = 2, $asText = true);
        $slug = Str::slug($product_name);
        $image_name = $this->faker->image(public_path('uploads/product'), 400, 300, null, false);

        return [
            'name'           => Str::title($product_name),
            'slug'           => $slug,
            'short_desc'     => $this->faker->text(200),
            'desc'           => $this->faker->text(500),
            'regular_price'  => $this->faker->numberBetween(1, 22),
            'SKU'            => 'SMD' . $this->faker->numberBetween(100, 500),
            'stock_status'   => 'instock',
            'quantity'       => $this->faker->numberBetween(100, 200),
            'image'          => $image_name,
            'category_id'    => $this->faker->numberBetween(1, 3),
            'brand_id'       => $this->faker->numberBetween(1, 3)
        ];
    }
}
