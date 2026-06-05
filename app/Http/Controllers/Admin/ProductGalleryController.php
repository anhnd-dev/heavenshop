<?php

namespace App\Http\Controllers\Admin;

use App\Models\Color;
use App\Models\Product;

use Illuminate\Http\Request;

use App\Services\Admin\ProductGalleryService;

use App\Http\Requests\Admin\ProductGallery\StoreProductGalleryRequest;
use App\Http\Requests\Admin\ProductGallery\UpdateProductGalleryRequest;

use App\Models\ProductGallery;

class ProductGalleryController extends BaseAdminController
{
    public function __construct(
        protected ProductGalleryService $galleryService
    ) {}

    /**
     * =========================
     * INDEX
     * =========================
     */
    public function index(
        Request $request,
        Product $product
    ) {
        $includeTrashed = $request->boolean(
            'include_trashed'
        );

        $galleries = $this->galleryService
            ->getByProduct(
                $product,
                $includeTrashed
            );

        $colors = Color::query()
            ->orderBy('name')
            ->get();

        return view(
            'admin.pages.gallery.index',
            compact(
                'product',
                'galleries',
                'colors',
                'includeTrashed'
            )
        );
    }

    public function edit(Request $request)
    {
        $gallery = ProductGallery::findOrFail($request->id);

        return response()->json($gallery);
    }

    /**
     * =========================
     * STORE
     * =========================
     */
    public function store(
        StoreProductGalleryRequest $request,
        Product $product
    ) {
        return $this->transaction(function () use (
            $request,
            $product
        ) {

            $this->galleryService->store(

                product: $product,

                files: $request->file('files'),

                colorId: $request->color_id,

                sortOrder: $request->sort_order ?? 0
            );

            return $this->successResponse(
                'Thêm thư viện media thành công'
            );
        });
    }

    /**
     * =========================
     * UPDATE
     * =========================
     */
    public function update(
        UpdateProductGalleryRequest $request,
        Product $product,
        int $id
    ) {
        return $this->transaction(function () use (
            $request,
            $product,
            $id
        ) {

            $gallery = $this->galleryService->update(

                product: $product,

                id: $id,

                data: [

                    'file' => $request->file('file'),

                    'thumbnail' => $request->file('thumbnail'),

                    'color_id' => $request->color_id,

                    'sort_order' => $request->sort_order,
                ]
            );

            return $this->successResponse(
                'Cập nhật thư viện media thành công',
                [
                    'gallery' => $gallery,
                ]
            );
        });
    }

    /**
     * =========================
     * DELETE
     * =========================
     */
    public function delete(
        Request $request,
        Product $product
    ) {
        $this->galleryService->delete(
            product: $product,
            id: $request->id
        );

        return $this->successResponse(
            'Đã chuyển media vào thùng rác'
        );
    }

    /**
     * =========================
     * DELETE ALL
     * =========================
     */
    public function deleteAll(
        Request $request,
        Product $product
    ) {
        $count = $this->galleryService
            ->deleteAll(
                product: $product,
                ids: $request->ids ?? []
            );

        return $this->successResponse(
            "{$count} media đã chuyển vào thùng rác"
        );
    }

    /**
     * =========================
     * RESTORE
     * =========================
     */
    public function restore(
        Request $request,
        Product $product
    ) {
        $this->galleryService->restore(
            product: $product,
            id: $request->id
        );

        return $this->successResponse(
            'Khôi phục media thành công'
        );
    }

    /**
     * =========================
     * RESTORE ALL
     * =========================
     */
    public function restoreAll(
        Request $request,
        Product $product
    ) {

        $count = $this->galleryService
            ->restoreAll(
                product: $product,
                ids: $request->ids ?? []
            );

        return $this->successResponse(
            "Khôi phục {$count} media thành công"
        );
    }

    /**
     * =========================
     * FORCE DELETE
     * =========================
     */
    public function forceDelete(
        Request $request,
        Product $product
    ) {

        $this->galleryService->forceDelete(
            product: $product,
            id: $request->id
        );

        return $this->successResponse(
            'Xóa media vĩnh viễn thành công'
        );
    }

    /**
     * =========================
     * FORCE DELETE ALL
     * =========================
     */
    public function forceDeleteAll(
        Request $request,
        Product $product
    ) {

        $count = $this->galleryService
            ->forceDeleteAll(
                product: $product,
                ids: $request->ids ?? []
            );

        return $this->successResponse(
            "{$count} media đã xóa vĩnh viễn"
        );
    }
}
