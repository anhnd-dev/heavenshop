<?php

namespace App\Services;

class CartService
{
    public function getCart(): array
    {
        return session('cart', []);
    }

    public function putCart(array $cart): void
    {
        session()->put('cart', $cart);
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

        if ($subtotal <= 0) {
            return 0;
        }

        return $subtotal >= 299000
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
}
