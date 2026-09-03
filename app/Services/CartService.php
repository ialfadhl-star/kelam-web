<?php

namespace App\Services;

use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Support\Facades\Session;

/**
 * Keranjang belanja berbasis session Laravel (server-side).
 * Struktur session 'cart': array keyed by "{variantId}" => [
 *   variant_id, product_id, product_name, slug, color, size, color_hex,
 *   price, quantity, gradient_from, gradient_to
 * ]
 */
class CartService
{
    protected string $key = 'cart';

    public function all(): array
    {
        return Session::get($this->key, []);
    }

    public function add(ProductVariant $variant, int $quantity = 1): void
    {
        $variant->loadMissing('product');
        $product = $variant->product;
        $cart = $this->all();
        $id = (string) $variant->id;

        $existingQty = $cart[$id]['quantity'] ?? 0;
        $newQty = $existingQty + $quantity;

        // Jangan melebihi stok yang tersedia
        $newQty = min($newQty, max(1, $variant->stock));

        $cart[$id] = [
            'variant_id'    => $variant->id,
            'product_id'    => $product->id,
            'product_name'  => $product->name,
            'slug'          => $product->slug,
            'color'         => $variant->color,
            'color_hex'     => $variant->color_hex,
            'size'          => $variant->size,
            'price'         => $product->price,
            'quantity'      => $newQty,
            'gradient_from' => $product->gradient_from,
            'gradient_to'   => $product->gradient_to,
        ];

        Session::put($this->key, $cart);
    }

    public function update(int $variantId, int $quantity): void
    {
        $cart = $this->all();
        $id = (string) $variantId;
        if (! isset($cart[$id])) {
            return;
        }

        if ($quantity <= 0) {
            $this->remove($variantId);
            return;
        }

        // Clamp ke stok terkini
        $variant = ProductVariant::find($variantId);
        if ($variant) {
            $quantity = min($quantity, max(1, $variant->stock));
        }

        $cart[$id]['quantity'] = $quantity;
        Session::put($this->key, $cart);
    }

    public function remove(int $variantId): void
    {
        $cart = $this->all();
        unset($cart[(string) $variantId]);
        Session::put($this->key, $cart);
    }

    public function clear(): void
    {
        Session::forget($this->key);
    }

    public function count(): int
    {
        return array_sum(array_column($this->all(), 'quantity'));
    }

    public function subtotal(): int
    {
        $total = 0;
        foreach ($this->all() as $item) {
            $total += $item['price'] * $item['quantity'];
        }
        return $total;
    }

    public function isEmpty(): bool
    {
        return count($this->all()) === 0;
    }
}
