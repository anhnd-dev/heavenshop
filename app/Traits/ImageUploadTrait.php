<?php

namespace App\Traits;

use Illuminate\Http\UploadedFile;

trait ImageUploadTrait
{
    /**
     * =========================
     * UPLOAD FILE
     * =========================
     */
    protected function uploadFile(
        UploadedFile $file,
        string $folder
    ): string {

        $fileName = time()
            . '_'
            . uniqid()
            . '.'
            . $file->getClientOriginalExtension();

        $destinationPath = public_path(
            "uploads/{$folder}"
        );

        if (!file_exists($destinationPath)) {

            mkdir(
                $destinationPath,
                0777,
                true
            );
        }

        $file->move(
            $destinationPath,
            $fileName
        );

        return $fileName;
    }

    /**
     * =========================
     * DELETE FILE
     * =========================
     */
    protected function deleteFile(
        ?string $file,
        string $folder
    ): bool {

        if (!$file) {

            return true;
        }

        $filePath = public_path(
            "uploads/{$folder}/{$file}"
        );

        if (file_exists($filePath)) {

            unlink($filePath);
        }

        return true;
    }
}
