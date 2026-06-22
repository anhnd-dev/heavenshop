<?php

namespace App\Services\Admin;

use App\Models\Category;
use App\Traits\ImageUploadTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Yajra\DataTables\Facades\DataTables;

class CategoryService
{
    use ImageUploadTrait;

    /*
    |--------------------------------------------------------------------------
    | Datatable
    |--------------------------------------------------------------------------
    */

    public function datatable(
        bool $includeTrashed = false
    ) {

        $query = Category::query()

            ->with('parent')

            ->select([
                'id',
                'name',
                'slug',
                'type',
                'parent_id',
                'level',
                'image',
                'is_active',
                'created_at',
            ]);

        if ($includeTrashed) {

            $query->onlyTrashed();
        }

        return DataTables::eloquent($query)

            ->addIndexColumn()

            ->addColumn('parent', function ($category) {

                if (!$category->parent) {
                    return '<span class="text-muted">Root</span>';
                }

                return str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;', $category->parent->level)
                    . ($category->parent->level > 0 ? '└── ' : '')
                    . $category->parent->name;
            })

            ->addColumn('action', function (
                $category
            ) use ($includeTrashed) {

                if ($includeTrashed) {

                    return '
                        <button type="button"
                            id="' . $category->id . '"
                            class="restoreIcon btn btn-danger shadow btn-xs sharp mr-1 btn-sm">

                            <i class="fas fa-trash-restore"></i>

                        </button>

                        <button type="button"
                            id="' . $category->id . '"
                            class="forceIcon btn btn-danger shadow btn-xs sharp btn-sm">

                            <i class="fas fa-trash-alt"></i>

                        </button>
                    ';
                }

                return '
                    <button type="button"
                        id="' . $category->id . '"
                        class="editIcon btn btn-primary shadow btn-xs sharp mr-1 btn-sm"
                        data-toggle="modal"
                        data-target="#editCategoryModal">

                        <i class="fas fa-pencil-alt"></i>

                    </button>

                    <button type="button"
                        id="' . $category->id . '"
                        class="deleteIcon btn btn-danger shadow btn-xs sharp btn-sm">

                        <i class="fa fa-trash"></i>

                    </button>

                    <button type="button"
                        id="' . $category->id . '"
                        class="statusIcon btn ' . ($category->is_active
                    ? 'btn-success'
                    : 'btn-dark') . ' shadow btn-xs sharp btn-sm">

                        <i class="fa ' . ($category->is_active
                    ? 'fa-eye'
                    : 'fa-eye-slash') . '"></i>

                    </button>
                ';
            })

            ->editColumn(
                'created_at',
                fn($category)
                => $category->created_at
                    ?->format('d/m/Y H:i')
            )

            ->editColumn('level', function ($category) {

                $badges = [
                    0 => 'dark',
                    1 => 'primary',
                    2 => 'success',
                    3 => 'warning',
                    4 => 'danger',
                ];

                $color = $badges[$category->level] ?? 'info';

                return '
                    <span class="badge badge-' . $color . '">
                        Level ' . $category->level . '
                    </span>
                ';
            })

            ->editColumn('type', function ($category) {

                return match ($category->type) {
                    'product' => '
                        <span class="badge badge-primary">
                            <i class="fa fa-box mr-1"></i> Product
                        </span>
                    ',

                    'blog' => '
                        <span class="badge badge-success">
                            <i class="fa fa-newspaper mr-1"></i> Blog
                        </span>
                    ',

                    default => '
                        <span class="badge badge-dark">
                            Unknown
                        </span>
                    ',
                };
            })

            ->editColumn('is_active', function ($category) {

                if ($category->is_active) {

                    return '
                        <span class="badge badge-success">
                            <i class="fa fa-eye mr-1"></i> Hiển thị
                        </span>
                    ';
                }

                return '
                    <span class="badge badge-danger">
                        <i class="fa fa-eye-slash mr-1"></i> Tạm khóa
                    </span>
                ';
            })

            ->rawColumns([
                'parent',
                'level',
                'type',
                'is_active',
                'action',
            ])

            ->make(true);
    }

    /*
    |--------------------------------------------------------------------------
    | Select Parent Category
    |--------------------------------------------------------------------------
    */

    public function select(
        string $type,
        ?int $excludeId = null
    ) {
        return Category::query()

            ->where('type', $type)

            ->when(
                $excludeId,
                function ($query) use ($excludeId) {

                    $query->where(
                        'id',
                        '!=',
                        $excludeId
                    );
                }
            )

            ->orderBy('level')

            ->get([
                'id',
                'name',
                'slug',
                'level',
            ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Store
    |--------------------------------------------------------------------------
    */

    public function store(
        Request $request
    ): void {

        Category::create(
            $this->prepareData($request)
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Find
    |--------------------------------------------------------------------------
    */

    public function find(
        int $id
    ): Category {

        return Category::query()

            ->findOrFail($id);
    }

    /*
    |--------------------------------------------------------------------------
    | Update
    |--------------------------------------------------------------------------
    */

    public function update(
        Request $request,
        int $id
    ): void {

        $category = $this->find($id);

        /*
        |--------------------------------------------------------------------------
        | Prevent recursive parent
        |--------------------------------------------------------------------------
        */

        if (
            $request->parent_id &&
            $this->isDescendant(
                $request->parent_id,
                $id
            )
        ) {

            throw ValidationException::withMessages([
                'parent_id'
                => 'Danh mục cha không hợp lệ',
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Update category
        |--------------------------------------------------------------------------
        */

        $category->update(
            $this->prepareData(
                $request,
                $category
            )
        );

        /*
        |--------------------------------------------------------------------------
        | Update children slug recursively
        |--------------------------------------------------------------------------
        */

        $this->updateChildrenSlug(
            $category
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Delete
    |--------------------------------------------------------------------------
    */

    public function delete(
        int $id
    ): void {

        $category = $this->find($id);

        if (
            $category->children()
            ->exists()
        ) {

            throw ValidationException::withMessages([
                'category'
                => 'Không thể xóa danh mục cha',
            ]);
        }

        $category->delete();
    }

    /*
    |--------------------------------------------------------------------------
    | Delete All
    |--------------------------------------------------------------------------
    */

    public function deleteAll(
        array $ids
    ): int {

        return Category::query()

            ->whereIn('id', $ids)

            ->delete();
    }

    /*
    |--------------------------------------------------------------------------
    | Restore
    |--------------------------------------------------------------------------
    */

    public function restore(
        int $id
    ): void {

        Category::withTrashed()

            ->findOrFail($id)

            ->restore();
    }

    /*
    |--------------------------------------------------------------------------
    | Restore All
    |--------------------------------------------------------------------------
    */

    public function restoreAll(
        array $ids
    ): int {
        return Category::onlyTrashed()
            ->whereIn('id', $ids)
            ->restore();
    }

    /*
    |--------------------------------------------------------------------------
    | Force Delete
    |--------------------------------------------------------------------------
    */

    public function forceDelete(
        int $id
    ): void {

        $category = Category::withTrashed()

            ->findOrFail($id);

        $this->deleteFile(
            $category->image,
            'category'
        );

        $category->forceDelete();
    }

    /*
    |--------------------------------------------------------------------------
    | Force Delete All
    |--------------------------------------------------------------------------
    */

    public function forceDeleteAll(
        array $ids
    ): int {

        $categories = Category::withTrashed()

            ->whereIn('id', $ids)

            ->get();

        foreach ($categories as $category) {

            $this->deleteFile(
                $category->image,
                'category'
            );
        }

        return Category::withTrashed()

            ->whereIn('id', $ids)

            ->forceDelete();
    }

    /*
    |--------------------------------------------------------------------------
    | Change Status
    |--------------------------------------------------------------------------
    */

    public function changeStatus(
        int $id,
        int $status
    ): void {

        $category = $this->find($id);

        $category->update([
            'is_active' => $status,
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Prepare Data
    |--------------------------------------------------------------------------
    */

    private function prepareData(
        Request $request,
        ?Category $category = null
    ): array {

        /*
        |--------------------------------------------------------------------------
        | Parent
        |--------------------------------------------------------------------------
        */

        $parent = null;

        if ($request->parent_id) {

            $parent = Category::find(
                $request->parent_id
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Generate base slug
        |--------------------------------------------------------------------------
        */

        $baseSlug = Str::slug(
            $request->name
        );

        /*
        |--------------------------------------------------------------------------
        | Generate full slug
        |--------------------------------------------------------------------------
        */

        $slug = $parent
            ? $parent->slug . '/' . $baseSlug
            : $baseSlug;

        /*
        |--------------------------------------------------------------------------
        | Data
        |--------------------------------------------------------------------------
        */

        $data = [

            'name' => $request->name,

            'slug' => $slug,

            'type' => $request->type,

            'parent_id'
            => $request->parent_id,

            'level' => $parent
                ? $parent->level + 1
                : 0,
        ];

        /*
        |--------------------------------------------------------------------------
        | Upload Image
        |--------------------------------------------------------------------------
        */

        if ($request->hasFile('image')) {

            $data['image'] = $this->uploadFile(
                $request->file('image'),
                'category'
            );

            if ($category?->image) {

                $this->deleteFile(
                    $category->image,
                    'category'
                );
            }
        } else {

            $data['image']
                = $category?->image;
        }

        return $data;
    }

    /*
    |--------------------------------------------------------------------------
    | Update Children Slug
    |--------------------------------------------------------------------------
    */

    private function updateChildrenSlug(
        Category $category
    ): void {

        foreach (
            $category->children
            as $child
        ) {

            /*
            |--------------------------------------------------------------------------
            | Last slug segment
            |--------------------------------------------------------------------------
            */

            $baseSlug = Str::afterLast(
                $child->slug,
                '/'
            );

            /*
            |--------------------------------------------------------------------------
            | Update slug
            |--------------------------------------------------------------------------
            */

            $child->slug =
                $category->slug .
                '/' .
                $baseSlug;

            /*
            |--------------------------------------------------------------------------
            | Update level
            |--------------------------------------------------------------------------
            */

            $child->level =
                $category->level + 1;

            $child->save();

            /*
            |--------------------------------------------------------------------------
            | Recursive
            |--------------------------------------------------------------------------
            */

            $this->updateChildrenSlug(
                $child
            );
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Check Descendant
    |--------------------------------------------------------------------------
    */

    private function isDescendant(
        int $parentId,
        int $categoryId
    ): bool {

        $parent = Category::find(
            $parentId
        );

        while ($parent) {

            if (
                $parent->id === $categoryId
            ) {

                return true;
            }

            $parent = $parent->parent;
        }

        return false;
    }
}
