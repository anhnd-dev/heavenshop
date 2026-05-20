<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Color\StoreColorRequest;
use App\Http\Requests\Color\UpdateColorRequest;
use App\Services\ColorService;
use Illuminate\Http\Request;

class ColorController extends BaseAdminController
{
    public function __construct(
        protected ColorService $colorService
    ) {}

    /**
     * Danh sách màu
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {

            return $this->colorService->datatable(
                $request->boolean('include_trashed')
            );
        }

        return view('admin.pages.color.index');
    }

    /**
     * Thêm màu
     */
    public function store(StoreColorRequest $request)
    {
        try {

            return $this->transaction(function () use ($request) {

                $color = $this->colorService->store(
                    $request->validated()
                );

                return $this->successResponse(
                    "Thêm màu sắc {$color->name} thành công"
                );
            });
        } catch (\Throwable $th) {

            return $this->errorResponse(
                'Đã xảy ra lỗi khi thêm màu sắc'
            );
        }
    }

    /**
     * Chỉnh sửa màu
     */
    public function edit(Request $request)
    {
        try {

            return response()->json(
                $this->colorService->find($request->id)
            );
        } catch (\Throwable $th) {

            return $this->errorResponse(
                'Không tìm thấy màu',
                404
            );
        }
    }

    /**
     * Cập nhật màu
     */
    public function update(UpdateColorRequest $request)
    {
        try {

            return $this->transaction(function () use ($request) {

                $color = $this->colorService->update(
                    $request->color_id,
                    $request->validated()
                );

                return $this->successResponse(
                    "Đã cập nhật màu sắc {$color->name} thành công"
                );
            });
        } catch (\Throwable $th) {

            return $this->errorResponse(
                'Đã xảy ra lỗi khi cập nhật màu sắc'
            );
        }
    }

    /**
     * Xóa mềm màu
     */
    public function delete(Request $request)
    {
        try {

            return $this->transaction(function () use ($request) {

                $color = $this->colorService->find(
                    $request->id
                );

                $this->colorService->delete(
                    $request->id
                );

                return $this->successResponse(
                    "Màu sắc {$color->name} đã được chuyển vào thùng rác"
                );
            });
        } catch (\Throwable $th) {

            return $this->errorResponse(
                'Đã xảy ra lỗi khi xóa màu sắc'
            );
        }
    }

    /**
     * Xóa mềm nhiều màu
     */
    public function deleteAll(Request $request)
    {
        try {

            return $this->transaction(function () use ($request) {

                $deletedCount = $this->colorService
                    ->deleteAll($request->ids);

                return $this->successResponse(
                    "{$deletedCount} màu sắc đã được chuyển vào thùng rác"
                );
            });
        } catch (\Throwable $th) {

            return $this->errorResponse(
                'Đã xảy ra lỗi khi xóa màu sắc'
            );
        }
    }

    /**
     * Khôi phục màu
     */
    public function restore(Request $request)
    {
        try {

            return $this->transaction(function () use ($request) {

                $this->colorService->restore(
                    $request->id
                );

                return $this->successResponse(
                    'Khôi phục màu sắc thành công'
                );
            });
        } catch (\Throwable $th) {

            return $this->errorResponse(
                'Đã xảy ra lỗi khi khôi phục màu sắc'
            );
        }
    }

    /**
     * Khôi phục tất cả danh mục
     */
    public function restoreAll()
    {
        try {

            return $this->transaction(function () {

                $this->colorService->restoreAll();

                return $this->successResponse(
                    'Khôi phục tất cả màu sắc thành công'
                );
            });
        } catch (\Throwable $th) {

            return $this->errorResponse(
                'Đã xảy ra lỗi khi khôi phục màu sắc'
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

                $this->colorService->forceDelete(
                    $request->id
                );

                return $this->successResponse(
                    'Xóa vĩnh viễn màu sắc thành công'
                );
            });
        } catch (\Throwable $th) {

            return $this->errorResponse(
                'Đã xảy ra lỗi khi xóa màu sắc'
            );
        }
    }

    /**
     * Xóa vĩnh viễn nhiều màu
     */
    public function forceDeleteAll(Request $request)
    {
        try {

            return $this->transaction(function () use ($request) {

                $deletedCount = $this->colorService
                    ->forceDeleteAll($request->ids);

                return $this->successResponse(
                    "{$deletedCount} màu sắc đã được xóa vĩnh viễn"
                );
            });
        } catch (\Throwable $th) {

            return $this->errorResponse(
                'Đã xảy ra lỗi khi xóa màu sắc'
            );
        }
    }

    /**
     * Đổi trạng thái màu
     */
    public function changeStatus(Request $request)
    {
        try {

            return $this->transaction(function () use ($request) {

                $this->colorService->changeStatus(
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
