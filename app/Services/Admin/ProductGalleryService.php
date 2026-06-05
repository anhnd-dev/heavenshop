<?php

namespace App\Services\Admin;

use App\Models\Product;
use App\Models\ProductGallery;

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

        $query = $product->galleries()
            ->with('color')
            ->orderBy('sort_order')
            ->latest();

        if ($includeTrashed) {

            $query->onlyTrashed();
        }

        return $query->get();
    }

    /**
     * =========================
     * STORE
     * =========================
     */
    public function store(
        Product $product,
        array|string $files,
        ?int $colorId = null,
        int $sortOrder = 0
    ): void {

        $files = is_array($files)
            ? $files
            : [$files];

        foreach ($files as $file) {

            $mime = $file->getMimeType();

            $type = str_contains(
                $mime,
                'video'
            )
                ? 'video'
                : 'image';

            $fileName = $this->uploadFile(
                $file,
                'gallery'
            );

            ProductGallery::create([

                'product_id' => $product->id,

                'color_id' => $colorId,

                'file' => $fileName,

                'type' => $type,

                'thumbnail' => null,

                'sort_order' => $sortOrder,
            ]);
        }
    }

    /**
     * =========================
     * UPDATE
     * =========================
     */
    public function update(
        Product $product,
        int $id,
        array $data
    ): ProductGallery {

        $gallery = ProductGallery::query()
            ->where('product_id', $product->id)
            ->findOrFail($id);

        /**
         * =========================
         * UPDATE FILE
         * =========================
         */
        if (!empty($data['file'])) {

            if ($gallery->file) {

                $this->deleteFile(
                    $gallery->file,
                    'gallery'
                );
            }

            $uploadedFile = $data['file'];

            $mime = $uploadedFile->getMimeType();

            $data['type'] = str_contains(
                $mime,
                'video'
            )
                ? 'video'
                : 'image';

            $fileName = $this->uploadFile(
                $uploadedFile,
                'gallery'
            );

            $data['file'] = $fileName;
        }

        /**
         * =========================
         * UPDATE THUMBNAIL
         * =========================
         */
        if (!empty($data['thumbnail'])) {

            if ($gallery->thumbnail) {

                $this->deleteFile(
                    $gallery->thumbnail,
                    'gallery'
                );
            }

            $thumbnail = $this->uploadFile(
                $data['thumbnail'],
                'gallery'
            );

            $data['thumbnail'] = $thumbnail;
        }

        $gallery->update([

            'file' => $data['file']
                ?? $gallery->file,

            'type' => $data['type']
                ?? $gallery->type,

            'thumbnail' => $data['thumbnail']
                ?? $gallery->thumbnail,

            'color_id' => $data['color_id']
                ?? $gallery->color_id,

            'sort_order' => $data['sort_order']
                ?? $gallery->sort_order,
        ]);

        return $gallery->fresh([
            'color',
        ]);
    }

    /**
     * =========================
     * DELETE
     * =========================
     */
    public function delete(
        Product $product,
        int $id
    ): void {

        ProductGallery::query()
            ->where('product_id', $product->id)
            ->findOrFail($id)
            ->delete();
    }

    /**
     * =========================
     * DELETE ALL
     * =========================
     */
    public function deleteAll(
        Product $product,
        array $ids
    ): int {

        return ProductGallery::query()
            ->where('product_id', $product->id)
            ->whereIn('id', $ids)
            ->delete();
    }

    /**
     * =========================
     * RESTORE
     * =========================
     */
    public function restore(
        Product $product,
        int $id
    ): void {

        ProductGallery::onlyTrashed()
            ->where('product_id', $product->id)
            ->findOrFail($id)
            ->restore();
    }

    /**
     * =========================
     * RESTORE ALL
     * =========================
     */
    public function restoreAll(
        Product $product,
        array $ids
    ): int {

        return ProductGallery::onlyTrashed()
            ->where('product_id', $product->id)
            ->whereIn('id', $ids)
            ->restore();
    }

    /**
     * =========================
     * FORCE DELETE
     * =========================
     */
    public function forceDelete(
        Product $product,
        int $id
    ): void {

        $gallery = ProductGallery::withTrashed()
            ->where('product_id', $product->id)
            ->findOrFail($id);

        if ($gallery->file) {

            $this->deleteFile(
                $gallery->file,
                'gallery'
            );
        }

        if ($gallery->thumbnail) {

            $this->deleteFile(
                $gallery->thumbnail,
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
        Product $product,
        array $ids
    ): int {

        $galleries = ProductGallery::withTrashed()
            ->where('product_id', $product->id)
            ->whereIn('id', $ids)
            ->get();

        foreach ($galleries as $gallery) {

            if ($gallery->file) {

                $this->deleteFile(
                    $gallery->file,
                    'gallery'
                );
            }

            if ($gallery->thumbnail) {

                $this->deleteFile(
                    $gallery->thumbnail,
                    'gallery'
                );
            }
        }

        return ProductGallery::withTrashed()
            ->where('product_id', $product->id)
            ->whereIn('id', $ids)
            ->forceDelete();
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

            if ($gallery->file) {

                $this->deleteFile(
                    $gallery->file,
                    'gallery'
                );
            }

            if ($gallery->thumbnail) {

                $this->deleteFile(
                    $gallery->thumbnail,
                    'gallery'
                );
            }
        }

        ProductGallery::withTrashed()
            ->where('product_id', $product->id)
            ->forceDelete();
    }
}
