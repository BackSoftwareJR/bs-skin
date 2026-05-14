<?php

namespace App\Livewire\Public\Cart;

use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Support\Str;

class AddToCartButton extends Component
{
    public int $productId;
    public ?int $variantId = null;
    public int $quantity = 1;
    public string $buttonText = 'Aggiungi al carrello';
    public bool $loading = false;
    public bool $added = false;

    protected $rules = [
        'quantity' => 'required|integer|min:1|max:99',
    ];

    public function mount(int $productId, ?int $variantId = null, string $buttonText = 'Aggiungi al carrello'): void
    {
        $this->productId = $productId;
        $this->variantId = $variantId;
        $this->buttonText = $buttonText;
    }

    public function addToCart(): void
    {
        $this->validate();
        
        $this->loading = true;

        try {
            // Verifica che il prodotto esista
            $product = Product::findOrFail($this->productId);
            
            // Se è specificata una variante, verificala
            $variant = null;
            if ($this->variantId) {
                $variant = ProductVariant::where('product_id', $this->productId)
                    ->where('id', $this->variantId)
                    ->firstOrFail();
            }

            // TODO: delegare a AddToCartAction quando disponibile
            // Per ora implemento la logica inline
            
            // Trova o crea il carrello per la sessione corrente
            $cartToken = session('cart_token');
            if (!$cartToken) {
                $cartToken = Str::uuid()->toString();
                session(['cart_token' => $cartToken]);
            }

            $cart = Cart::firstOrCreate(
                ['session_token' => $cartToken],
                [
                    'customer_id' => auth('customer')->id(),
                    'currency' => 'EUR',
                ]
            );

            // Controlla stock se c'è una variante con inventory
            if ($variant && $variant->inventory) {
                $currentInCart = $cart->items()
                    ->where('product_id', $this->productId)
                    ->where('variant_id', $this->variantId)
                    ->sum('quantity');

                $requestedTotal = $currentInCart + $this->quantity;

                if ($requestedTotal > $variant->inventory->quantity) {
                    $this->dispatch('toast', [
                        'type' => 'error',
                        'message' => 'Stock non sufficiente'
                    ]);
                    return;
                }
            }

            // Trova item esistente o creane uno nuovo
            $cartItem = $cart->items()
                ->where('product_id', $this->productId)
                ->where('variant_id', $this->variantId)
                ->first();

            $price = $variant ? $variant->price : $product->price;

            if ($cartItem) {
                // Aggiorna quantità
                $cartItem->increment('quantity', $this->quantity);
            } else {
                // Crea nuovo item
                CartItem::create([
                    'cart_id' => $cart->id,
                    'product_id' => $this->productId,
                    'variant_id' => $this->variantId,
                    'quantity' => $this->quantity,
                    'price' => $price,
                ]);
            }

            // Emetti eventi
            $this->dispatch('cart-updated');
            $this->dispatch('cart-item-added');
            $this->dispatch('cart-open');

            // Stato success
            $this->added = true;
            $this->dispatch('toast', [
                'type' => 'success',
                'message' => 'Prodotto aggiunto al carrello!'
            ]);

            // Reset dopo 2 secondi
            $this->dispatch('reset-add-to-cart-button')->delay(2000);

        } catch (\Exception $e) {
            $this->dispatch('toast', [
                'type' => 'error',
                'message' => 'Errore durante l\'aggiunta al carrello'
            ]);
        } finally {
            $this->loading = false;
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