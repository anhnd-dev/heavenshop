<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Frontend;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class GeneralSettingController extends Controller
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function general() // :GET
    {
        $setting = Frontend::where('data_key', 'setting.data')->first();
        return view('admin.setting.general', compact('setting'));
    }

    public function generalSubmit() // :POST
    {
        DB::beginTransaction();

        try {
            $data = [
                'shipping_free_threshold' => $this->request->shipping_free_threshold,
            ];

            // Retrieve or create setting data
            $setting = Frontend::firstOrNew(['data_key' => 'setting.data']);
            $setting->data_value = json_encode($data);
            $setting->save();

            DB::commit();
            return response()->json(['status' => 200, 'message' => "Cấu hình đã được thêm hoặc cập nhật thành công."]);
        } catch (\Throwable $th) {
            return response()->json(['status' => 500, 'message' => 'Đã xảy ra lỗi khi thêm hoặc cập nhật cấu hình']);
        }
    }

    public function optimize() // :GET
    {
        Artisan::call('optimize:clear');
        toastr()->success("Cache cleared successfully");
        return back();
    }

    public function cookie() // :GET
    {
        $cookie = Frontend::where('data_key', 'cookie.data')->first();
        return view('admin.setting.cookie', compact('cookie'));
    }

    public function cookieSubmit() // :POST
    {
        DB::beginTransaction();

        try {
            $data = [
                'link' => $this->request->link,
                'status' => $this->request->status === "on" ? 1 : 0,
                'description' => $this->request->description
            ];

            // Retrieve or create cookie data
            $cookie = Frontend::firstOrNew(['data_key' => 'cookie.data']);
            $cookie->data_value = json_encode($data);
            $cookie->save();

            DB::commit();
            return response()->json(['status' => 200, 'message' => "Cookie đã được thêm hoặc cập nhật thành công."]);
        } catch (\Throwable $th) {
            return response()->json(['status' => 500, 'message' => 'Đã xảy ra lỗi khi thêm hoặc cập nhật cookie']);
        }
    }

    public function logoIcon() // :GET
    {
        $logoIcon = Frontend::where('data_key', 'logo_icon.data')->first();
        return view('admin.setting.logo_icon', compact('logoIcon'));
    }

    public function logoIconSubmit()
    {
        DB::beginTransaction();

        try {
            $imageData = [];
            $logoPath = public_path('uploads/logoIcon/');
            $faviconPath = public_path('uploads/favicon/');

            $faviconSizes = ['favicon', 'favicon_57x', 'favicon_72x', 'favicon_114x'];

            foreach ($faviconSizes as $size) {
                if ($this->request->hasFile($size)) {
                    $imageData[$size] = $this->uploadImage($this->request->file($size), $faviconPath);
                    if (isset($this->request->{$size . '_old'})) {
                        $this->deleteImage($this->request->{$size . '_old'}, $faviconPath);
                    }
                } else {
                    $imageData[$size] = $this->request->{$size . '_old'};
                }
            }

            $logoFields = ['logo_white', 'logo_black', 'logo_white_2x', 'logo_black_2x'];

            foreach ($logoFields as $field) {
                if ($this->request->hasFile($field)) {
                    $imageData[$field] = $this->uploadImage($this->request->file($field), $logoPath);
                    if (isset($this->request->{$field . '_old'})) {
                        $this->deleteImage($this->request->{$field . '_old'}, $logoPath);
                    }
                } else {
                    $imageData[$field] = $this->request->{$field . '_old'};
                }
            }

            $logoIcon = Frontend::firstOrNew(['data_key' => 'logo_icon.data']);
            $logoIconData = $logoIcon->exists ? json_decode($logoIcon->data_value, true) : [];

            foreach ($logoIconData as $key => $value) {
                if (isset($imageData[$key]) && $value !== $imageData[$key]) {
                    $this->deleteImage($value, $key === 'favicon' ? $faviconPath : $logoPath);
                }
            }

            // Merge new data with existing data
            $updatedData = array_merge($logoIconData, $imageData);

            // Update or create logoIcon data in the database
            $logoIcon->data_value = json_encode($updatedData);
            $logoIcon->save();

            DB::commit();
            return response()->json(['status' => 200, 'message' => 'Logo & favicon data has been successfully updated.']);
        } catch (\Throwable $th) {
            DB::rollback();
            return response()->json(['status' => 500, 'message' => 'Failed to update Logo & favicon data.']);
        }
    }

    protected function uploadImage($file, $path)
    {
        $fileName = time() . '_' . $file->getClientOriginalName();
        $file->move($path, $fileName);
        return $fileName;
    }

    protected function deleteImage($file, $path)
    {
        $filePath = $path . $file;

        if (file_exists($filePath) && is_readable($filePath)) {
            if (unlink($filePath)) {
                usleep(50000);
                return true;
            } else {
                return false;
            }
        } else {
            return true;
        }
    }
}
