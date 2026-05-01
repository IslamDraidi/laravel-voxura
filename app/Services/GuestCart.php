<?php

namespace App\Services;

use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Support\Collection;

/**
 * Session-backed cart for unauthenticated guests.
 * Mirrors the ShoppingCart / CartItem interface used in views.
 */
class GuestCart
{
    const SESSION_KEY = 'guest_cart';

    public Collection $items;

    public function __construct()
    {
        $this->items = $this->loadItems();
    }

    public function total(): float
    {
        return $this->items->sum(fn ($item) => $item->subtotal());
    }

    public function itemCount(): int
    {
        return (int) $this->items->sum('quantity');
    }

    // ── Mutations ─────────────────────────────────────────────────

    public function add(int $productId, ?int $variantId, int $qty): GuestCartItem
    {
        $rows = session(self::SESSION_KEY, []);
        $found = false;

        foreach ($rows as &$row) {
            if ($row['product_id'] === $productId && $row['variant_id'] === $variantId) {
                $row['quantity'] += $qty;
                $found = true;
                break;
            }
        }
        unset($row);

        if (! $found) {
            $rows[] = ['product_id' => $productId, 'variant_id' => $variantId, 'quantity' => $qty];
        }

        session([self::SESSION_KEY => $rows]);
        $this->items = $this->loadItems();

        return $this->items->first(fn ($i) => $i->product_id === $productId && $i->variant_id === $variantId);
    }

    public function updateQuantity(string $sessionKey, int $qty): void
    {
        $rows = session(self::SESSION_KEY, []);
        foreach ($rows as &$row) {
            if ($this->rowKey($row) === $sessionKey) {
                $row['quantity'] = $qty;
                break;
            }
        }
        unset($row);
        session([self::SESSION_KEY => $rows]);
        $this->items = $this->loadItems();
    }

    public function removeByKey(string $sessionKey): void
    {
        $rows = session(self::SESSION_KEY, []);
        $rows = array_values(array_filter($rows, fn ($r) => $this->rowKey($r) !== $sessionKey));
        session([self::SESSION_KEY => $rows]);
        $this->items = $this->loadItems();
    }

    public function clear(): void
    {
        session()->forget(self::SESSION_KEY);
        $this->items = collect();
    }

    public function isEmpty(): bool
    {
        return $this->items->isEmpty();
    }

    // ── Helpers ───────────────────────────────────────────────────

    private function loadItems(): Collection
    {
        $rows = session(self::SESSION_KEY, []);
        if (empty($rows)) {
            return collect();
        }

        $productIds = array_unique(array_column($rows, 'product_id'));
        $variantIds = array_filter(array_unique(array_column($rows, 'variant_id')));

        $products = Product::with('category')->whereIn('id', $productIds)->get()->keyBy('id');
        $variants = $variantIds ? ProductVariant::whereIn('id', $variantIds)->get()->keyBy('id') : collect();

        $items = collect();
        foreach ($rows as $row) {
            $product = $products->get($row['product_id']);
            if (! $product) {
                continue;
            }
            $variant = $row['variant_id'] ? $variants->get($row['variant_id']) : null;
            $items->push(new GuestCartItem(
                id: $this->rowKey($row),
                product: $product,
                variant: $variant,
                quantity: (int) $row['quantity'],
            ));
        }

        return $items;
    }

    private function rowKey(array $row): string
    {
        return $row['product_id'] . '_' . ($row['variant_id'] ?? '0');
    }
}
