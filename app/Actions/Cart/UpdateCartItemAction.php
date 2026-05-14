<?php

declare(strict_types=1);

namespace App\Actions\Cart;

use App\Models\Cart;
use App\Models\CartItem;

class UpdateCartItemAction
{
    public function execute(Cart $cart, int $cartItemId, int $quantity): CartItem
    {
        if ($quantity <= 0) {
            app(RemoveFromCartAction::class)->execute($cart, $cartItemId);
            // Ritorna un CartItem dummy per mantenere la signature
            return new CartItem();
        }
        
        $cartItem = $cart->items()->findOrFail($cartItemId);
        $cartItem->quantity = $quantity;
        $cartItem->subtotal_snapshot = $quantity * $cartItem->unit_price_snapshot;
        $cartItem->save();
        
        $cart->recalculate();
        
        return $cartItem;
    }
}