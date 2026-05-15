<?php

declare(strict_types=1);

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Services\CartService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function store(Request $request, CartService $cart): RedirectResponse
    {
        $request->validate([
            'product_id' => 'required|integer|min:1',
            'quantity'   => 'integer|min:1|max:99',
        ]);

        $cart->add((int) $request->product_id, (int) ($request->quantity ?? 1));

        return back()->with('success', 'Prodotto aggiunto al carrello');
    }

    public function update(Request $request, int $cartItemId, CartService $cart): RedirectResponse
    {
        $request->validate([
            'quantity' => 'required|integer|min:0|max:99',
        ]);

        $cart->update($cartItemId, (int) $request->quantity);

        return back();
    }

    public function destroy(int $cartItemId, CartService $cart): RedirectResponse
    {
        $cart->remove($cartItemId);

        return back()->with('success', 'Prodotto rimosso dal carrello');
    }
}
