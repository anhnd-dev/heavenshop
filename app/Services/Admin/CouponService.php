<?php

namespace App\Services\Admin;

use App\Models\Coupon;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class CouponService
{
    /**
     * Datatable
     */
    public function datatable(bool $includeTrashed = false)
    {
        $query = Coupon::query()
            ->select([
                'id',
                'code',
                'discount_type',
                'discount_value',
                'min_order_amount',
                'max_discount_amount',
                'quantity',
                'used_count',
                'is_unlimited',
                'is_active',
                'start_date',
                'end_date',
                'created_at',
            ]);

        if ($includeTrashed) {
            $query->onlyTrashed();
        }

        return DataTables::eloquent($query)

            ->addIndexColumn()

            ->addColumn('discount', function ($coupon) {

                if (
                    $coupon->discount_type ===
                    Coupon::TYPE_PERCENTAGE
                ) {
                    return $coupon->discount_value . '%';
                }

                return number_format(
                    $coupon->discount_value
                ) . ' VNĐ';
            })

            ->addColumn('status', function ($coupon) {

                if (!$coupon->is_active) {

                    return '
                        <span class="badge badge-danger">
                            Tạm khóa
                        </span>
                    ';
                }

                if (
                    $coupon->end_date &&
                    now()->gt($coupon->end_date)
                ) {

                    return '
                        <span class="badge badge-warning">
                            Hết hạn
                        </span>
                    ';
                }

                return '
                    <span class="badge badge-success">
                        Hoạt động
                    </span>
                ';
            })

            ->addColumn('quantity_text', function ($coupon) {

                if ($coupon->is_unlimited) {
                    return 'Không giới hạn';
                }

                return $coupon->used_count .
                    '/' .
                    $coupon->quantity;
            })

            ->addColumn(
                'action',
                function ($coupon) use ($includeTrashed) {

                    if ($includeTrashed) {

                        return '
                            <button type="button"
                                id="' . $coupon->id . '"
                                class="restoreIcon btn btn-danger shadow btn-xs sharp mr-1 btn-sm">

                                <i class="fas fa-trash-restore"></i>

                            </button>

                            <button type="button"
                                id="' . $coupon->id . '"
                                class="forceIcon btn btn-danger shadow btn-xs sharp btn-sm">

                                <i class="fas fa-trash-alt"></i>

                            </button>
                        ';
                    }

                    return '
                        <button type="button"
                            id="' . $coupon->id . '"
                            class="editIcon btn btn-primary shadow btn-xs sharp mr-1 btn-sm"
                            data-toggle="modal"
                            data-target="#editCouponModal">

                            <i class="fas fa-pencil-alt"></i>

                        </button>

                        <button type="button"
                            id="' . $coupon->id . '"
                            class="deleteIcon btn btn-danger shadow btn-xs sharp btn-sm">

                            <i class="fa fa-trash"></i>

                        </button>

                        <button type="button"
                            id="' . $coupon->id . '"
                            class="statusIcon btn ' . ($coupon->is_active ? 'btn-success' : 'btn-dark') . ' shadow btn-xs sharp btn-sm">

                            <i class="fa ' . ($coupon->is_active ? 'fa-eye' : 'fa-eye-slash') . '"></i>

                        </button>
                    ';
                }
            )

            ->editColumn(
                'start_date',
                fn($coupon) => $coupon->start_date
                    ? $coupon->start_date->format('d/m/Y H:i')
                    : '-'
            )

            ->editColumn(
                'end_date',
                fn($coupon) => $coupon->end_date
                    ? $coupon->end_date->format('d/m/Y H:i')
                    : '-'
            )

            ->editColumn(
                'created_at',
                fn($coupon) => $coupon->created_at
                    ? $coupon->created_at->format('d/m/Y H:i')
                    : '-'
            )

            ->rawColumns([
                'status',
                'action',
            ])

            ->make(true);
    }

    /**
     * Store
     */
    public function store(Request $request): void
    {
        Coupon::create(
            $this->prepareData($request)
        );
    }

    /**
     * Find
     */
    public function find(int $id): Coupon
    {
        return Coupon::query()
            ->findOrFail($id);
    }

    /**
     * Update
     */
    public function update(
        Request $request,
        int $id
    ): void {

        $coupon = $this->find($id);

        $coupon->update(
            $this->prepareData($request, $coupon)
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
        return Coupon::query()
            ->whereIn('id', $ids)
            ->delete();
    }

    /**
     * Restore
     */
    public function restore(int $id): void
    {
        Coupon::withTrashed()
            ->findOrFail($id)
            ->restore();
    }

    /**
     * Restore All
     */
    public function restoreAll(): void
    {
        Coupon::onlyTrashed()
            ->restore();
    }

    /**
     * Force Delete
     */
    public function forceDelete(int $id): void
    {
        Coupon::withTrashed()
            ->findOrFail($id)
            ->forceDelete();
    }

    /**
     * Force Delete Multiple
     */
    public function forceDeleteAll(array $ids): int
    {
        return Coupon::withTrashed()
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

        $coupon = $this->find($id);

        $coupon->update([
            'is_active' => $status,
        ]);
    }

    private function generateDescription(array $data): string
    {
        $value = number_format($data['discount_value'], 0, ',', '.');

        $minOrder = number_format(
            $data['min_order_amount'] ?? 0,
            0,
            ',',
            '.'
        );

        if ($data['discount_type'] === 'percentage') {

            $text = "Giảm {$value}%";

            if (!empty($data['max_discount_amount'])) {

                $max = number_format(
                    $data['max_discount_amount'],
                    0,
                    ',',
                    '.'
                );

                $text .= " tối đa {$max}đ";
            }
        } else {

            $text = "Giảm {$value}đ";
        }

        if (!empty($data['min_order_amount'])) {

            $text .= " cho đơn từ {$minOrder}đ";
        }

        return $text;
    }

    /**
     * Prepare Data
     */
    private function prepareData(
        Request $request,
        ?Coupon $coupon = null
    ): array {

        $data = [
            'code' => strtoupper($request->code),

            'discount_type' => $request->discount_type,

            'discount_value' => $request->discount_value,

            'min_order_amount' => $request->min_order_amount,

            'max_discount_amount' => $request->max_discount_amount,

            'quantity' => $request->boolean('is_unlimited')
                ? null
                : $request->quantity,

            'is_unlimited' => $request->boolean(
                'is_unlimited'
            ),

            'start_date' => $request->start_date,

            'end_date' => $request->end_date,
        ];

        if (!$coupon) {
            $data['used_count'] = 0;
        }

        $data['description'] = $this->generateDescription(
            $data
        );

        return $data;
    }
}
