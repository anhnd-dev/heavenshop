<?php

namespace App\Http\Controllers\Admin;

use App\Models\Brand;
use App\Models\Color;
use App\Models\Product;
use App\Models\Category;
use App\Models\Size;
use Illuminate\Http\Request;
use App\Services\Admin\ProductService;
use App\Services\Admin\ProductVariantService;

class ProductController extends BaseAdminController
{
    public function __construct(
        protected ProductService $productService,
        protected ProductVariantService $variantService
    ) {}

    // =========================
    // INDEX
    // =========================
    public function index(Request $request)
    {
        $categories = Category::with('childrenRecursive')
            ->where('type', 'product')
            ->whereNull('parent_id')
            ->get();

        $brands = Brand::all();

        $colors = Color::all();

        $sizes = Size::all();

        if ($request->ajax()) {

            return $this->productService
                ->getDataTable(
                    $request->boolean('include_trashed')
                );
        }

        return view(
            'admin.pages.product.index',
            compact(
                'categories',
                'brands',
                'colors',
                'sizes'
            )
        );
    }

    // =========================
    // STORE
    // =========================
    public function store(Request $request)
    {
        return $this->transaction(function () use ($request) {

            $data = $request->all();

            $product = $this->productService
                ->store($data);

            $this->variantService
                ->syncVariants(
                    $product,
                    $data['variants'] ?? []
                );

            return $this->successResponse(
                'Thêm sản phẩm thành công'
            );
        });
    }

    // =========================
    // EDIT
    // =========================
    public function edit(Request $request)
    {
        $product = Product::with([
            'category.parent',
            'brand',
            'variants.color',
            'variants.size',
            'galleries.color',
        ])->findOrFail($request->id);

        return response()->json($product);
    }

    // =========================
    // UPDATE
    // =========================
    public function update(Request $request)
    {
        return $this->transaction(function () use ($request) {

            $product = Product::with('variants')
                ->findOrFail($request->product_id);

            $data = $request->all();

            // =========================
            // UPDATE PRODUCT
            // =========================
            $this->productService
                ->update($product, $data);

            // =========================
            // REMOVE VARIANTS FIRST
            // =========================
            if (!empty($data['removed_variants'])) {

                $removedIds = json_decode(
                    $data['removed_variants'],
                    true
                );

                if (is_array($removedIds) && count($removedIds)) {

                    $variants = $product->variants()
                        ->whereIn('id', $removedIds)
                        ->get();

                    foreach ($variants as $variant) {

                        // delete image
                        if ($variant->image) {

                            $this->variantService
                                ->deleteVariantImage(
                                    $variant->image
                                );
                        }

                        $variant->delete();
                    }
                }
            }

            // =========================
            // UPDATE OLD VARIANTS
            // =========================
            $this->variantService
                ->syncVariants(
                    $product,
                    $data['edit_variants'] ?? []
                );

            // =========================
            // CREATE NEW VARIANTS
            // =========================
            $this->variantService
                ->syncVariants(
                    $product,
                    $data['new_variants'] ?? []
                );

            return $this->successResponse(
                'Cập nhật sản phẩm thành công'
            );
        });
    }

    // =========================
    // DELETE
    // =========================
    public function delete(Request $request)
    {
        return $this->transaction(function () use ($request) {

            $product = Product::findOrFail(
                $request->id
            );

            $this->productService
                ->delete($product);

            return $this->successResponse(
                'Xóa sản phẩm thành công'
            );
        });
    }

    // =========================
    // DELETE ALL
    // =========================
    public function deleteAll(Request $request)
    {
        return $this->transaction(function () use ($request) {

            $ids = $request->ids ?? [];

            $this->productService
                ->deleteMultiple($ids);

            return $this->successResponse(
                'Xóa nhiều sản phẩm thành công'
            );
        });
    }

    // =========================
    // RESTORE
    // =========================
    public function restore(Request $request)
    {
        $this->productService
            ->restore($request->id);

        return $this->successResponse(
            'Khôi phục sản phẩm thành công'
        );
    }

    // =========================
    // RESTORE ALL
    // =========================
    public function restoreAll(Request $request)
    {
        return $this->transaction(function () use ($request) {

            $ids = $request->ids ?? [];

            $this->productService
                ->restoreMultiple($ids);

            return $this->successResponse(
                'Khôi phục tất cả sản phẩm thành công'
            );
        });
    }

    // =========================
    // FORCE DELETE
    // =========================
    public function forceDelete(Request $request)
    {
        return $this->transaction(function () use ($request) {

            $product = Product::withTrashed()
                ->with([
                    'variants',
                    'galleries',
                ])
                ->findOrFail($request->id);

            $this->productService
                ->forceDelete($product);

            return $this->successResponse(
                'Xóa vĩnh viễn sản phẩm thành công'
            );
        });
    }

    // =========================
    // FORCE DELETE ALL
    // =========================
    public function forceDeleteAll(Request $request)
    {
        return $this->transaction(function () use ($request) {

            $ids = $request->ids ?? [];

            $this->productService
                ->forceDeleteMultiple($ids);

            return $this->successResponse(
                'Xóa vĩnh viễn nhiểu sản phẩm thành công'
            );
        });
    }
}
