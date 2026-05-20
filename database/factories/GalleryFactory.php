<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\File;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Gallery>
 */
class GalleryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $product = \App\Models\Product::inRandomOrder()->first();

        $uploadPath = 'uploads/';
        $productImagePath = $uploadPath . 'product/';
        $galleryImagePath = $uploadPath . 'gallery/';

        if ($product->image) {
            $image_url = $product->image;

            if (!File::exists(public_path($galleryImagePath . $image_url))) {
                File::copy(public_path($productImagePath . $image_url), public_path($galleryImagePath . $image_url));
            }
        } else {
            $image_url = null;
        }

        return [
            'product_id' => $product->id,
            'image_url' => $image_url,
            'caption' => $this->faker->sentence(),
        ];
    }
}
