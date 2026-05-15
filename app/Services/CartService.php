<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Product;

class CartService
{
    private const SESSION_KEY = 'skintemple_cart';

    /**
     * Ritorna tutti gli item del carrello.
     * Struttura item: ['product_id', 'name', 'price', 'quantity', 'slug', 'image', 'sku']
     *
     * @return array<int, array<string, mixed>>
     */
    public function get(): array
    {
        return session(self::SESSION_KEY, []);
    }

    /**
     * Aggiunge un prodotto al carrello. Se esiste già, incrementa la quantità.
     */
    public function add(int $productId, int $quantity = 1): void
    {
        $product = Product::find($productId);

        if (!$product) {
            return;
        }

        $cart = $this->get();

        if (isset($cart[$productId])) {
            $cart[$productId]['quantity'] += $quantity;
        } else {
            $cart[$productId] = [
                'product_id' => $productId,
                'name'       => $product->getTranslation('name', app()->getLocale()),
                'price'      => (float) $product->price,
                'quantity'   => $quantity,
                'slug'       => $product->slug,
                'image'      => $product->getFirstMediaUrl('gallery', 'thumb'),
                'sku'        => $product->sku ?? '',
            ];
        }

        session([self::SESSION_KEY => $cart]);
    }

    /**
     * Aggiorna la quantità di un prodotto nel carrello.
     * Se la quantità è ≤ 0 rimuove l'item.
     */
    public function update(int $productId, int $quantity): void
    {
        if ($quantity <= 0) {
            $this->remove($productId);
            return;
        }

        $cart = $this->get();

        if (!isset($cart[$productId])) {
            return;
        }

        $cart[$productId]['quantity'] = $quantity;
        session([self::SESSION_KEY => $cart]);
    }

    /**
     * Rimuove un prodotto dal carrello.
     */
    public function remove(int $productId): void
    {
        $cart = $this->get();
        unset($cart[$productId]);
        session([self::SESSION_KEY => $cart]);
    }

    /**
     * Svuota completamente il carrello.
     */
    public function clear(): void
    {
        session([self::SESSION_KEY => []]);
    }

    /**
     * Ritorna il totale (somma price × quantity) senza sconti.
     */
    public function total(): float
    {
        return (float) array_sum(
            array_map(
                fn(array $item): float => $item['price'] * $item['quantity'],
                $this->get()
            )
        );
    }

    /**
     * Ritorna la quantità totale di articoli nel carrello.
     */
    public function count(): int
    {
        return (int) array_sum(array_column($this->get(), 'quantity'));
    }

    /**
     * Controlla se il carrello è vuoto.
     */
    public function isEmpty(): bool
    {
        return empty($this->get());
    }
}
