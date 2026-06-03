<?php

namespace App\Http\Controllers\Admin;

use App\Models\Category;
use App\Services\Admin\BlogService;
use Illuminate\Http\Request;

class BlogController extends BaseAdminController
{
    public function __construct(
        protected BlogService $blogService
    ) {}

    /**
     * Danh sách bài viết
     */
    public function index(Request $request)
    {
        $categories = Category::query()
            ->where('type', 'blog')
            ->whereNotNull('parent_id')
            ->latest()
            ->get();

        if ($request->ajax()) {

            return $this->blogService->datatable(
                $request->boolean('include_trashed')
            );
        }

        return view(
            'admin.pages.blog.index',
            compact('categories')
        );
    }

    /**
     * Thêm bài viết
     */
    public function store(Request $request)
    {
        try {

            return $this->transaction(function () use ($request) {

                $this->blogService->store($request);

                return $this->successResponse(
                    'Thêm bài viết thành công'
                );
            });
        } catch (\Throwable $th) {

            return $this->errorResponse(
                'Đã xảy ra lỗi khi thêm bài viết'
            );
        }
    }

    /**
     * Chi tiết bài viết
     */
    public function edit(Request $request)
    {
        try {

            $blog = $this->blogService->find(
                $request->id
            );

            $categories = Category::query()
                ->where('type', 'blog')
                ->whereNotNull('parent_id')
                ->latest()
                ->get();

            return response()->json([
                'blog'       => $blog,
                'categories' => $categories,
            ]);
        } catch (\Throwable $th) {

            return $this->errorResponse(
                'Không tìm thấy bài viết',
                404
            );
        }
    }

    /**
     * Cập nhật bài viết
     */
    public function update(Request $request)
    {
        try {

            return $this->transaction(function () use ($request) {

                $this->blogService->update(
                    $request,
                    $request->blog_id
                );

                return $this->successResponse(
                    'Cập nhật bài viết thành công'
                );
            });
        } catch (\Throwable $th) {

            return $this->errorResponse(
                'Đã xảy ra lỗi khi cập nhật bài viết'
            );
        }
    }

    /**
     * Xóa mềm bài viết
     */
    public function delete(Request $request)
    {
        try {

            return $this->transaction(function () use ($request) {

                $this->blogService->delete(
                    $request->id
                );

                return $this->successResponse(
                    'Bài viết đã được chuyển vào thùng rác'
                );
            });
        } catch (\Throwable $th) {

            return $this->errorResponse(
                'Đã xảy ra lỗi khi xóa bài viết'
            );
        }
    }

    /**
     * Xóa mềm nhiều bài viết
     */
    public function deleteAll(Request $request)
    {
        try {

            return $this->transaction(function () use ($request) {

                $deletedCount = $this->blogService->deleteAll(
                    $request->ids
                );

                return $this->successResponse(
                    "{$deletedCount} bài viết đã được chuyển vào thùng rác"
                );
            });
        } catch (\Throwable $th) {

            return $this->errorResponse(
                'Đã xảy ra lỗi khi xóa bài viết'
            );
        }
    }

    /**
     * Khôi phục bài viết
     */
    public function restore(Request $request)
    {
        try {

            return $this->transaction(function () use ($request) {

                $this->blogService->restore(
                    $request->id
                );

                return $this->successResponse(
                    'Khôi phục bài viết thành công'
                );
            });
        } catch (\Throwable $th) {

            return $this->errorResponse(
                'Đã xảy ra lỗi khi khôi phục bài viết'
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

                $this->blogService->restoreAll();

                return $this->successResponse(
                    'Khôi phục tất cả bài viết thành công'
                );
            });
        } catch (\Throwable $th) {

            return $this->errorResponse(
                'Đã xảy ra lỗi khi khôi phục bài viết'
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

                $this->blogService->forceDelete(
                    $request->id
                );

                return $this->successResponse(
                    'Xóa vĩnh viễn bài viết thành công'
                );
            });
        } catch (\Throwable $th) {

            return $this->errorResponse(
                'Đã xảy ra lỗi khi xóa bài viết'
            );
        }
    }

    /**
     * Xóa vĩnh viễn nhiều bài viết
     */
    public function forceDeleteAll(Request $request)
    {
        try {

            return $this->transaction(function () use ($request) {

                $deletedCount = $this->blogService->forceDeleteAll(
                    $request->ids
                );

                return $this->successResponse(
                    "{$deletedCount} bài viết đã được xóa vĩnh viễn"
                );
            });
        } catch (\Throwable $th) {

            return $this->errorResponse(
                'Đã xảy ra lỗi khi xóa bài viết'
            );
        }
    }

    /**
     * Đổi trạng thái bài viết
     */
    public function changeStatus(Request $request)
    {
        try {

            return $this->transaction(function () use ($request) {

                $this->blogService->changeStatus(
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
