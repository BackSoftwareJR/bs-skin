<?php

namespace App\Livewire\Public\Checkout;

use Livewire\Component;
use App\Models\Cart;
use App\Models\Order;
use App\Models\CustomerAddress;
use App\Models\ShippingMethod;
use Illuminate\Support\Collection;

class CheckoutFlow extends Component
{
    public string $step = 'address'; // address, shipping, payment, review, confirmation
    public array $steps = ['address', 'shipping', 'payment', 'review', 'confirmation'];
    public int $currentStepIndex = 0;

    // Cart data
    public ?Cart $cart = null;
    public Collection $cartItems;
    public float $subtotal = 0;
    public float $shippingCost = 0;
    public float $discount = 0;
    public float $total = 0;

    // Address step
    public ?int $selectedAddressId = null;
    public bool $useNewAddress = false;
    public bool $sameAddressForBilling = true;
    public array $shippingAddress = [];
    public array $billingAddress = [];

    // Shipping step
    public ?int $selectedShippingMethodId = null;
    public Collection $availableShippingMethods;

    // Payment step
    public string $selectedPaymentMethod = '';
    public array $availablePaymentMethods = ['bank_transfer', 'stripe', 'paypal'];

    // Review step
    public bool $termsAccepted = false;

    // Confirmation step
    public ?Order $completedOrder = null;

    protected $rules = [
        'selectedAddressId' => 'required_unless:useNewAddress,true|nullable|integer',
        'selectedShippingMethodId' => 'required_if:step,shipping,payment,review|nullable|integer',
        'selectedPaymentMethod' => 'required_if:step,payment,review|string|in:bank_transfer,stripe,paypal',
        'termsAccepted' => 'required_if:step,review|accepted',
    ];

    public function mount(): void
    {
        // Carica cart dalla sessione
        $cartToken = session('cart_token');
        if (!$cartToken) {
            return redirect('/shop');
        }

        $this->cart = Cart::where('session_token', $cartToken)->with('items.product', 'items.variant')->first();
        
        if (!$this->cart || $this->cart->items->isEmpty()) {
            return redirect('/shop');
        }

        $this->cartItems = $this->cart->items;
        $this->calculateTotals();
        
        $this->currentStepIndex = array_search($this->step, $this->steps);
        $this->availableShippingMethods = collect();
    }

    public function nextStep(): void
    {
        $this->validateCurrentStep();

        if ($this->currentStepIndex < count($this->steps) - 1) {
            $this->currentStepIndex++;
            $this->step = $this->steps[$this->currentStepIndex];
            
            $this->handleStepTransition();
        }
    }

    public function previousStep(): void
    {
        if ($this->currentStepIndex > 0) {
            $this->currentStepIndex--;
            $this->step = $this->steps[$this->currentStepIndex];
        }
    }

    public function goToStep(string $step): void
    {
        if (in_array($step, $this->steps)) {
            $this->step = $step;
            $this->currentStepIndex = array_search($step, $this->steps);
        }
    }

    public function selectAddress(int $addressId): void
    {
        $this->selectedAddressId = $addressId;
        $this->useNewAddress = false;
        
        // Carica dati indirizzo per shipping cost calculation
        $address = CustomerAddress::find($addressId);
        if ($address) {
            $this->shippingAddress = $address->toArray();
        }
    }

    public function toggleNewAddress(): void
    {
        $this->useNewAddress = !$this->useNewAddress;
        if ($this->useNewAddress) {
            $this->selectedAddressId = null;
        }
    }

    public function selectShippingMethod(int $methodId): void
    {
        $this->selectedShippingMethodId = $methodId;
        
        $method = ShippingMethod::find($methodId);
        if ($method) {
            $this->shippingCost = $method->price;
            $this->calculateTotals();
        }
    }

    public function selectPaymentMethod(string $method): void
    {
        $this->selectedPaymentMethod = $method;
    }

