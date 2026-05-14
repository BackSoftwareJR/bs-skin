<?php

declare(strict_types=1);

namespace App\Actions\Cart;

use App\Exceptions\OutOfStockException;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\ProductVariant;

class AddToCartAction
{
    public function execute(Cart $cart, ProductVariant $variant, int $quantity): CartItem
    {
        // Verifica stock disponibile (soft check - senza lock)
        $inventory = $variant->inventory;
        if ($inventory && $inventory->quantity <= 0 && !$inventory->allow_backorder) {
            throw new OutOfStockException($variant->product->name);
        }
        
        // Verifica se variant già in carrello
        $existingItem = $cart->items()
            ->where('product_variant_id', $variant->id)
            ->first();
            
        if ($existingItem) {
            $existingItem->quantity += $quantity;
            $existingItem->subtotal_snapshot = $existingItem->quantity * $existingItem->unit_price_snapshot;
            $existingItem->save();
            $cartItem = $existingItem;
        } else {
            // Prezzo snapshot al momento dell'aggiunta
            $unitPrice = $variant->price_override ?? $variant->product->price;
            
            $cartItem = CartItem::create([
                'cart_id' => $cart->id,
                'product_variant_id' => $variant->id,
                'quantity' => $quantity,
                'unit_price_snapshot' => $unitPrice,
                'subtotal_snapshot' => $unitPrice * $quantity,
            ]);
        }
        
        $cart->recalculate();
        
        return $cartItem;
    }
}