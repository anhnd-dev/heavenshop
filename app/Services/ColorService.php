<?php

namespace App\Services;

use App\Models\Color;
use Yajra\DataTables\Facades\DataTables;

class ColorService
{
    /**
     * Datatable
     */
    public function datatable(bool $includeTrashed = false)
    {
        $query = Color::query()
            ->select([
                'id',
                'name',
                'code',
                'is_active',
                'created_at',
            ]);

        if ($includeTrashed) {

            $query->onlyTrashed();
        }

        return DataTables::eloquent($query)

            ->addIndexColumn()

            ->addColumn('color_preview', function ($color) {

                return '
                    <div
                        style="
                            width:35px;
                            height:35px;
                            border-radius: 4px;
                            background:' . $color->code . ';
                            border:1px solid #ddd;
                        ">
                    </div>
                ';
            })

            ->addColumn('action', function ($color) use ($includeTrashed) {

                if ($includeTrashed) {

                    return '
                        <button
                            type="button"
                            id="' . $color->id . '"
                            class="restoreIcon btn btn-danger shadow btn-xs sharp mr-1 btn-sm">

                            <i class="fas fa-trash-restore"></i>

                        </button>

                        <button
                            type="button"
                            id="' . $color->id . '"
                            class="forceIcon btn btn-dark shadow btn-xs sharp btn-sm">

                            <i class="fas fa-trash-alt"></i>

                        </button>
                    ';
                }

                return '
                    <button
                        type="button"
                        id="' . $color->id . '"
                        class="editIcon btn btn-primary shadow btn-xs sharp mr-1 btn-sm"
                        data-toggle="modal"
                        data-target="#editColorModal">

                        <i class="fas fa-pencil-alt"></i>

                    </button>

                    <button
                        type="button"
                        id="' . $color->id . '"
                        class="deleteIcon btn btn-danger shadow btn-xs sharp btn-sm">

                        <i class="fa fa-trash"></i>

                    </button>

                    <button type="button"
                        id="' . $color->id . '"
                        class="statusIcon btn ' . ($color->is_active ? 'btn-success' : 'btn-dark') . ' shadow btn-xs sharp btn-sm">

                        <i class="fa ' . ($color->is_active ? 'fa-eye' : 'fa-eye-slash') . '"></i>

                    </button>
                ';
            })

            ->rawColumns([
                'action',
                'color_preview',
            ])

            ->make(true);
    }

    /**
     * Store
     */
    public function store(array $data): Color
    {
        return Color::create($data);
    }

    /**
     * Find
     */
    public function find(int $id): Color
    {
        return Color::findOrFail($id);
    }

    /**
     * Update
     */
    public function update(
        int $id,
        array $data
    ): Color {

        $color = $this->find($id);

        $color->update($data);

        return $color;
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
        return Color::whereIn('id', $ids)
            ->delete();
    }

    /**
     * Restore
     */
    public function restore(int $id): void
    {
        Color::withTrashed()
            ->findOrFail($id)
            ->restore();
    }

    /**
     * Restore All
     */
    public function restoreAll(): void
    {
        Color::onlyTrashed()
            ->restore();
    }

    /**
     * Force Delete
     */
    public function forceDelete(int $id): void
    {
        Color::withTrashed()
            ->findOrFail($id)
            ->forceDelete();
    }

    /**
     * Force Delete Multiple
     */
    public function forceDeleteAll(array $ids): int
    {
        return Color::withTrashed()
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

        $color = $this->find($id);

        $color->update([
            'is_active' => $status,
        ]);
    }
}
