<?php

namespace App\Livewire\Public\Cart;

use Livewire\Component;
use Livewire\Attributes\On;
use Illuminate\Support\Collection;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Coupon;

class CartPage extends Component
{
    public Collection $items;
    public float $subtotal = 0;
    public float $discountTotal = 0;
    public float $total = 0;
    public string $couponCode = '';
    public ?string $couponMessage = null;
    public bool $couponApplied = false;

    protected $rules = [
        'couponCode' => 'required|string|min:3|max:30',
    ];

    public function mount(): void
    {
        $this->items = collect();
        $this->loadCart();
    }

    #[On('cart-updated')]
    public function loadCart(): void
    {
        $cartToken = session('cart_token');

        if (!$cartToken) {
            $this->items = collect();
            $this->recalculate();
            return;
        }

        $cart = Cart::where('session_token', $cartToken)->first();

        if (!$cart) {
            $this->items = collect();
            $this->recalculate();
            return;
        }

        $this->items = $cart->items()
            ->with(['product', 'variant'])
            ->get();

        if (session('applied_coupon')) {
            $coupon = Coupon::find(session('applied_coupon'));
            if ($coupon) {
                $this->couponApplied = true;
            }
        }

        $this->recalculate();
    }

    public function updateQuantity(int $cartItemId, int $quantity): void
    {
        if ($quantity < 1) {
            $this->removeItem($cartItemId);
            return;
        }

        $item = CartItem::find($cartItemId);

        if (!$item) {
            return;
        }

        if ($item->variant && $item->variant->inventory) {
            $availableStock = $item->variant->inventory->quantity;
            if ($quantity > $availableStock) {
                $this->dispatch('toast', ['type' => 'error', 'message' => 'Stock non sufficiente']);
                return;
            }
        }

        $item->update(['quantity' => $quantity]);
        $this->loadCart();
        $this->dispatch('cart-updated', count: $this->items->count(), total: $this->total);
    }

    public function removeItem(int $cartItemId): void
    {
        $item = CartItem::find($cartItemId);

        if ($item) {
            $item->delete();
            $this->loadCart();
            $this->dispatch('cart-updated', count: $this->items->count(), total: $this->total);
            $this->dispatch('toast', ['type' => 'success', 'message' => 'Prodotto rimosso dal carrello']);
        }
    }

    public function applyCoupon(): void
    {
        $this->validate();

        $coupon = Coupon::where('code', $this->couponCode)->valid()->first();

        if (!$coupon) {
            $this->couponMessage = 'Codice coupon non valido o scaduto';
            $this->couponApplied = false;
            return;
        }

        session(['applied_coupon' => $coupon->id]);
        $this->couponMessage = "Sconto applicato: -{$coupon->discount_amount}€";
        $this->couponApplied = true;

        $this->recalculate();
        $this->dispatch('toast', ['type' => 'success', 'message' => 'Coupon applicato!']);
    }

    public function removeCoupon(): void
    {
        session()->forget('applied_coupon');
        $this->couponCode = '';
        $this->couponMessage = null;
        $this->couponApplied = false;
        $this->recalculate();
        $this->dispatch('toast', ['type' => 'info', 'message' => 'Coupon rimosso']);
    }

    protected function recalculate(): void
    {
        $this->subtotal = $this->items->sum(fn($item) => $item->price * $item->quantity);

        $this->discountTotal = 0;
        if (session('applied_coupon')) {
            $coupon = Coupon::find(session('applied_coupon'));
            if ($coupon) {
                $this->discountTotal = min($coupon->discount_amount, $this->subtotal);
                $this->couponApplied = true;
            }
        }

        $this->total = max(0, $this->subtotal - $this->discountTotal);
    }

    public function render()
    {
        return view('livewire.public.cart.cart-page');
    }
}
