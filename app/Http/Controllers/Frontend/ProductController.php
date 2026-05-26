<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Product;

class ProductController extends Controller
{
    public function show(string $slug)
    {
        $product = Product::with([

            'brand',

            'category.parent.parent',

            'galleries' => function ($query) {
                $query->orderBy('id');
            },

            'galleries.color',

            'variants' => function ($query) {
                $query->where('stock', '>', 0);
            },

            'variants.color',
            'variants.size',
        ])
            ->active()
            ->where('slug', $slug)
            ->firstOrFail();

        // =========================
        // CART SESSION
        // =========================
        $cart = session('cart', []);

        // =========================
        // CART QUANTITIES
        // =========================
        $cartQuantities = collect($cart)
            ->mapWithKeys(function ($item) {

                return [
                    $item['variant_id'] => $item['quantity']
                ];
            });

        // =========================
        // COLORS
        // =========================
        $colors = $product->variants
            ->pluck('color')
            ->filter()
            ->unique('id')
            ->values();

        // =========================
        // SIZES
        // =========================
        $sizes = $product->variants
            ->pluck('size')
            ->filter()
            ->unique('id')
            ->values();

        // =========================
        // VARIANTS
        // =========================
        $variantData = $product->variants
            ->map(function ($variant) use ($cartQuantities) {

                // quantity hiện có trong cart
                $cartQty = $cartQuantities[$variant->id] ?? 0;

                // stock còn lại
                $availableStock = max(
                    $variant->stock - $cartQty,
                    0
                );

                return [

                    'id' => $variant->id,

                    'color_id' => $variant->color_id,

                    'size_id' => $variant->size_id,

                    // stock DB
                    'stock' => $variant->stock,

                    // stock realtime
                    'available_stock' => $availableStock,

                    'price' => $variant->price,

                    'sale_price' => $variant->sale_price,

                    'color' => [
                        'id' => $variant->color?->id,
                        'name' => $variant->color?->name,
                        'code' => $variant->color?->code,
                    ],

                    'size' => [
                        'id' => $variant->size?->id,
                        'name' => $variant->size?->name,
                    ],
                ];
            })
            ->values();

        // =========================
        // GALLERY BY COLOR
        // =========================
        $galleryByColor = $product->galleries
            ->groupBy('color_id')
            ->map(function ($items) {

                return $items->map(function ($gallery) {

                    return [
                        'id' => $gallery->id,

                        'file' => asset(
                            'uploads/gallery/' . $gallery->file
                        ),
                    ];
                })->values();
            });

        // =========================
        // DEFAULT IMAGE
        // =========================
        $mainImage = $product->galleries->first()?->file
            ?? $product->image;

        // =========================
        // PRICE
        // =========================
        $minPrice = $product->variants->min('price');

        $maxPrice = $product->variants->max('price');

        $oldPrice = $product->variants->max('sale_price');

        // =========================
        // DISCOUNT
        // =========================
        $discountPercent = 0;

        if ($oldPrice && $minPrice) {

            $discountPercent = round(
                (($oldPrice - $minPrice) / $oldPrice) * 100
            );
        }

        // =========================
        // RELATED
        // =========================
        $relatedProducts = Product::with([
            'brand',
            'variants',
            'variants.color',
        ])
            ->active()
            ->where('id', '!=', $product->id)
            ->where('category_id', $product->category_id)
            ->latest()
            ->take(4)
            ->get();

        // =========================
        // VIEW
        // =========================
        return view('frontend.product.show', compact(
            'product',

            'colors',
            'sizes',

            'variantData',

            'galleryByColor',

            'mainImage',

            'minPrice',
            'maxPrice',

            'oldPrice',

            'discountPercent',

            'relatedProducts'
        ));
    }
}
