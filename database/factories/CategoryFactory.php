<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Category>
 */
class CategoryFactory extends Factory
{
    protected $model = Category::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $category_name = $this->faker->unique()->word($nb = 2, $asText = true);
        $slug = Str::slug($category_name);
        $imagePath = $this->faker->image(public_path('uploads/category'), 400, 300, null, false);

        return [
            'name'  => Str::title($category_name),
            'slug'  => $slug,
            'image' => $imagePath
        ];
    }
}
