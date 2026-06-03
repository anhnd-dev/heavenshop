<?php

namespace App\Services\Admin;

use App\Models\Slider;
use App\Traits\ImageUploadTrait;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class SliderService
{
    use ImageUploadTrait;

    /**
     * Datatable
     */
    public function datatable(bool $includeTrashed = false)
    {
        $query = Slider::query()
            ->select([
                'id',
                'title',
                'image',
                'position',
                'sort_order',
                'is_active',
                'start_at',
                'end_at',
            ]);

        if ($includeTrashed) {
            $query->onlyTrashed();
        }

        return DataTables::eloquent($query)

            ->addIndexColumn()

            ->addColumn('action', function ($slider) use ($includeTrashed) {

                if ($includeTrashed) {

                    return '
                        <button type="button"
                            id="' . $slider->id . '"
                            class="restoreIcon btn btn-danger shadow btn-xs sharp mr-1 btn-sm">

                            <i class="fas fa-trash-restore"></i>

                        </button>

                        <button type="button"
                            id="' . $slider->id . '"
                            class="forceIcon btn btn-danger shadow btn-xs sharp btn-sm">

                            <i class="fas fa-trash-alt"></i>

                        </button>
                    ';
                }

                return '
                    <button type="button"
                        id="' . $slider->id . '"
                        class="editIcon btn btn-primary shadow btn-xs sharp mr-1 btn-sm"
                        data-toggle="modal"
                        data-target="#editSliderModal">

                        <i class="fas fa-pencil-alt"></i>

                    </button>

                    <button type="button"
                        id="' . $slider->id . '"
                        class="deleteIcon btn btn-danger shadow btn-xs sharp btn-sm">

                        <i class="fa fa-trash"></i>

                    </button>

                    <button type="button"
                        id="' . $slider->id . '"
                        class="statusIcon btn ' . ($slider->is_active ? 'btn-success' : 'btn-dark') . ' shadow btn-xs sharp btn-sm">

                        <i class="fa ' . ($slider->is_active ? 'fa-eye' : 'fa-eye-slash') . '"></i>

                    </button>
                ';
            })

            ->editColumn(
                'start_at',
                fn($slider) => $slider->start_at?->format('d/m/Y H:i')
            )

            ->editColumn(
                'end_at',
                fn($slider) => $slider->end_at?->format('d/m/Y H:i')
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
        Slider::create(
            $this->prepareData($request)
        );
    }

    /**
     * Find
     */
    public function find(int $id): Slider
    {
        return Slider::query()
            ->findOrFail($id);
    }

    /**
     * Update
     */
    public function update(
        Request $request,
        int $id
    ): void {

        $slider = $this->find($id);

        $slider->update(
            $this->prepareData($request, $slider)
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
        return Slider::query()
            ->whereIn('id', $ids)
            ->delete();
    }

    /**
     * Restore
     */
    public function restore(int $id): void
    {
        Slider::withTrashed()
            ->findOrFail($id)
            ->restore();
    }

    /**
     * Restore All
     */
    public function restoreAll(): void
    {
        Slider::onlyTrashed()
            ->restore();
    }

    /**
     * Force Delete
     */
    public function forceDelete(int $id): void
    {
        $slider = Slider::withTrashed()
            ->findOrFail($id);

        $this->deleteFile(
            $slider->image,
            'slider'
        );

        $slider->forceDelete();
    }

    /**
     * Force Delete Multiple
     */
    public function forceDeleteAll(array $ids): int
    {
        $sliders = Slider::withTrashed()
            ->whereIn('id', $ids)
            ->get();

        foreach ($sliders as $slider) {

            $this->deleteFile(
                $slider->image,
                'slider'
            );
        }

        return Slider::withTrashed()
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

        $slider = $this->find($id);

        $slider->update([
            'is_active' => $status,
        ]);
    }

    /**
     * Prepare Data
     */
    private function prepareData(
        Request $request,
        ?Slider $slider = null
    ): array {

        $data = $request->only([
            'title',
            'subtitle',
            'url',
            'position',
            'sort_order',
            'start_at',
            'end_at',
        ]);

        if ($request->hasFile('image')) {

            $data['image'] = $this->uploadFile(
                $request->file('image'),
                'slider'
            );

            if ($slider?->image) {

                $this->deleteFile(
                    $slider->image,
                    'slider'
                );
            }
        } else {

            $data['image'] = $slider?->image;
        }

        return $data;
    }
}
