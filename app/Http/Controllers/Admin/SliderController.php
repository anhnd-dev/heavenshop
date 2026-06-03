<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\Slider\StoreSliderRequest;
use App\Http\Requests\Admin\Slider\UpdateSliderRequest;
use App\Services\Admin\SliderService;
use Illuminate\Http\Request;

class SliderController extends BaseAdminController
{
    public function __construct(
        protected SliderService $sliderService
    ) {}

    /**
     * Danh sách slider
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {

            return $this->sliderService->datatable(
                $request->boolean('include_trashed')
            );
        }

        return view('admin.pages.slider.index');
    }

    /**
     * Thêm slider
     */
    public function store(StoreSliderRequest $request)
    {
        try {

            return $this->transaction(function () use ($request) {

                $this->sliderService->store($request);

                return $this->successResponse(
                    'Thêm slider thành công'
                );
            });
        } catch (\Throwable $th) {

            return $this->errorResponse(
                'Đã xảy ra lỗi khi thêm slider'
            );
        }
    }

    /**
     * Chỉnh sửa slider
     */
    public function edit(Request $request)
    {
        try {

            $slider = $this->sliderService->find(
                $request->id
            );

            return response()->json($slider);
        } catch (\Throwable $th) {

            return $this->errorResponse(
                'Không tìm thấy slider',
                404
            );
        }
    }

    /**
     * Cập nhật slider
     */
    public function update(UpdateSliderRequest $request)
    {
        try {

            return $this->transaction(function () use ($request) {

                $this->sliderService->update(
                    $request,
                    $request->slider_id
                );

                return $this->successResponse(
                    'Cập nhật slider thành công'
                );
            });
        } catch (\Throwable $th) {

            return $this->errorResponse(
                'Đã xảy ra lỗi khi cập nhật slider'
            );
        }
    }

    /**
     * Xóa mềm slider
     */
    public function delete(Request $request)
    {
        try {

            return $this->transaction(function () use ($request) {

                $this->sliderService->delete(
                    $request->id
                );

                return $this->successResponse(
                    'Slider đã được chuyển vào thùng rác'
                );
            });
        } catch (\Throwable $th) {

            return $this->errorResponse(
                'Đã xảy ra lỗi khi xóa slider'
            );
        }
    }

    /**
     * Xóa mềm nhiều slider
     */
    public function deleteAll(Request $request)
    {
        try {

            return $this->transaction(function () use ($request) {

                $deletedCount = $this->sliderService->deleteAll(
                    $request->ids
                );

                return $this->successResponse(
                    "{$deletedCount} slider đã được chuyển vào thùng rác"
                );
            });
        } catch (\Throwable $th) {

            return $this->errorResponse(
                'Đã xảy ra lỗi khi xóa slider'
            );
        }
    }

    /**
     * Khôi phục slider
     */
    public function restore(Request $request)
    {
        try {

            return $this->transaction(function () use ($request) {

                $this->sliderService->restore(
                    $request->id
                );

                return $this->successResponse(
                    'Khôi phục slider thành công'
                );
            });
        } catch (\Throwable $th) {

            return $this->errorResponse(
                'Đã xảy ra lỗi khi khôi phục slider'
            );
        }
    }

    /**
     * Khôi phục tất cả
     */
    public function restoreAll()
    {
        try {

            return $this->transaction(function () {

                $this->sliderService->restoreAll();

                return $this->successResponse(
                    'Khôi phục tất cả slider thành công'
                );
            });
        } catch (\Throwable $th) {

            return $this->errorResponse(
                'Đã xảy ra lỗi khi khôi phục slider'
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

                $this->sliderService->forceDelete(
                    $request->id
                );

                return $this->successResponse(
                    'Xóa vĩnh viễn slider thành công'
                );
            });
        } catch (\Throwable $th) {

            return $this->errorResponse(
                'Đã xảy ra lỗi khi xóa slider'
            );
        }
    }

    /**
     * Xóa vĩnh viễn nhiều slider
     */
    public function forceDeleteAll(Request $request)
    {
        try {

            return $this->transaction(function () use ($request) {

                $deletedCount = $this->sliderService->forceDeleteAll(
                    $request->ids
                );

                return $this->successResponse(
                    "{$deletedCount} slider đã được xóa vĩnh viễn"
                );
            });
        } catch (\Throwable $th) {

            return $this->errorResponse(
                'Đã xảy ra lỗi khi xóa slider'
            );
        }
    }

    /**
     * Đổi trạng thái slider
     */
    public function changeStatus(Request $request)
    {
        try {

            return $this->transaction(function () use ($request) {

                $this->sliderService->changeStatus(
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
