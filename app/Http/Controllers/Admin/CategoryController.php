<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\Category\StoreCategoryRequest;
use App\Http\Requests\Admin\Category\UpdateCategoryRequest;
use App\Services\Admin\CategoryService;
use Illuminate\Http\Request;

class CategoryController extends BaseAdminController
{
    public function __construct(
        protected CategoryService $categoryService
    ) {}

    /**
     * Danh sách danh mục
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {

            return $this->categoryService->datatable(
                $request->boolean('include_trashed')
            );
        }

        return view('admin.pages.category.index');
    }

    /**
     * Danh sách cha
     */
    public function selectCategory(Request $request)
    {
        return response()->json(
            $this->categoryService->select(
                $request->type,
                $request->exclude_id
            )
        );
    }

    /**
     * Thêm danh mục
     */
    public function store(StoreCategoryRequest $request)
    {
        try {

            return $this->transaction(function () use ($request) {

                $this->categoryService->store($request);

                return $this->successResponse(
                    'Thêm danh mục thành công'
                );
            });
        } catch (\Throwable $th) {

            return $this->errorResponse(
                'Đã xảy ra lỗi khi thêm danh mục'
            );
        }
    }

    /**
     * Chỉnh sửa danh mục
     */
    public function edit(Request $request)
    {
        try {

            $category = $this->categoryService->find(
                $request->id
            );

            return response()->json([
                'category' => $category,
            ]);
        } catch (\Throwable $th) {

            return $this->errorResponse(
                'Không tìm thấy danh mục',
                404
            );
        }
    }

    /**
     * Cập nhật danh mục
     */
    public function update(UpdateCategoryRequest $request)
    {
        try {

            return $this->transaction(function () use ($request) {

                $this->categoryService->update(
                    $request,
                    $request->category_id
                );

                return $this->successResponse(
                    'Cập nhật danh mục thành công'
                );
            });
        } catch (\Throwable $th) {

            return $this->errorResponse(
                'Đã xảy ra lỗi khi cập nhật danh mục'
            );
        }
    }

    /**
     * Xóa mềm danh mục
     */
    public function delete(Request $request)
    {
        try {

            return $this->transaction(function () use ($request) {

                $this->categoryService->delete(
                    $request->id
                );

                return $this->successResponse(
                    'Danh mục đã được chuyển vào thùng rác'
                );
            });
        } catch (\Throwable $th) {

            return $this->errorResponse(
                'Đã xảy ra lỗi khi xóa danh mục'
            );
        }
    }

    /**
     * Xóa mềm nhiều danh mục
     */
    public function deleteAll(Request $request)
    {
        try {

            return $this->transaction(function () use ($request) {

                $count = $this->categoryService->deleteAll(
                    $request->ids
                );

                return $this->successResponse(
                    "{$count} danh mục đã được chuyển vào thùng rác"
                );
            });
        } catch (\Throwable $th) {

            return $this->errorResponse(
                'Đã xảy ra lỗi khi xóa danh mục'
            );
        }
    }

    /**
     * Khôi phục danh mục
     */
    public function restore(Request $request)
    {
        try {

            return $this->transaction(function () use ($request) {

                $this->categoryService->restore(
                    $request->id
                );

                return $this->successResponse(
                    'Khôi phục danh mục thành công'
                );
            });
        } catch (\Throwable $th) {

            return $this->errorResponse(
                'Đã xảy ra lỗi khi khôi phục danh mục'
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

                $this->categoryService->restoreAll();

                return $this->successResponse(
                    'Khôi phục tất cả danh mục thành công'
                );
            });
        } catch (\Throwable $th) {

            return $this->errorResponse(
                'Đã xảy ra lỗi khi khôi phục danh mục'
            );
        }
    }

    /**
     * Xóa vĩnh viễn danh mục
     */
    public function forceDelete(Request $request)
    {
        try {

            return $this->transaction(function () use ($request) {

                $this->categoryService->forceDelete(
                    $request->id
                );

                return $this->successResponse(
                    'Xóa vĩnh viễn danh mục thành công'
                );
            });
        } catch (\Throwable $th) {

            return $this->errorResponse(
                'Đã xảy ra lỗi khi xóa danh mục'
            );
        }
    }

    /**
     * Xóa vĩnh viễn nhiều danh mục
     */
    public function forceDeleteAll(Request $request)
    {
        try {

            return $this->transaction(function () use ($request) {

                $count = $this->categoryService->forceDeleteAll(
                    $request->ids
                );

                return $this->successResponse(
                    "{$count} danh mục đã được xóa vĩnh viễn"
                );
            });
        } catch (\Throwable $th) {

            return $this->errorResponse(
                'Đã xảy ra lỗi khi xóa danh mục'
            );
        }
    }

    /**
     * Đổi trạng thái danh mục
     */
    public function changeStatus(Request $request)
    {
        try {

            return $this->transaction(function () use ($request) {

                $this->categoryService->changeStatus(
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
