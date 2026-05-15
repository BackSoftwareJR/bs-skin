<?php

declare(strict_types=1);

namespace App\Livewire\Public\Cart;

use App\Models\Coupon;
use App\Services\CartService;
use Livewire\Attributes\On;
use Livewire\Component;

class CartPage extends Component
{
    /** @var array<int, array<string, mixed>> */
    public array $items = [];

    public float $subtotal      = 0.0;
    public float $discountTotal = 0.0;
    public float $total         = 0.0;
    public int   $count         = 0;

    public string  $couponCode    = '';
    public ?string $couponMessage = null;
    public bool    $couponApplied = false;

    /** Shipping threshold */
    public const FREE_SHIPPING_THRESHOLD = 99.0;
    public const SHIPPING_COST           = 5.90;

    protected CartService $cartService;

    protected array $rules = [
        'couponCode' => 'required|string|min:3|max:30',
    ];

    public function boot(CartService $cartService): void
    {
        $this->cartService = $cartService;
    }

    public function mount(): void
    {
        $this->loadCart();
    }

    #[On('cart-updated')]
    public function loadCart(): void
    {
        $this->items = array_values($this->cartService->get());
        $this->count = $this->cartService->count();

        if (session('applied_coupon')) {
            $coupon = Coupon::find(session('applied_coupon'));
            if ($coupon) {
                $this->couponApplied = true;
            }
        }

        $this->recalculate();
    }

    public function updateQuantity(int $productId, int $quantity): void
    {
        if ($quantity < 1) {
            $this->removeItem($productId);
            return;
        }

        $this->cartService->update($productId, $quantity);
        $this->loadCart();
        $this->dispatch('cart-updated');
    }

    public function removeItem(int $productId): void
    {
        $this->cartService->remove($productId);
        $this->loadCart();
        $this->dispatch('cart-updated');
        $this->dispatch('toast', ['type' => 'success', 'message' => 'Prodotto rimosso dal carrello.']);
    }

    public function applyCoupon(string $code = ''): void
    {
        $this->couponCode = $code ?: $this->couponCode;
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

        $this->recalculate();
        $this->dispatch('toast', ['type' => 'success', 'message' => 'Coupon applicato!']);
    }

    public function removeCoupon(): void
    {
        session()->forget('applied_coupon');
        $this->couponCode    = '';
        $this->couponMessage = null;
        $this->couponApplied = false;

        $this->recalculate();
        $this->dispatch('toast', ['type' => 'info', 'message' => 'Coupon rimosso.']);
    }

    protected function recalculate(): void
    {
        $this->subtotal = $this->cartService->total();

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
        $shipping = ($this->subtotal >= self::FREE_SHIPPING_THRESHOLD || empty($this->items))
            ? 0.0
            : self::SHIPPING_COST;

        $grandTotal = $this->total + $shipping;

        return view('livewire.public.cart.cart-page', compact('shipping', 'grandTotal'));
    }
}
