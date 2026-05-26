<?php

namespace App\Services;

use App\Models\Product;
use App\Traits\ImageUploadTrait;
use Illuminate\Validation\ValidationException;

class ProductVariantService
{
    use ImageUploadTrait;

    // =========================
    // SYNC VARIANTS
    // =========================
    public function syncVariants(Product $product, array $variants): void
    {
        foreach ($variants as $variant) {

            $this->validatePayload($product, $variant);

            $payload = $this->buildPayload($variant);

            // =========================
            // UPDATE
            // =========================
            if (!empty($variant['id'])) {

                $model = $product->variants()
                    ->where('id', $variant['id'])
                    ->firstOrFail();

                $this->checkDuplicateCombo($product, $payload, $model->id);

                $this->checkDuplicateSku($product, $payload['sku'], $model->id);

                $payload['image'] = $this->handleImageUpdate($model, $variant);

                $model->update($payload);
            }

            // =========================
            // CREATE
            // =========================
            else {

                $this->checkDuplicateCombo($product, $payload);

                $this->checkDuplicateSku($product, $payload['sku']);

                $payload['image'] = $this->handleImageCreate($variant);

                $product->variants()->create($payload);
            }
        }
    }

    // =========================
    // BUILD PAYLOAD
    // =========================
    private function buildPayload(array $variant): array
    {
        return [
            'color_id' => (int) $variant['color_id'],
            'size_id' => (int) $variant['size_id'],
            'sku' => $variant['sku'] ?? null,
            'price' => $variant['price'] ?? 0,
            'sale_price' => $variant['sale_price'] ?? null,
            'stock' => $variant['stock'] ?? 0,
            'is_active' => $variant['is_active'] ?? 1,
        ];
    }

    // =========================
    // VALIDATE INPUT
    // =========================
    private function validatePayload(Product $product, array $variant): void
    {
        if (
            !isset($variant['color_id']) ||
            !isset($variant['size_id'])
        ) {
            throw ValidationException::withMessages([
                'variant' => 'Color và Size không được để trống'
            ]);
        }

        if (empty($variant['sku'])) {
            throw ValidationException::withMessages([
                'sku' => 'SKU không được để trống'
            ]);
        }
    }

    // =========================
    // CHECK DUPLICATE COMBO
    // =========================

    private function checkDuplicateCombo(
        Product $product,
        array $payload,
        ?int $ignoreId = null
    ): void {

        $query = $product->variants()
            ->where('product_id', $product->id)
            ->where('color_id', (int) $payload['color_id'])
            ->where('size_id', (int) $payload['size_id'])
            ->whereNull('deleted_at');

        if ($ignoreId !== null) {

            $query->where(
                'id',
                '!=',
                (int) $ignoreId
            );
        }

        if ($query->exists()) {

            throw ValidationException::withMessages([
                'variant' => 'Biến thể màu + size đã tồn tại'
            ]);
        }
    }

    private function checkDuplicateSku(
        Product $product,
        string $sku,
        $ignoreId = null
    ): void {

        $query = $product->variants()
            ->where('sku', trim($sku));

        if (!is_null($ignoreId)) {
            $query->where('id', '!=', (int) $ignoreId);
        }

        if ($query->exists()) {

            throw ValidationException::withMessages([
                'sku' => [
                    'SKU đã tồn tại'
                ]
            ]);
        }
    }

    // =========================
    // HANDLE IMAGE (CREATE)
    // =========================
    private function handleImageCreate(array $variant): ?string
    {
        if (empty($variant['image'])) {
            return null;
        }

        return $this->uploadFile($variant['image'], 'variant');
    }

    // =========================
    // HANDLE IMAGE (UPDATE)
    // =========================
    private function handleImageUpdate($model, array $variant): ?string
    {
        if (empty($variant['image'])) {
            return $model->image;
        }

        if ($model->image) {
            $this->deleteFile($model->image, 'variant');
        }

        return $this->uploadFile($variant['image'], 'variant');
    }

    // =========================
    // DELETE IMAGE ONLY
    // =========================
    public function deleteVariantImage(string $image): void
    {
        $this->deleteFile($image, 'variant');
    }
}
