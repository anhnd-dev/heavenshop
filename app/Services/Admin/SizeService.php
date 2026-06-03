<?php

namespace App\Services\Admin;

use App\Models\Size;
use Yajra\DataTables\Facades\DataTables;

class SizeService
{
    /**
     * Datatable
     */
    public function datatable(bool $includeTrashed = false)
    {
        $query = Size::query()
            ->select([
                'id',
                'name',
                'is_active',
                'created_at',
            ]);

        if ($includeTrashed) {

            $query->onlyTrashed();
        }

        return DataTables::eloquent($query)

            ->addIndexColumn()

            ->addColumn('action', function ($size) use ($includeTrashed) {

                if ($includeTrashed) {

                    return '
                        <button
                            type="button"
                            id="' . $size->id . '"
                            class="restoreIcon btn btn-danger shadow btn-xs sharp mr-1 btn-sm">

                            <i class="fas fa-trash-restore"></i>

                        </button>

                        <button
                            type="button"
                            id="' . $size->id . '"
                            class="forceIcon btn btn-dark shadow btn-xs sharp btn-sm">

                            <i class="fas fa-trash-alt"></i>

                        </button>
                    ';
                }

                return '
                    <button
                        type="button"
                        id="' . $size->id . '"
                        class="editIcon btn btn-primary shadow btn-xs sharp mr-1 btn-sm"
                        data-toggle="modal"
                        data-target="#editSizeModal">

                        <i class="fas fa-pencil-alt"></i>

                    </button>

                    <button
                        type="button"
                        id="' . $size->id . '"
                        class="deleteIcon btn btn-danger shadow btn-xs sharp btn-sm">

                        <i class="fa fa-trash"></i>

                    </button>

                    <button type="button"
                        id="' . $size->id . '"
                        class="statusIcon btn ' . ($size->is_active ? 'btn-success' : 'btn-dark') . ' shadow btn-xs sharp btn-sm">

                        <i class="fa ' . ($size->is_active ? 'fa-eye' : 'fa-eye-slash') . '"></i>

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
    public function store(array $data): Size
    {
        return Size::create($data);
    }

    /**
     * Find
     */
    public function find(int $id): Size
    {
        return Size::findOrFail($id);
    }

    /**
     * Update
     */
    public function update(
        int $id,
        array $data
    ): Size {

        $size = $this->find($id);

        $size->update($data);

        return $size;
    }

    /**
     * Delete
     */
    public function delete(int $id): void
    {
        $this->find($id)->delete();
    }

    /**
     * Delete Multiple
     */
    public function deleteAll(array $ids): int
    {
        return Size::whereIn('id', $ids)
            ->delete();
    }

    /**
     * Restore
     */
    public function restore(int $id): void
    {
        Size::withTrashed()
            ->findOrFail($id)
            ->restore();
    }

    /**
     * Restore All
     */
    public function restoreAll(): void
    {
        Size::onlyTrashed()
            ->restore();
    }

    /**
     * Force Delete
     */
    public function forceDelete(int $id): void
    {
        Size::withTrashed()
            ->findOrFail($id)
            ->forceDelete();
    }

    /**
     * Force Delete Multiple
     */
    public function forceDeleteAll(array $ids): int
    {
        return Size::withTrashed()
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

        $size = $this->find($id);

        $size->update([
            'is_active' => $status,
        ]);
    }
}
