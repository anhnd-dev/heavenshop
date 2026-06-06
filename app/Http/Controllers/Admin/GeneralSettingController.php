<?php

namespace App\Http\Controllers\Admin;

use App\Models\Frontend;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use App\Traits\ImageUploadTrait;

class GeneralSettingController extends BaseAdminController
{
    use ImageUploadTrait;

    private const FAVICON_FIELDS = [
        'favicon',
        'favicon_57x',
        'favicon_72x',
        'favicon_114x',
    ];

    private const LOGO_FIELDS = [
        'logo_white',
        'logo_black',
        'logo_white_2x',
        'logo_black_2x',
    ];

    public function __construct(
        protected Request $request
    ) {}

    /*
    |--------------------------------------------------------------------------
    | General Setting
    |--------------------------------------------------------------------------
    */

    public function general()
    {
        $setting = Frontend::query()
            ->where('data_key', Frontend::SETTING)
            ->first();

        return view(
            'admin.setting.general',
            compact('setting')
        );
    }

    public function generalSubmit()
    {
        try {

            $this->transaction(function () {

                Frontend::updateOrCreate(
                    [
                        'data_key' => Frontend::SETTING,
                    ],
                    [
                        'data_value' => [
                            'shipping_free_threshold'
                            => $this->request->shipping_free_threshold,
                        ],
                        'is_active' => true,
                    ]
                );
            });

            return $this->successResponse(
                'Cấu hình đã được cập nhật thành công.'
            );
        } catch (\Throwable $th) {

            return $this->errorResponse(
                'Đã xảy ra lỗi khi cập nhật cấu hình.'
            );
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Cookie
    |--------------------------------------------------------------------------
    */

    public function cookie()
    {
        $cookie = Frontend::query()
            ->where('data_key', Frontend::COOKIE)
            ->first();

        return view(
            'admin.setting.cookie',
            compact('cookie')
        );
    }

    public function cookieSubmit()
    {
        try {

            $this->transaction(function () {

                Frontend::updateOrCreate(
                    [
                        'data_key' => Frontend::COOKIE,
                    ],
                    [
                        'data_value' => [
                            'link' => $this->request->link,
                            'status' => $this->request->status === 'on',
                            'description' => $this->request->description,
                        ],
                        'is_active' => true,
                    ]
                );
            });

            return $this->successResponse(
                'Cookie đã được cập nhật thành công.'
            );
        } catch (\Throwable $th) {

            return $this->errorResponse(
                'Đã xảy ra lỗi khi cập nhật cookie.'
            );
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Logo & Favicon
    |--------------------------------------------------------------------------
    */

    public function logoIcon()
    {
        $logoIcon = Frontend::query()
            ->where('data_key', Frontend::LOGO_ICON)
            ->first();

        return view(
            'admin.setting.logo_icon',
            compact('logoIcon')
        );
    }

    public function logoIconSubmit()
    {
        try {

            $this->transaction(function () {

                $imageData = [];

                $this->processImages(
                    self::FAVICON_FIELDS,
                    'favicon',
                    $imageData
                );

                $this->processImages(
                    self::LOGO_FIELDS,
                    'logoIcon',
                    $imageData
                );

                $logoIcon = Frontend::firstOrNew([
                    'data_key' => Frontend::LOGO_ICON,
                ]);

                $oldData = $logoIcon->data_value ?? [];

                $this->deleteReplacedFiles(
                    $oldData,
                    $imageData
                );

                $logoIcon->fill([
                    'data_value' => $imageData,
                    'is_active' => true,
                ]);

                $logoIcon->save();
            });

            return $this->successResponse(
                'Logo & favicon đã được cập nhật thành công.'
            );
        } catch (\Throwable $th) {

            return $this->errorResponse(
                'Không thể cập nhật logo & favicon.'
            );
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Optimize
    |--------------------------------------------------------------------------
    */

    public function optimize()
    {
        Artisan::call('optimize:clear');

        toastr()->success(
            'Cache cleared successfully'
        );

        return back();
    }

    private function processImages(
        array $fields,
        string $folder,
        array &$imageData
    ): void {

        foreach ($fields as $field) {

            $imageData[$field] = $this->request->hasFile($field)
                ? $this->uploadFile(
                    $this->request->file($field),
                    $folder
                )
                : $this->request->{$field . '_old'};
        }
    }

    private function deleteReplacedFiles(
        array $oldData,
        array $newData
    ): void {

        foreach ($oldData as $key => $oldFile) {

            if (
                blank($oldFile) ||
                !isset($newData[$key]) ||
                $oldFile === $newData[$key]
            ) {
                continue;
            }

            $this->deleteFile(
                $oldFile,
                in_array($key, self::FAVICON_FIELDS)
                    ? 'favicon'
                    : 'logoIcon'
            );
        }
    }
}
