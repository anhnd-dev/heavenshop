<?php

namespace App\Services;

use App\Models\Product;
use App\Traits\ImageUploadTrait;
use App\Services\ProductGalleryService;

class ProductService
{
    use ImageUploadTrait;

    public function __construct(
        protected ProductGalleryService $galleryService
    ) {}

    // =========================
    // DATATABLE
    // =========================
    public function getDataTable(
        bool $includeTrashed = false
    ) {
        $query = Product::query()
            ->with([
                'category.parent',
                'brand',
                'variants.color',
                'variants.size',
            ])
            ->withMin('variants', 'price')
            ->withMax('variants', 'price');

        if ($includeTrashed) {

            $query->onlyTrashed();
        }

        return datatables()
            ->of($query)

            ->addIndexColumn()

            ->addColumn('category_name', function ($product) {

                $category = $product->category;

                if (!$category) {
                    return null;
                }

                if ($category->parent) {

                    return $category->parent->name
                        . ' > '
                        . $category->name;
                }

                return $category->name;
            })

            ->addColumn(
                'brand_name',
                fn($product)
                => $product->brand?->name
            )

            ->addColumn(
                'total_stock',
                fn($product)
                => $product->variants->sum('stock')
            )

            ->addColumn(
                'variant_count',
                fn($product)
                => $product->variants->count()
            )

            ->addColumn('price_range', function ($product) {

                $min = $product->variants_min_price;

                $max = $product->variants_max_price;

                if (!$min) {
                    return 0;
                }

                if ($min == $max) {

                    return number_format($min);
                }

                return number_format($min)
                    . ' - '
                    . number_format($max);
            })

            ->addColumn('action', function ($product) use ($includeTrashed) {

                $gallery = '
                    <button type="button"
                        class="galleryBtn btn btn-info shadow btn-xs sharp mr-1"
                        data-product_id="' . $product->id . '"
                        data-product_name="' . e($product->name) . '">

                        <i class="fas fa-images"></i>

                    </button>
                ';

                if ($includeTrashed) {

                    return '
                        <button type="button"
                            id="' . $product->id . '"
                            class="restoreIcon btn btn-success shadow btn-xs sharp mr-1">

                            <i class="fas fa-trash-restore"></i>

                        </button>

                        <button type="button"
                            id="' . $product->id . '"
                            class="forceIcon btn btn-danger shadow btn-xs sharp">

                            <i class="fas fa-trash-alt"></i>

                        </button>
                    ';
                }

                return $gallery . '

                    <button type="button"
                        id="' . $product->id . '"
                        class="editIcon btn btn-primary shadow btn-xs sharp mr-1">

                        <i class="fas fa-pencil-alt"></i>

                    </button>

                    <button type="button"
                        id="' . $product->id . '"
                        class="deleteIcon btn btn-danger shadow btn-xs sharp">

                        <i class="fa fa-trash"></i>

                    </button>
                ';
            })

            ->rawColumns([
                'action',
            ])

            ->make(true);
    }

    // =========================
    // STORE
    // =========================
    public function store(array $data): Product
    {
        if (!empty($data['image'])) {

            $data['image'] = $this->uploadImage(
                $data['image'],
                'product'
            );
        }

        if (isset($data['tags'])) {

            if (is_string($data['tags'])) {

                $data['tags'] = explode(
                    ',',
                    $data['tags']
                );
            }

            $data['tags'] = array_values(
                array_filter($data['tags'])
            );
        }

        $product = Product::create($data);

        // sync gallery
        if ($product->image) {

            $this->galleryService->store(
                $product,
                $product->image
            );
        }


        return $product;
    }

    // =========================
    // UPDATE
    // =========================
    public function update(
        Product $product,
        array $data
    ): Product {

        if (!empty($data['image'])) {

            $newImage = $this->uploadImage(
                $data['image'],
                'product'
            );

            // delete old image
            if ($product->image) {

                $this->deleteImage(
                    $product->image,
                    'product'
                );

                $this->galleryService->deleteByImage(
                    $product,
                    $product->image
                );
            }

            $data['image'] = $newImage;
        }

        // tags
        if (isset($data['tags'])) {

            if (is_string($data['tags'])) {

                $data['tags'] = explode(
                    ',',
                    $data['tags']
                );
            }

            $data['tags'] = array_values(
                array_filter($data['tags'])
            );
        }

        // update product
        $product->update($data);

        // sync gallery
        if (!empty($data['image'])) {

            $this->galleryService->store(
                $product,
                [$data['image']]
            );
        }

        return $product;
    }

    // =========================
    // DELETE
    // =========================
    public function delete(Product $product): bool
    {
        return $product->delete();
    }

    // =========================
    // DELETE MULTIPLE
    // =========================
    public function deleteMultiple(
        array $ids
    ): bool {

        Product::whereIn('id', $ids)
            ->delete();

        return true;
    }

    // =========================
    // RESTORE
    // =========================
    public function restore(int $id): bool
    {
        return Product::withTrashed()
            ->findOrFail($id)
            ->restore();
    }

    // =========================
    // RESTORE MULTIPLE
    // =========================
    public function restoreMultiple(
        array $ids
    ): bool {

        Product::onlyTrashed()
            ->whereIn('id', $ids)
            ->restore();

        return true;
    }

    // =========================
    // RESTORE ALL
    // =========================
    public function restoreAll(): bool
    {
        Product::onlyTrashed()
            ->restore();

        return true;
    }
    // =========================
    // FORCE DELETE
    // =========================
    public function forceDelete(Product $product): bool
    {
        $product->load([
            'variants',
            'galleries',
        ]);

        // product image
        if ($product->image) {

            $this->deleteImage(
                $product->image,
                'product'
            );

            $this->galleryService->deleteByProduct(
                $product
            );
        }

        // variant image
        foreach ($product->variants as $variant) {

            if ($variant->image) {

                $this->deleteImage(
                    $variant->image,
                    'variant'
                );
            }
        }

        return $product->forceDelete();
    }

    // =========================
    // FORCE DELETE MULTIPLE
    // =========================
    public function forceDeleteMultiple(
        array $ids
    ): bool {

        $products = Product::withTrashed()
            ->with([
                'variants',
                'galleries',
            ])
            ->whereIn('id', $ids)
            ->get();

        foreach ($products as $product) {

            $this->forceDelete($product);
        }

        return true;
    }

    // =========================
    // FORCE DELETE ALL
    // =========================
    public function forceDeleteAll(): bool
    {
        $products = Product::onlyTrashed()
            ->with([
                'variants',
                'galleries',
            ])
            ->get();


        foreach ($products as $product) {

            $this->forceDelete($product);
        }

        return true;
    }
}
