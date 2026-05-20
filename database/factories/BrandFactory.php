<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Brand>
 */
class BrandFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $brand_name = $this->faker->unique()->word($nb = 2, $asText = true);
        $slug = Str::slug($brand_name);
        $imagePath = $this->faker->image(public_path('uploads/brand'), 400, 300, null, false);

        return [
            'name'  => Str::title($brand_name),
            'slug'  => $slug,
            'image' => $imagePath
        ];
    }
}
