<?php

namespace App\Services;

use App\Models\Blog;
use App\Traits\ImageUploadTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class BlogService
{
    use ImageUploadTrait;

    /**
     * Datatable
     */
    public function datatable(bool $includeTrashed = false)
    {
        $query = Blog::query()
            ->with([
                'category:id,name',
                'admin:id,full_name',
            ])
            ->select([
                'id',
                'title',
                'slug',
                'image',
                'view_count',
                'tags',
                'is_active',
                'category_id',
                'admin_id',
                'created_at',
            ]);

        if ($includeTrashed) {
            $query->onlyTrashed();
        }

        return DataTables::eloquent($query)

            ->addIndexColumn()

            ->addColumn('action', function ($blog) use ($includeTrashed) {

                if ($includeTrashed) {

                    return '
                        <button type="button"
                            id="' . $blog->id . '"
                            class="restoreIcon btn btn-danger shadow btn-xs sharp mr-1 btn-sm mb-1">

                            <i class="fas fa-trash-restore"></i>

                        </button>

                        <button type="button"
                            id="' . $blog->id . '"
                            class="forceIcon btn btn-danger shadow btn-xs sharp mr-1 btn-sm">

                            <i class="fas fa-trash-alt"></i>

                        </button>
                    ';
                }

                return '
                    <button type="button"
                        id="' . $blog->id . '"
                        class="editIcon btn btn-primary shadow btn-xs sharp mr-1 btn-sm mb-1"
                        data-toggle="modal"
                        data-target="#editBlogModal">

                        <i class="fas fa-pencil-alt"></i>

                    </button>

                    <button type="button"
                        id="' . $blog->id . '"
                        class="deleteIcon btn btn-danger shadow btn-xs sharp mr-1 btn-sm">

                        <i class="fa fa-trash"></i>

                    </button>

                    <button type="button"
                        id="' . $blog->id . '"
                        class="statusIcon btn ' . ($blog->is_active ? 'btn-success' : 'btn-dark') . ' shadow btn-xs sharp btn-sm">

                        <i class="fa ' . ($blog->is_active ? 'fa-eye' : 'fa-eye-slash') . '"></i>

                    </button>
                ';
            })

            ->addColumn('category_name', fn($blog) => $blog->category?->name)
            ->addColumn('admin_name', fn($blog) => $blog->admin?->full_name)
            ->editColumn(
                'created_at',
                fn($blog) => $blog->created_at?->format('d/m/Y H:i')
            )
            ->rawColumns(['action'])
            ->make(true);
    }

    /**
     * Store
     */
    public function store(Request $request): void
    {
        Blog::create(
            $this->prepareData($request)
        );
    }

    /**
     * Edit
     */
    public function find(int $id): Blog
    {
        return Blog::query()
            ->findOrFail($id);
    }

    /**
     * Update
     */
    public function update(
        Request $request,
        int $id
    ): void {

        $blog = $this->find($id);

        $blog->update(
            $this->prepareData($request, $blog)
        );
    }

    /**
     * Soft Delete
     */
    public function delete(int $id): void
    {
        $this->find($id)->delete();
    }

    /**
     * Soft Delete Multiple
     */
    public function deleteAll(array $ids): int
    {
        return Blog::query()
            ->whereIn('id', $ids)
            ->delete();
    }

    /**
     * Restore
     */
    public function restore(int $id): void
    {
        Blog::withTrashed()
            ->findOrFail($id)
            ->restore();
    }

    /**
     * Restore All
     */
    public function restoreAll(): void
    {
        Blog::onlyTrashed()
            ->restore();
    }

    /**
     * Force Delete
     */
    public function forceDelete(int $id): void
    {
        $blog = Blog::withTrashed()
            ->findOrFail($id);

        $this->deleteFile(
            $blog->image,
            'blog'
        );

        $blog->forceDelete();
    }

    /**
     * Force Delete Multiple
     */
    public function forceDeleteAll(array $ids): int
    {
        $blogs = Blog::withTrashed()
            ->whereIn('id', $ids)
            ->get();

        foreach ($blogs as $blog) {

            $this->deleteFile(
                $blog->image,
                'blog'
            );
        }

        return Blog::withTrashed()
            ->whereIn('id', $ids)
            ->forceDelete();
    }

    /**
     * Change Status
     */
    public function changeStatus(
        int $id,
        int $status
    ): void {

        $blog = $this->find($id);

        $blog->update([
            'is_active' => $status,
        ]);
    }

    /**
     * Prepare Data
     */
    private function prepareData(
        Request $request,
        ?Blog $blog = null
    ): array {

        $data = $request->only([
            'title',
            'slug',
            'category_id',
        ]);

        if ($request->hasFile('image')) {

            $data['image'] = $this->uploadFile(
                $request->file('image'),
                'blog'
            );

            if ($blog?->image) {

                $this->deleteFile(
                    $blog->image,
                    'blog'
                );
            }
        } else {

            $data['image'] = $blog?->image;
        }

        $data['description'] = strip_tags(
            $request->description,
            '<p><span><a><strong><em><ul><ol><li>'
        );

        $data['content'] = strip_tags(
            $request->content,
            '<p><span><a><strong><em><ul><ol><li>'
        );

        $data['tags'] = implode(
            ',',
            $request->tags ?? []
        );

        $data['admin_id'] = Auth::guard('admin')->id();

        return $data;
    }
}
