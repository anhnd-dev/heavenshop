<?php

namespace App\Traits;

use Illuminate\Http\UploadedFile;

trait ImageUploadTrait
{
    protected function uploadImage(
        UploadedFile $file,
        string $folder
    ): string {

        $imageName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

        $destinationPath = public_path("uploads/{$folder}");

        if (!file_exists($destinationPath)) {
            mkdir($destinationPath, 0777, true);
        }

        $file->move($destinationPath, $imageName);

        return $imageName;
    }

    protected function deleteImage(
        ?string $image,
        string $folder
    ): bool {

        if (!$image) {
            return true;
        }

        $filePath = public_path("uploads/{$folder}/{$image}");

        if (file_exists($filePath)) {
            unlink($filePath);
        }

        return true;
    }
}
