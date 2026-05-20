<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BaseAdminController extends Controller
{
    protected function transaction(callable $callback): mixed
    {
        DB::beginTransaction();

        try {

            $result = $callback();

            DB::commit();

            return $result;
        } catch (\Throwable $th) {

            DB::rollBack();

            Log::error($th);

            throw $th;
        }
    }

    protected function successResponse(
        string $message,
        array $data = [],
        int $status = 200
    ): JsonResponse {

        return response()->json([
            'status'  => $status,
            'message' => $message,
            'data'    => $data,
        ], $status);
    }

    protected function errorResponse(
        string $message = 'Đã xảy ra lỗi',
        int $status = 500
    ): JsonResponse {

        return response()->json([
            'status'  => $status,
            'message' => $message,
        ], $status);
    }
}
