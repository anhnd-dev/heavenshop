<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\Api\LocationService;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class LocationController extends Controller
{
    protected LocationService $locationService;

    public function __construct(LocationService $locationService)
    {
        $this->locationService = $locationService;
    }

    /**
     * GET /api/location/provinces
     */
    public function provinces(): JsonResponse
    {
        return response()->json([
            'data' => $this->locationService->getProvinces()
        ]);
    }

    /**
     * GET /api/location/districts?province_id=
     */
    public function districts(Request $request): JsonResponse
    {
        $provinceId = $request->get('province_id');

        if (!$provinceId) {
            return response()->json([
                'data' => [],
                'message' => 'province_id is required'
            ], 400);
        }

        return response()->json([
            'data' => $this->locationService->getDistricts($provinceId)
        ]);
    }

    /**
     * GET /api/location/wards?district_id=
     */
    public function wards(Request $request): JsonResponse
    {
        $districtId = $request->get('district_id');

        if (!$districtId) {
            return response()->json([
                'data' => [],
                'message' => 'district_id is required'
            ], 400);
        }

        return response()->json([
            'data' => $this->locationService->getWards($districtId)
        ]);
    }
}
