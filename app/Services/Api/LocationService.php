<?php

namespace App\Services\Api;

use App\Models\Province;
use App\Models\District;
use App\Models\Ward;

use Illuminate\Support\Collection;

class LocationService
{
    /**
     * Lấy danh sách tỉnh/thành
     */
    public function getProvinces(): Collection
    {
        return Province::dropdown()->get();
    }

    /**
     * Lấy danh sách quận/huyện theo tỉnh
     */
    public function getDistricts(?int $provinceId): Collection
    {
        if (!$provinceId) return collect();

        return District::byProvince($provinceId)
            ->dropdown()
            ->get();
    }

    /**
     * Lấy danh sách phường/xã theo quận
     */
    public function getWards(?int $districtId): Collection
    {
        if (!$districtId) return collect();

        return Ward::byDistrict($districtId)
            ->dropdown()
            ->get();
    }
}
