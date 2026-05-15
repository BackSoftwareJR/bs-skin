<?php

declare(strict_types=1);

namespace App\Livewire\Public\Cart;

use App\Services\CartService;
use Livewire\Attributes\On;
use Livewire\Component;

class AddToCartButton extends Component
{
    public int $productId;
    public int $quantity = 1;
    public string $buttonText = 'Aggiungi al carrello';
    public bool $added = false;

    public function mount(
        int $productId,
        int $quantity = 1,
        string $buttonText = 'Aggiungi al carrello',
    ): void {
        $this->productId  = $productId;
        $this->quantity   = $quantity;
        $this->buttonText = $buttonText;
    }

    public function addToCart(CartService $cart): void
    {
        try {
            $cart->add($this->productId, $this->quantity);
            $this->added = true;
            $this->dispatch('cart-updated');
            $this->dispatch('cart-open');
            $this->dispatch('toast', [
                'type'    => 'success',
                'message' => 'Prodotto aggiunto al carrello!',
            ]);
            $this->dispatch('reset-add-to-cart-button')->self()->after(2000);
        } catch (\Throwable) {
            $this->dispatch('toast', [
                'type'    => 'error',
                'message' => 'Errore durante l\'aggiunta al carrello.',
            ]);
        }
    }

    #[On('reset-add-to-cart-button')]
    public function resetState(): void
    {
        $this->added = false;
    }

    public function render()
    {
        return view('livewire.public.cart.add-to-cart-button');
    }
}