    public function placeOrder(): void
    {
        $this->validate();

        try {
            // TODO: delegare a PlaceOrderAction quando disponibile
            // Per ora implemento la logica base inline
            
            \DB::transaction(function () {
                // Crea ordine
                $this->completedOrder = Order::create([
                    'customer_id' => auth('customer')->id(),
                    'order_number' => $this->generateOrderNumber(),
                    'status' => 'pending',
                    'payment_status' => 'pending',
                    'payment_method' => $this->selectedPaymentMethod,
                    'currency' => 'EUR',
                    'subtotal' => $this->subtotal,
                    'shipping_cost' => $this->shippingCost,
                    'discount_total' => $this->discount,
                    'total' => $this->total,
                    'notes' => null,
                ]);

                // Crea order items dalle cart items
                foreach ($this->cartItems as $cartItem) {
                    $this->completedOrder->items()->create([
                        'product_id' => $cartItem->product_id,
                        'variant_id' => $cartItem->variant_id,
                        'quantity' => $cartItem->quantity,
                        'price' => $cartItem->price,
                        'product_name' => $cartItem->product->name,
                        'variant_name' => $cartItem->variant ? $cartItem->variant->name : null,
                    ]);
                }

                // Svuota carrello
                $this->cart->items()->delete();

                // TODO: invia email conferma ordine
                // dispatch(new SendOrderConfirmationEmail($this->completedOrder));
            });

            $this->step = 'confirmation';
            $this->currentStepIndex = array_search('confirmation', $this->steps);

            session()->forget('cart_token');

            $this->dispatch('toast', [
                'type' => 'success',
                'message' => 'Ordine completato con successo!'
            ]);

        } catch (\Exception $e) {
            logger('Checkout error: ' . $e->getMessage());
            
            $this->dispatch('toast', [
                'type' => 'error',
                'message' => 'Errore durante il completamento dell\'ordine. Riprova.'
            ]);
        }
    }

    protected function validateCurrentStep(): void
    {
        switch ($this->step) {
            case 'address':
                if (!$this->useNewAddress && !$this->selectedAddressId) {
                    throw new \Exception('Seleziona un indirizzo');
                }
                break;
            case 'shipping':
                if (!$this->selectedShippingMethodId) {
                    throw new \Exception('Seleziona un metodo di spedizione');
                }
                break;
            case 'payment':
                if (!$this->selectedPaymentMethod) {
                    throw new \Exception('Seleziona un metodo di pagamento');
                }
                break;
            case 'review':
                if (!$this->termsAccepted) {
                    throw new \Exception('Devi accettare i termini e condizioni');
                }
                break;
        }
    }

    protected function handleStepTransition(): void
    {
        switch ($this->step) {
            case 'shipping':
                $this->loadShippingMethods();
                break;
        }
    }

    protected function loadShippingMethods(): void
    {
        // Metodi di spedizione base
        $this->availableShippingMethods = collect([
            (object) [
                'id' => 1,
                'name' => 'Spedizione Standard',
                'description' => '3-5 giorni lavorativi',
                'price' => $this->subtotal >= 99 ? 0 : 7.90,
            ],
            (object) [
                'id' => 2,
                'name' => 'Spedizione Express',
                'description' => '1-2 giorni lavorativi',
                'price' => 14.90,
            ],
        ]);

        // Auto-select first if none selected
        if (!$this->selectedShippingMethodId) {
            $this->selectShippingMethod($this->availableShippingMethods->first()->id);
        }
    }

    protected function calculateTotals(): void
    {
        $this->subtotal = $this->cartItems->sum(function ($item) {
            return $item->price * $item->quantity;
        });

        // Apply discount if any coupon in session
        $this->discount = 0;
        // TODO: calcola discount da coupon

        $this->total = $this->subtotal + $this->shippingCost - $this->discount;
    }

    protected function generateOrderNumber(): string
    {
        return 'ST-' . date('Y') . '-' . str_pad(Order::count() + 1, 6, '0', STR_PAD_LEFT);
    }

    public function render()
    {
        return view('livewire.public.checkout.checkout-flow');
    }
}