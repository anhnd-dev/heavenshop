<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SeoRequest;
use App\Models\Frontend;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FrontendController extends Controller
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function seo() // :GET
    {
        $seo = Frontend::where('data_key', 'seo.data')->first();
        return view('admin.frontend.seo', compact('seo'));
    }

    public function seoSubmit() // :POST
    {
        DB::beginTransaction();

        try {
            // Prepare data
            $data = [
                'keywords' => $this->request->keywords,
                'description' => $this->request->description,
                'social_title' => $this->request->social_title,
                'social_description' => $this->request->social_description,
            ];

            $seoPath = public_path('uploads/seo/');

            // Handle image upload
            if ($this->request->hasFile('image')) {
                $image_new = $this->uploadImage($this->request->file('image'), $seoPath);
                $data['image'] = $image_new;
            } else {
                $data['image'] = $this->request->image_old;
            }

            // Retrieve or create SEO data
            $seo = Frontend::firstOrNew(['data_key' => 'seo.data']);
            $seoData = $seo->exists ? json_decode($seo->data_value, true) : [];

            // Delete existing image if any
            if (isset($seoData['image']) && $seoData['image'] !== $data['image']) {
                $this->deleteImage($seoData['image'], $seoPath);
            }

            // Merge new data with existing data
            $updatedData = array_merge($seoData, $data);

            // Update or create SEO data in the database
            $seo->data_value = json_encode($updatedData);
            $seo->save();

            DB::commit();
            return response()->json(['status' => 200, 'message' => 'SEO data has been successfully updated.']);
        } catch (\Throwable $th) {
            DB::rollback();
            return response()->json(['status' => 500, 'message' => 'Failed to update SEO data.']);
        }
    }

    public function contact() // :GET
    {
        $contact = Frontend::where('data_key', 'contact_us.content')->first();
        return view('admin.frontend.contact', compact('contact'));
    }

    public function contactSubmit() // :POST
    {
        DB::beginTransaction();

        try {
            $data = [
                'title' => $this->request->title,
                'address' => $this->request->address,
                'email' => $this->request->email,
                'phone_number' => $this->request->phone_number,
                'question' => $this->request->question,
            ];

            $contactPath = public_path('uploads/contact/');

            $fields = ['image_url', 'map_url'];

            foreach ($fields as $field) {
                if ($this->request->hasFile($field)) {
                    $data[$field] = $this->uploadImage($this->request->file($field), $contactPath);
                    // Xóa hình ảnh cũ nếu nó tồn tại
                    if (isset($this->request->{$field . '_old'})) {
                        $this->deleteImage($this->request->{$field . '_old'}, $contactPath);
                    }
                } else {
                    $data[$field] = $this->request->{$field . '_old'};
                }
            }

            $contact = Frontend::firstOrNew(['data_key' => 'contact_us.content']);
            $contactData = $contact->exists ? json_decode($contact->data_value, true) : [];

            foreach ($fields as $field) {
                if (isset($contactData[$field]) && $contactData[$field] !== $data[$field]) {
                    $this->deleteImage($contactData[$field], $contactPath);
                }
            }

            // Hợp nhất dữ liệu mới với dữ liệu hiện có
            $updatedData = array_merge($contactData, $data);

            // Cập nhật hoặc tạo dữ liệu SEO trong cơ sở dữ liệu
            $contact->data_value = json_encode($updatedData);
            $contact->save();

            DB::commit();
            return response()->json(['status' => 200, 'message' => __('admin.notify.contact.updated')]);
        } catch (\Throwable $th) {
            DB::rollback();
            return response()->json(['status' => 500, 'message' => __('admin.notify.contact.err_updated')]);
        }
    }

    protected function uploadImage($file, $path)
    {
        $image_new = time() . '_' . $file->getClientOriginalName();
        $file->move($path, $image_new);
        return $image_new;
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
