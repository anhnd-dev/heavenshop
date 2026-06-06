<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\Frontend\SeoRequest;
use App\Http\Requests\Admin\Frontend\ContactRequest;

use App\Models\Frontend;
use App\Traits\ImageUploadTrait;
use Illuminate\Http\Request;

class FrontendController extends BaseAdminController
{
    use ImageUploadTrait;

    private const CONTACT_IMAGE_FIELDS = [
        'image_url',
        'map_url',
    ];

    /*
    |--------------------------------------------------------------------------
    | SEO
    |--------------------------------------------------------------------------
    */

    public function seo()
    {
        $seo = Frontend::query()
            ->where('data_key', Frontend::SEO)
            ->first();

        return view(
            'admin.frontend.seo',
            compact('seo')
        );
    }

    public function seoSubmit(SeoRequest $request)
    {
        try {

            $this->transaction(function () use ($request) {

                $seo = Frontend::firstOrNew([
                    'data_key' => Frontend::SEO,
                ]);

                $oldData = $seo->data_value ?? [];

                $data = [
                    'keywords' => $request->keywords,
                    'description' => $request->description,
                    'social_title' => $request->social_title,
                    'social_description' => $request->social_description,
                    'image' => $request->image_old,
                ];

                if ($request->hasFile('image')) {

                    $data['image'] = $this->uploadFile(
                        $request->file('image'),
                        'seo'
                    );
                }

                if (
                    !empty($oldData['image']) &&
                    $oldData['image'] !== $data['image']
                ) {
                    $this->deleteFile(
                        $oldData['image'],
                        'seo'
                    );
                }

                $seo->fill([
                    'data_value' => $data,
                    'is_active' => true,
                ]);

                $seo->save();
            });

            return $this->successResponse(
                'SEO data has been successfully updated.'
            );
        } catch (\Throwable $th) {

            return $this->errorResponse(
                'Failed to update SEO data.'
            );
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Contact
    |--------------------------------------------------------------------------
    */

    public function contact()
    {
        $contact = Frontend::query()
            ->where('data_key', Frontend::CONTACT)
            ->first();

        return view(
            'admin.frontend.contact',
            compact('contact')
        );
    }

    public function contactSubmit(ContactRequest $request)
    {
        try {

            $this->transaction(function () use ($request) {

                $contact = Frontend::firstOrNew([
                    'data_key' => Frontend::CONTACT,
                ]);

                $oldData = $contact->data_value ?? [];

                $data = [
                    'title' => $request->title,
                    'address' => $request->address,
                    'email' => $request->email,
                    'phone_number' => $request->phone_number,
                    'question' => $request->question,
                ];

                $this->processContactImages(
                    $request,
                    $data
                );

                $this->deleteOldContactImages(
                    $oldData,
                    $data
                );

                $contact->fill([
                    'data_value' => $data,
                    'is_active' => true,
                ]);

                $contact->save();
            });

            return $this->successResponse(
                __('admin.notify.contact.updated')
            );
        } catch (\Throwable $th) {

            return $this->errorResponse(
                __('admin.notify.contact.err_updated')
            );
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Helpers
    |--------------------------------------------------------------------------
    */

    private function processContactImages(
        ContactRequest $request,
        array &$data
    ): void {

        foreach (self::CONTACT_IMAGE_FIELDS as $field) {

            if ($request->hasFile($field)) {

                $data[$field] = $this->uploadFile(
                    $request->file($field),
                    'contact'
                );
            } else {

                $data[$field] = $request->{$field . '_old'};
            }
        }
    }

    private function deleteOldContactImages(
        array $oldData,
        array $newData
    ): void {

        foreach (self::CONTACT_IMAGE_FIELDS as $field) {

            if (
                empty($oldData[$field]) ||
                $oldData[$field] === ($newData[$field] ?? null)
            ) {
                continue;
            }

            $this->deleteFile(
                $oldData[$field],
                'contact'
            );
        }
    }
}
