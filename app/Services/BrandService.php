<?php

namespace App\Services;

use App\Models\Brand;
use App\Traits\ImageUploadTrait;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class BrandService
{
    use ImageUploadTrait;

    /**
     * Datatable
     */
    public function datatable(bool $includeTrashed = false)
    {
        $query = Brand::query()
            ->select([
                'id',
                'name',
                'slug',
                'image',
                'is_active',
                'created_at',
            ]);

        if ($includeTrashed) {
            $query->onlyTrashed();
        }

        return DataTables::eloquent($query)

            ->addIndexColumn()

            ->addColumn('action', function ($brand) use ($includeTrashed) {

                if ($includeTrashed) {

                    return '
                        <button type="button"
                            id="' . $brand->id . '"
                            class="restoreIcon btn btn-danger shadow btn-xs sharp mr-1 btn-sm">

                            <i class="fas fa-trash-restore"></i>

                        </button>

                        <button type="button"
                            id="' . $brand->id . '"
                            class="forceIcon btn btn-danger shadow btn-xs sharp btn-sm">

                            <i class="fas fa-trash-alt"></i>

                        </button>
                    ';
                }

                return '
                    <button type="button"
                        id="' . $brand->id . '"
                        class="editIcon btn btn-primary shadow btn-xs sharp mr-1 btn-sm"
                        data-toggle="modal"
                        data-target="#editBrandModal">

                        <i class="fas fa-pencil-alt"></i>

                    </button>

                    <button type="button"
                        id="' . $brand->id . '"
                        class="deleteIcon btn btn-danger shadow btn-xs sharp btn-sm">

                        <i class="fa fa-trash"></i>

                    </button>

                    <button type="button"
                        id="' . $brand->id . '"
                        class="statusIcon btn ' . ($brand->is_active ? 'btn-success' : 'btn-dark') . ' shadow btn-xs sharp btn-sm">

                        <i class="fa ' . ($brand->is_active ? 'fa-eye' : 'fa-eye-slash') . '"></i>

                    </button>
                ';
            })
            ->editColumn(
                'created_at',
                fn($brand) => $brand->created_at?->format('d/m/Y H:i')
            )
            ->rawColumns([
                'action',
            ])
            ->make(true);
    }

    /**
     * Store
     */
    public function store(Request $request): void
    {
        Brand::create(
            $this->prepareData($request)
        );
    }

    /**
     * Find
     */
    public function find(int $id): Brand
    {
        return Brand::query()
            ->findOrFail($id);
    }

    /**
     * Update
     */
    public function update(
        Request $request,
        int $id
    ): void {

        $brand = $this->find($id);

        $brand->update(
            $this->prepareData($request, $brand)
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
        return Brand::query()
            ->whereIn('id', $ids)
            ->delete();
    }

    /**
     * Restore
     */
    public function restore(int $id): void
    {
        Brand::withTrashed()
            ->findOrFail($id)
            ->restore();
    }

    /**
     * Restore All
     */
    public function restoreAll(): void
    {
        Brand::onlyTrashed()
            ->restore();
    }

    /**
     * Force Delete
     */
    public function forceDelete(int $id): void
    {
        $brand = Brand::withTrashed()
            ->findOrFail($id);

        $this->deleteImage(
            $brand->image,
            'brand'
        );

        $brand->forceDelete();
    }

    /**
     * Force Delete Multiple
     */
    public function forceDeleteAll(array $ids): int
    {
        $brands = Brand::withTrashed()
            ->whereIn('id', $ids)
            ->get();

        foreach ($brands as $brand) {

            $this->deleteImage(
                $brand->image,
                'brand'
            );
        }

        return Brand::withTrashed()
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

        $brand = $this->find($id);

        $brand->update([
            'is_active' => $status,
        ]);
    }

    /**
     * Prepare Data
     */
    private function prepareData(
        Request $request,
        ?Brand $brand = null
    ): array {

        $data = $request->only([
            'name',
            'slug',
        ]);

        if ($request->hasFile('image')) {

            $data['image'] = $this->uploadImage(
                $request->file('image'),
                'brand'
            );

            if ($brand?->image) {

                $this->deleteImage(
                    $brand->image,
                    'brand'
                );
            }
        } else {

            $data['image'] = $brand?->image;
        }

        return $data;
    }
}
