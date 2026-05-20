<?php

namespace App\Http\Controllers\Admin;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\ProductGalleryService;

class ProductGalleryController extends Controller
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

        return view(
            'admin.pages.gallery.index',
            compact(
                'product',
                'galleries',
                'includeTrashed'
            )
        )->render();
    }

    /**
     * =========================
     * STORE
     * =========================
     */
    public function store(
        Request $request,
        Product $product
    ) {

        $request->validate([
            'images' => ['required', 'array'],
            'images.*' => ['required', 'image'],
        ]);

        $this->galleryService->store(
            $product,
            $request->file('images')
        );

        return response()->json([
            'status' => 200,
            'message' => 'Thêm thư viện ảnh thành công',
        ]);
    }

    /**
     * =========================
     * DELETE
     * =========================
     */
    public function delete(
        Request $request
    ) {

        $this->galleryService->delete(
            $request->id
        );

        return response()->json([
            'status' => 200,
            'message' => 'Đã chuyển ảnh vào thùng rác',
        ]);
    }

    /**
     * =========================
     * DELETE ALL
     * =========================
     */
    public function deleteAll(
        Request $request
    ) {

        $count = $this->galleryService
            ->deleteAll(
                $request->ids ?? []
            );

        return response()->json([
            'status' => 200,
            'message' => "{$count} ảnh đã chuyển vào thùng rác",
        ]);
    }

    /**
     * =========================
     * RESTORE
     * =========================
     */
    public function restore(
        Request $request
    ) {

        $this->galleryService->restore(
            $request->id
        );

        return response()->json([
            'status' => 200,
            'message' => 'Khôi phục ảnh thành công',
        ]);
    }

    /**
     * =========================
     * RESTORE ALL
     * =========================
     */
    public function restoreAll()
    {

        $this->galleryService->restoreAll();

        return response()->json([
            'status' => 200,
            'message' => 'Khôi phục tất cả ảnh thành công',
        ]);
    }

    /**
     * =========================
     * FORCE DELETE
     * =========================
     */
    public function forceDelete(
        Request $request
    ) {

        $this->galleryService->forceDelete(
            $request->id
        );

        return response()->json([
            'status' => 200,
            'message' => 'Xóa ảnh vĩnh viễn thành công',
        ]);
    }

    /**
     * =========================
     * FORCE DELETE ALL
     * =========================
     */
    public function forceDeleteAll(
        Request $request
    ) {

        $count = $this->galleryService
            ->forceDeleteAll(
                $request->ids ?? []
            );

        return response()->json([
            'status' => 200,
            'message' => "{$count} ảnh đã xóa vĩnh viễn",
        ]);
    }
}
