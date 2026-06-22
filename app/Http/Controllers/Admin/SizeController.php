<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\Size\StoreSizeRequest;
use App\Http\Requests\Admin\Size\UpdateSizeRequest;
use App\Services\Admin\SizeService;
use Illuminate\Http\Request;

class SizeController extends BaseAdminController
{
    public function __construct(
        protected SizeService $sizeService
    ) {}

    /**
     * Danh sách kích cỡ
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {

            return $this->sizeService->datatable(
                $request->boolean('include_trashed')
            );
        }

        return view('admin.pages.size.index');
    }

    /**
     * Thêm kích cỡ
     */
    public function store(StoreSizeRequest $request)
    {
        try {

            return $this->transaction(function () use ($request) {

                $size = $this->sizeService->store(
                    $request->validated()
                );

                return $this->successResponse(
                    "Thêm kích cỡ {$size->name} thành công"
                );
            });
        } catch (\Throwable $th) {

            return $this->errorResponse(
                'Đã xảy ra lỗi khi thêm kích cỡ'
            );
        }
    }

    /**
     * Chỉnh sửa kích cỡ
     */
    public function edit(Request $request)
    {
        try {

            return response()->json(
                $this->sizeService->find($request->id)
            );
        } catch (\Throwable $th) {

            return $this->errorResponse(
                'Không tìm thấy kích cỡ',
                404
            );
        }
    }

    /**
     * Cập nhật kích cỡ
     */
    public function update(UpdateSizeRequest $request)
    {
        try {

            return $this->transaction(function () use ($request) {

                $size = $this->sizeService->update(
                    $request->size_id,
                    $request->validated()
                );

                return $this->successResponse(
                    "Đã cập nhật kích cỡ {$size->name} thành công"
                );
            });
        } catch (\Throwable $th) {

            return $this->errorResponse(
                'Đã xảy ra lỗi khi cập nhật kích cỡ'
            );
        }
    }

    /**
     * Xóa mềm kích cỡ
     */
    public function delete(Request $request)
    {
        try {

            return $this->transaction(function () use ($request) {

                $size = $this->sizeService->find(
                    $request->id
                );

                $this->sizeService->delete(
                    $request->id
                );

                return $this->successResponse(
                    "Kích cỡ {$size->name} đã được chuyển vào thùng rác"
                );
            });
        } catch (\Throwable $th) {

            return $this->errorResponse(
                'Đã xảy ra lỗi khi xóa kích cỡ'
            );
        }
    }

    /**
     * Xóa mềm nhiều kích cỡ
     */
    public function deleteAll(Request $request)
    {
        try {

            return $this->transaction(function () use ($request) {

                $deletedCount = $this->sizeService
                    ->deleteAll($request->ids);

                return $this->successResponse(
                    "{$deletedCount} kích cỡ đã được chuyển vào thùng rác"
                );
            });
        } catch (\Throwable $th) {

            return $this->errorResponse(
                'Đã xảy ra lỗi khi xóa kích cỡ'
            );
        }
    }

    /**
     * Khôi phục kích cỡ
     */
    public function restore(Request $request)
    {
        try {

            return $this->transaction(function () use ($request) {

                $this->sizeService->restore(
                    $request->id
                );

                return $this->successResponse(
                    'Khôi phục kích cỡ thành công'
                );
            });
        } catch (\Throwable $th) {

            return $this->errorResponse(
                'Đã xảy ra lỗi khi khôi phục kích cỡ'
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

                $restoreCount = $this->sizeService->restoreAll(
                    $request->ids
                );

                return $this->successResponse(
                    "{$restoreCount} kích thước đã được khôi phục thành công"
                );
            });
        } catch (\Throwable $th) {

            return $this->errorResponse(
                'Đã xảy ra lỗi khi khôi phục kích cỡ'
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

                $this->sizeService->forceDelete(
                    $request->id
                );

                return $this->successResponse(
                    'Xóa vĩnh viễn kích cỡ thành công'
                );
            });
        } catch (\Throwable $th) {

            return $this->errorResponse(
                'Đã xảy ra lỗi khi xóa kích cỡ'
            );
        }
    }

    /**
     * Xóa vĩnh viễn nhiều kích cỡ
     */
    public function forceDeleteAll(Request $request)
    {
        try {

            return $this->transaction(function () use ($request) {

                $deletedCount = $this->sizeService
                    ->forceDeleteAll($request->ids);

                return $this->successResponse(
                    "{$deletedCount} kích cỡ đã được xóa vĩnh viễn"
                );
            });
        } catch (\Throwable $th) {

            return $this->errorResponse(
                'Đã xảy ra lỗi khi xóa kích cỡ'
            );
        }
    }

    /**
     * Đổi trạng kích thước
     */
    public function changeStatus(Request $request)
    {
        try {

            return $this->transaction(function () use ($request) {

                $this->sizeService->changeStatus(
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
