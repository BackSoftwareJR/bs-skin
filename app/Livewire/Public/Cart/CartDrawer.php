<?php

declare(strict_types=1);

namespace App\Livewire\Public\Cart;

use App\Models\Coupon;
use App\Services\CartService;
use Livewire\Attributes\On;
use Livewire\Component;

class CartDrawer extends Component
{
    public bool $isOpen = false;

    /** @var array<int, array<string, mixed>> */
    public array $items = [];

    public float $subtotal      = 0.0;
    public float $discountTotal = 0.0;
    public float $total         = 0.0;
    public int   $count         = 0;

    public string  $couponCode    = '';
    public ?string $couponMessage = null;
    public bool    $couponApplied = false;

    protected array $rules = [
        'couponCode' => 'required|string|min:3|max:30',
    ];

    public function mount(CartService $cart): void
    {
        $this->refreshCart($cart);
    }

    #[On('cart-updated')]
    public function refreshCart(CartService $cart): void
    {
        $this->items = array_values($cart->get());
        $this->count = $cart->count();
        $this->recalculate($cart);
    }

    #[On('cart-open')]
    public function open(CartService $cart): void
    {
        $this->isOpen = true;
        $this->refreshCart($cart);
    }

    public function openDrawer(CartService $cart): void
    {
        $this->isOpen = true;
        $this->refreshCart($cart);
    }

    public function close(): void
    {
        $this->isOpen = false;
    }

    public function removeItem(int $productId, CartService $cart): void
    {
        $cart->remove($productId);
        $this->refreshCart($cart);
        $this->dispatch('cart-updated');
        $this->dispatch('toast', [
            'type'    => 'success',
            'message' => 'Prodotto rimosso dal carrello.',
        ]);
    }

    public function updateQuantity(int $productId, int $qty, CartService $cart): void
    {
        if ($qty < 1) {
            $this->removeItem($productId, $cart);
            return;
        }
        $cart->update($productId, $qty);
        $this->refreshCart($cart);
        $this->dispatch('cart-updated');
    }

    public function clearCart(CartService $cart): void
    {
        $cart->clear();
        $this->refreshCart($cart);
        $this->dispatch('cart-updated');
        $this->dispatch('toast', ['type' => 'info', 'message' => 'Carrello svuotato.']);
    }

    public function applyCoupon(CartService $cart): void
    {
        $this->validate();

        $coupon = Coupon::where('code', $this->couponCode)->valid()->first();

        if (!$coupon) {
            $this->couponMessage = 'Codice coupon non valido o scaduto.';
            $this->couponApplied = false;
            return;
        }

        session(['applied_coupon' => $coupon->id]);
        $this->couponMessage = "Sconto applicato: -{$coupon->discount_amount}€";
        $this->couponApplied = true;

        $this->recalculate($cart);
        $this->dispatch('toast', ['type' => 'success', 'message' => 'Coupon applicato!']);
    }

    public function removeCoupon(CartService $cart): void
    {
        session()->forget('applied_coupon');
        $this->couponCode    = '';
        $this->couponMessage = null;
        $this->couponApplied = false;

        $this->recalculate($cart);
        $this->dispatch('toast', ['type' => 'info', 'message' => 'Coupon rimosso.']);
    }

    public function goToCheckout(): void
    {
        if (empty($this->items)) {
            return;
        }
        $this->redirect('/checkout');
    }

    protected function recalculate(CartService $cart): void
    {
        $this->subtotal = $cart->total();

        $this->discountTotal = 0.0;
        if (session('applied_coupon')) {
            $coupon = Coupon::find(session('applied_coupon'));
            if ($coupon) {
                $this->discountTotal = (float) min($coupon->discount_amount, $this->subtotal);
                $this->couponApplied = true;
            }
        }

        $this->total = max(0.0, $this->subtotal - $this->discountTotal);
    }

    public function render()
    {
        return view('livewire.public.cart.cart-drawer');
    }
}
