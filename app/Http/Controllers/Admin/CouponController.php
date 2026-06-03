<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\Coupon\StoreCouponRequest;
use App\Http\Requests\Admin\Coupon\UpdateCouponRequest;
use App\Services\Admin\CouponService;
use Illuminate\Http\Request;

class CouponController extends BaseAdminController
{
    public function __construct(
        protected CouponService $couponService
    ) {}

    /**
     * Danh sách coupon
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {

            return $this->couponService->datatable(
                $request->boolean('include_trashed')
            );
        }

        return view('admin.pages.coupon.index');
    }

    /**
     * Thêm coupon
     */
    public function store(StoreCouponRequest $request)
    {
        try {

            return $this->transaction(function () use ($request) {

                $this->couponService->store($request);

                return $this->successResponse(
                    'Thêm coupon thành công'
                );
            });
        } catch (\Throwable $th) {

            return $this->errorResponse(
                'Đã xảy ra lỗi khi thêm coupon'
            );
        }
    }

    /**
     * Chỉnh sửa coupon
     */
    public function edit(Request $request)
    {
        try {

            $coupon = $this->couponService->find(
                $request->id
            );

            return response()->json($coupon);
        } catch (\Throwable $th) {

            return $this->errorResponse(
                'Không tìm thấy coupon',
                404
            );
        }
    }

    /**
     * Cập nhật coupon
     */
    public function update(UpdateCouponRequest $request)
    {
        try {

            return $this->transaction(function () use ($request) {

                $this->couponService->update(
                    $request,
                    $request->coupon_id
                );

                return $this->successResponse(
                    'Cập nhật coupon thành công'
                );
            });
        } catch (\Throwable $th) {

            return $this->errorResponse(
                'Đã xảy ra lỗi khi cập nhật coupon'
            );
        }
    }

    /**
     * Xóa mềm coupon
     */
    public function delete(Request $request)
    {
        try {

            return $this->transaction(function () use ($request) {

                $this->couponService->delete(
                    $request->id
                );

                return $this->successResponse(
                    'Coupon đã được chuyển vào thùng rác'
                );
            });
        } catch (\Throwable $th) {

            return $this->errorResponse(
                'Đã xảy ra lỗi khi xóa coupon'
            );
        }
    }

    /**
     * Xóa mềm nhiều coupon
     */
    public function deleteAll(Request $request)
    {
        try {

            return $this->transaction(function () use ($request) {

                $deletedCount = $this->couponService->deleteAll(
                    $request->ids
                );

                return $this->successResponse(
                    "{$deletedCount} coupon đã được chuyển vào thùng rác"
                );
            });
        } catch (\Throwable $th) {

            return $this->errorResponse(
                'Đã xảy ra lỗi khi xóa coupon'
            );
        }
    }

    /**
     * Khôi phục coupon
     */
    public function restore(Request $request)
    {
        try {

            return $this->transaction(function () use ($request) {

                $this->couponService->restore(
                    $request->id
                );

                return $this->successResponse(
                    'Khôi phục coupon thành công'
                );
            });
        } catch (\Throwable $th) {

            return $this->errorResponse(
                'Đã xảy ra lỗi khi khôi phục coupon'
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

                $this->couponService->restoreAll();

                return $this->successResponse(
                    'Khôi phục tất cả coupon thành công'
                );
            });
        } catch (\Throwable $th) {

            return $this->errorResponse(
                'Đã xảy ra lỗi khi khôi phục coupon'
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

                $this->couponService->forceDelete(
                    $request->id
                );

                return $this->successResponse(
                    'Xóa vĩnh viễn coupon thành công'
                );
            });
        } catch (\Throwable $th) {

            return $this->errorResponse(
                'Đã xảy ra lỗi khi xóa coupon'
            );
        }
    }

    /**
     * Xóa vĩnh viễn nhiều coupon
     */
    public function forceDeleteAll(Request $request)
    {
        try {

            return $this->transaction(function () use ($request) {

                $deletedCount = $this->couponService->forceDeleteAll(
                    $request->ids
                );

                return $this->successResponse(
                    "{$deletedCount} coupon đã được xóa vĩnh viễn"
                );
            });
        } catch (\Throwable $th) {

            return $this->errorResponse(
                'Đã xảy ra lỗi khi xóa coupon'
            );
        }
    }

    /**
     * Đổi trạng thái mã giảm giá
     */
    public function changeStatus(Request $request)
    {
        try {

            return $this->transaction(function () use ($request) {

                $this->couponService->changeStatus(
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
