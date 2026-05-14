<?php

namespace App\Livewire\Public\Cart;

use Livewire\Component;
use App\Models\Product;
use App\Models\Customer;

class WishlistToggle extends Component
{
    public int $productId;
    public bool $isWishlisted = false;

    protected $listeners = ['customer-logged-in' => 'checkWishlistStatus'];

    public function mount(int $productId): void
    {
        $this->productId = $productId;
        $this->checkWishlistStatus();
    }

    public function toggle(): void
    {
        // Verifica se il customer è loggato
        $customer = auth('customer')->user();
        
        if (!$customer) {
            $this->dispatch('toast', [
                'type' => 'info',
                'message' => 'Effettua l\'accesso per aggiungere prodotti alla wishlist'
            ]);
            
            // Redirect alla pagina login
            return $this->redirect('/account/login');
        }

        $product = Product::find($this->productId);
        
        if (!$product) {
            return;
        }

        // TODO: delegare a ToggleWishlistAction quando disponibile
        // Per ora uso una soluzione semplice con colonna JSON su customer
        
        $wishlist = $customer->wishlist ?? [];
        
        if ($this->isWishlisted) {
            // Rimuovi dalla wishlist
            $wishlist = array_diff($wishlist, [$this->productId]);
            $this->isWishlisted = false;
            
            $this->dispatch('toast', [
                'type' => 'info',
                'message' => 'Prodotto rimosso dalla lista desideri'
            ]);
        } else {
            // Aggiungi alla wishlist
            if (!in_array($this->productId, $wishlist)) {
                $wishlist[] = $this->productId;
            }
            $this->isWishlisted = true;
            
            $this->dispatch('toast', [
                'type' => 'success',
                'message' => 'Prodotto aggiunto alla lista desideri'
            ]);
        }

        $customer->update(['wishlist' => array_values($wishlist)]);
        
        $this->dispatch('wishlist-updated');
    }

    public function checkWishlistStatus(): void
    {
        $customer = auth('customer')->user();
        
        if (!$customer) {
            $this->isWishlisted = false;
            return;
        }

        $wishlist = $customer->wishlist ?? [];
        $this->isWishlisted = in_array($this->productId, $wishlist);
    }

    public function render()
    {
        return view('livewire.public.cart.wishlist-toggle');
    }
}