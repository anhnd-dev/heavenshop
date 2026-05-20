<?php

namespace App\Services;

use App\Models\Product;
use App\Models\ProductGallery;
use Illuminate\Support\Facades\DB;
use App\Traits\ImageUploadTrait;

class ProductGalleryService
{
    use ImageUploadTrait;

    /**
     * =========================
     * GET BY PRODUCT
     * =========================
     */
    public function getByProduct(
        Product $product,
        bool $includeTrashed = false
    ) {

        $query = $product->galleries();

        if ($includeTrashed) {

            $query->onlyTrashed();
        }

        return $query
            ->latest()
            ->get();
    }

    /**
     * =========================
     * STORE
     * =========================
     */
    public function store(
        Product $product,
        array $images
    ): void {

        foreach ($images as $image) {

            // upload file object
            if (is_object($image)) {

                $imageName = $this->uploadImage(
                    $image,
                    'gallery'
                );
            }

            // image string
            else {

                $imageName = $image;
            }

            ProductGallery::create([
                'product_id' => $product->id,
                'image' => $imageName,
            ]);
        }
    }

    /**
     * =========================
     * DELETE
     * =========================
     */
    public function delete(
        int $id
    ): void {

        ProductGallery::findOrFail($id)
            ->delete();
    }

    /**
     * =========================
     * DELETE ALL
     * =========================
     */
    public function deleteAll(
        array $ids
    ): int {

        return ProductGallery::query()
            ->whereIn('id', $ids)
            ->delete();
    }

    /**
     * =========================
     * RESTORE
     * =========================
     */
    public function restore(
        int $id
    ): void {

        ProductGallery::withTrashed()
            ->findOrFail($id)
            ->restore();
    }

    /**
     * =========================
     * RESTORE ALL
     * =========================
     */
    public function restoreAll(): void
    {

        ProductGallery::onlyTrashed()
            ->restore();
    }

    /**
     * =========================
     * FORCE DELETE
     * =========================
     */
    public function forceDelete(
        int $id
    ): void {

        $gallery = ProductGallery::withTrashed()
            ->findOrFail($id);

        if ($gallery->image) {

            $this->deleteImage(
                $gallery->image,
                'gallery'
            );
        }

        $gallery->forceDelete();
    }

    /**
     * =========================
     * FORCE DELETE ALL
     * =========================
     */
    public function forceDeleteAll(
        array $ids
    ): int {

        $galleries = ProductGallery::withTrashed()
            ->whereIn('id', $ids)
            ->get();

        foreach ($galleries as $gallery) {

            if ($gallery->image) {

                $this->deleteImage(
                    $gallery->image,
                    'gallery'
                );
            }
        }

        return ProductGallery::withTrashed()
            ->whereIn('id', $ids)
            ->forceDelete();
    }

    /**
     * =========================
     * DELETE BY IMAGE
     * =========================
     */
    public function deleteByImage(
        Product $product,
        string $image
    ): void {

        ProductGallery::query()
            ->where('product_id', $product->id)
            ->where('image', $image)
            ->delete();
    }

    /**
     * =========================
     * DELETE BY PRODUCT
     * =========================
     */
    public function deleteByProduct(
        Product $product
    ): void {

        $galleries = ProductGallery::withTrashed()
            ->where('product_id', $product->id)
            ->get();

        foreach ($galleries as $gallery) {

            if ($gallery->image) {

                $this->deleteImage(
                    $gallery->image,
                    'gallery'
                );
            }
        }

        ProductGallery::withTrashed()
            ->where('product_id', $product->id)
            ->forceDelete();
    }
}
