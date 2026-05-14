<?php

namespace App\Observers;

use App\Models\Cart;

class CartObserver
{
    /**
     * Solo cleanup lazy: nessuna azione automatica su save/delete
     * Il cleanup avviene nel CartService al caricamento del carrello utente
     */
    public function retrieved(Cart $cart): void
    {
        // Se carrello > 30gg e non associato a customer, auto-delete
        if (!$cart->customer_id && $cart->updated_at?->diffInDays(now()) > 30) {
            $cart->items()->delete();
            $cart->delete();
        }
    }
}