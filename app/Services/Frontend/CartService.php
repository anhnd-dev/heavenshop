<?php

namespace App\Services\Frontend;

use App\Models\CustomerCartItem;
use App\Models\Frontend;
use App\Models\ProductVariant;

class CartService
{
    public function getCart(): array
    {
        return session('cart', []);
    }

    public function putCart(array $cart): void
    {
        session()->put('cart', $cart);

        $this->syncToDatabase();
    }

    public function selectedItems(): array
    {
        return collect($this->getCart())
            ->filter(
                fn($item) => $item['selected'] ?? false
            )
            ->toArray();
    }

    public function subtotalSelected(): float
    {
        return collect($this->selectedItems())
            ->sum(function ($item) {

                return $item['price'] * $item['quantity'];
            });
    }

    public function shipping(?float $subtotal = null): float
    {
        $subtotal = $subtotal
            ?? $this->subtotalSelected();

        $free_ship = Frontend::getSetting(
            'shipping_free_threshold',
            0
        );

        if ($subtotal <= 0) {
            return 0;
        }

        return $subtotal >= $free_ship
            ? 0
            : 30000;
    }

    public function toggleItem(
        int $variantId,
        bool $selected
    ): void {

        $cart = $this->getCart();

        if (!isset($cart[$variantId])) {
            return;
        }

        $cart[$variantId]['selected'] = $selected;

        $this->putCart($cart);
    }

    public function toggleAll(bool $selected): void
    {
        $cart = $this->getCart();

        foreach ($cart as &$item) {

            $item['selected'] = $selected;
        }

        $this->putCart($cart);
    }

    public function loadFromDatabase(): void
    {
        if (!auth('customer')->check()) {
            return;
        }

        $customerId = auth('customer')->id();

        $items = CustomerCartItem::query()
            ->with([
                'variant.product.variants.color',
                'variant.product.variants.size',
                'variant.color',
                'variant.size'
            ])
            ->where('customer_id', $customerId)
            ->get();

        $cart = [];

        foreach ($items as $item) {

            $variant = $item->variant;

            if (!$variant) {
                continue;
            }

            $cart[$variant->id] = [

                'variant_id' => $variant->id,

                'product_id' => $variant->product_id,

                'product_name' => $variant->product->name,

                'product_slug' => $variant->product->slug,

                'image' => $variant->image
                    ?? $variant->product->image,

                'price' => $variant->price,

                'quantity' => $item->quantity,

                'stock' => $variant->stock,

                'selected' => $item->selected,

                'color' => optional($variant->color)->name,

                'size' => optional($variant->size)->name,

                'variants' => $variant->product
                    ->variants
                    ->map(function ($v) {

                        return [

                            'id' => $v->id,

                            'color_id' => $v->color_id,

                            'color_name' => optional($v->color)->name,

                            'size_id' => $v->size_id,

                            'size_name' => optional($v->size)->name,

                            'price' => $v->price,

                            'stock' => $v->stock,

                            'image' => $v->image
                                ?? $v->product->image,
                        ];
                    })
                    ->values()
                    ->toArray()
            ];
        }

        session()->put('cart', $cart);
    }

    public function syncToDatabase(): void
    {
        if (!auth('customer')->check()) {
            return;
        }

        $customerId = auth('customer')->id();

        $cart = $this->getCart();

        CustomerCartItem::query()
            ->where('customer_id', $customerId)
            ->delete();

        foreach ($cart as $item) {

            CustomerCartItem::create([
                'customer_id' => $customerId,
                'product_variant_id' => $item['variant_id'],
                'quantity' => $item['quantity'],
                'selected' => $item['selected'],
            ]);
        }
    }

    public function mergeCartAfterLogin()
    {
        $sessionCart = session('cart', []);

        $this->loadFromDatabase();

        $dbCart = session('cart', []);

        foreach ($sessionCart as $variantId => $item) {

            $variant = ProductVariant::find($variantId);

            if (!$variant) {
                continue;
            }

            // Đã có trong DB
            if (isset($dbCart[$variantId])) {

                $dbCart[$variantId]['quantity'] = min(
                    $dbCart[$variantId]['quantity']
                        + $item['quantity'],
                    $variant->stock
                );
            }

            // Chưa có trong DB
            else {

                $item['quantity'] = min(
                    $item['quantity'],
                    $variant->stock
                );

                $dbCart[$variantId] = $item;
            }
        }

        session()->put('cart', $dbCart);

        $this->syncToDatabase();
    }
}
