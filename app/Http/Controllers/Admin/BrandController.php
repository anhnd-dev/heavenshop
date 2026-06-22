<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\Brand\StoreBrandRequest;
use App\Http\Requests\Admin\Brand\UpdateBrandRequest;
use App\Services\Admin\BrandService;
use Illuminate\Http\Request;

class BrandController extends BaseAdminController
{
    public function __construct(
        protected BrandService $brandService
    ) {}

    /**
     * Danh sách thương hiệu
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {

            return $this->brandService->datatable(
                $request->boolean('include_trashed')
            );
        }

        return view('admin.pages.brand.index');
    }

    /**
     * Thêm thương hiệu
     */
    public function store(StoreBrandRequest $request)
    {
        try {

            return $this->transaction(function () use ($request) {

                $this->brandService->store($request);

                return $this->successResponse(
                    'Thêm thương hiệu thành công'
                );
            });
        } catch (\Throwable $th) {

            return $this->errorResponse(
                'Đã xảy ra lỗi khi thêm thương hiệu'
            );
        }
    }

    /**
     * Chỉnh sửa thương hiệu
     */
    public function edit(Request $request)
    {
        try {

            $brand = $this->brandService->find(
                $request->id
            );

            return response()->json($brand);
        } catch (\Throwable $th) {

            return $this->errorResponse(
                'Không tìm thấy thương hiệu',
                404
            );
        }
    }

    /**
     * Cập nhật thương hiệu
     */
    public function update(UpdateBrandRequest $request)
    {
        try {

            return $this->transaction(function () use ($request) {

                $this->brandService->update(
                    $request,
                    $request->brand_id
                );

                return $this->successResponse(
                    'Cập nhật thương hiệu thành công'
                );
            });
        } catch (\Throwable $th) {

            return $this->errorResponse(
                'Đã xảy ra lỗi khi cập nhật thương hiệu'
            );
        }
    }

    /**
     * Xóa mềm thương hiệu
     */
    public function delete(Request $request)
    {
        try {

            return $this->transaction(function () use ($request) {

                $this->brandService->delete(
                    $request->id
                );

                return $this->successResponse(
                    'Thương hiệu đã được chuyển vào thùng rác'
                );
            });
        } catch (\Throwable $th) {

            return $this->errorResponse(
                'Đã xảy ra lỗi khi xóa thương hiệu'
            );
        }
    }

    /**
     * Xóa mềm nhiều thương hiệu
     */
    public function deleteAll(Request $request)
    {
        try {

            return $this->transaction(function () use ($request) {

                $deletedCount = $this->brandService->deleteAll(
                    $request->ids
                );

                return $this->successResponse(
                    "{$deletedCount} thương hiệu đã được chuyển vào thùng rác"
                );
            });
        } catch (\Throwable $th) {

            return $this->errorResponse(
                'Đã xảy ra lỗi khi xóa thương hiệu'
            );
        }
    }

    /**
     * Khôi phục thương hiệu
     */
    public function restore(Request $request)
    {
        try {

            return $this->transaction(function () use ($request) {

                $this->brandService->restore(
                    $request->id
                );

                return $this->successResponse(
                    'Khôi phục thương hiệu thành công'
                );
            });
        } catch (\Throwable $th) {

            return $this->errorResponse(
                'Đã xảy ra lỗi khi khôi phục thương hiệu'
            );
        }
    }

    /**
     * Khôi phục tất cả
     */
    public function restoreAll(Request $request)
    {
        try {

            return $this->transaction(function () use ($request) {

                $restoreCount = $this->brandService->restoreAll(
                    $request->ids
                );

                return $this->successResponse(
                    "{$restoreCount} thương hiệu đã được khôi phục thành công"
                );
            });
        } catch (\Throwable $th) {

            return $this->errorResponse(
                'Đã xảy ra lỗi khi khôi phục thương hiệu'
            );
        }
    }

    /**
     * Xóa vĩnh viễn
     */
    public function forceDelete(Request $request)
    {
        try {

            return $this->transaction(function () use ($request) {

                $this->brandService->forceDelete(
                    $request->id
                );

                return $this->successResponse(
                    'Xóa vĩnh viễn thương hiệu thành công'
                );
            });
        } catch (\Throwable $th) {

            return $this->errorResponse(
                'Đã xảy ra lỗi khi xóa thương hiệu'
            );
        }
    }

    /**
     * Xóa vĩnh viễn nhiều thương hiệu
     */
    public function forceDeleteAll(Request $request)
    {
        try {

            return $this->transaction(function () use ($request) {

                $deletedCount = $this->brandService->forceDeleteAll(
                    $request->ids
                );

                return $this->successResponse(
                    "{$deletedCount} thương hiệu đã được xóa vĩnh viễn"
                );
            });
        } catch (\Throwable $th) {

            return $this->errorResponse(
                'Đã xảy ra lỗi khi xóa thương hiệu'
            );
        }
    }

    /**
     * Đổi trạng thái thương hiệu
     */
    public function changeStatus(Request $request)
    {
        try {

            return $this->transaction(function () use ($request) {

                $this->brandService->changeStatus(
                    $request->id,
                    $request->new_status
                );

                return $this->successResponse(
                    'Cập nhật trạng thái thành công'
                );
            });
        } catch (\Throwable $th) {

            return $this->errorResponse(
                'Đã xảy ra lỗi khi cập nhật trạng thái'
            );
        }
    }
}
