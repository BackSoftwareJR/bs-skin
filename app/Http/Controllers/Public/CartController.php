<?php

declare(strict_types=1);

namespace App\Http\Controllers\Public;

use App\Actions\Cart\AddToCartAction;
use App\Actions\Cart\RemoveFromCartAction;
use App\Actions\Cart\UpdateCartItemAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Public\StoreCartItemRequest;
use App\Http\Requests\Public\UpdateCartItemRequest;
use App\Models\Cart;
use App\Models\ProductVariant;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;

class CartController extends Controller
{
    public function store(StoreCartItemRequest $request): JsonResponse|RedirectResponse
    {
        $variant = ProductVariant::findOrFail($request->variant_id);
        $cart = $this->getOrCreateCart();
        
        $cartItem = app(AddToCartAction::class)->execute(
            $cart,
            $variant,
            $request->quantity
        );
        
        if ($request->expectsJson() || $request->hasHeader('X-Livewire')) {
            return response()->json([
                'success' => true,
                'message' => 'Prodotto aggiunto al carrello',
                'cart_items_count' => $cart->items()->count(),
                'cart_total' => $cart->total,
            ]);
        }
        
        return back()->with('success', 'Prodotto aggiunto al carrello');
    }
    
    public function update(UpdateCartItemRequest $request, int $cartItemId): JsonResponse|RedirectResponse
    {
        $cart = $this->getOrCreateCart();
        
        $cartItem = app(UpdateCartItemAction::class)->execute(
            $cart,
            $cartItemId,
            $request->quantity
        );
        
        if ($request->expectsJson() || $request->hasHeader('X-Livewire')) {
            return response()->json([
                'success' => true,
                'message' => 'Carrello aggiornato',
                'cart_items_count' => $cart->items()->count(),
                'cart_total' => $cart->total,
            ]);
        }
        
        return back()->with('success', 'Carrello aggiornato');
    }
    
    public function destroy(int $cartItemId): JsonResponse|RedirectResponse
    {
        $cart = $this->getOrCreateCart();
        
        app(RemoveFromCartAction::class)->execute($cart, $cartItemId);
        
        if (request()->expectsJson() || request()->hasHeader('X-Livewire')) {
            return response()->json([
                'success' => true,
                'message' => 'Prodotto rimosso dal carrello',
                'cart_items_count' => $cart->items()->count(),
                'cart_total' => $cart->total,
            ]);
        }
        
        return back()->with('success', 'Prodotto rimosso dal carrello');
    }
    
    private function getOrCreateCart(): Cart
    {
        // TODO: implementare logica per recuperare o creare carrello
        // basato su session per guest o customer_id per utenti loggati
        $sessionToken = session('cart_token', \Str::random(32));
        session(['cart_token' => $sessionToken]);
        
        return Cart::firstOrCreate([
            'session_token' => $sessionToken,
            'customer_id' => session('skintemple_customer_id'),
        ]);
    }
}