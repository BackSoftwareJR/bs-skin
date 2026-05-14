<?php

declare(strict_types=1);

namespace App\Http\Controllers\Public;

use App\Actions\Checkout\PlaceOrderAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Public\PlaceOrderRequest;
use App\Models\Cart;
use App\Models\Customer;
use Illuminate\Http\RedirectResponse;

class CheckoutController extends Controller
{
    public function store(PlaceOrderRequest $request): RedirectResponse
    {
        $cart = $this->getCart();
        $customer = $this->getCurrentCustomer();
        
        $addresses = [
            'shipping' => [
                'first_name' => $request->shipping_first_name,
                'last_name' => $request->shipping_last_name,
                'email' => $request->shipping_email ?? $customer?->email,
                'phone' => $request->shipping_phone,
                'company' => $request->shipping_company,
                'address' => $request->shipping_address,
                'city' => $request->shipping_city,
                'postal_code' => $request->shipping_postal_code,
                'province' => $request->shipping_province,
                'country' => $request->shipping_country ?? 'IT',
            ],
            'billing' => $request->same_billing_as_shipping 
                ? $request->only(['shipping_first_name', 'shipping_last_name', 'shipping_email', 'shipping_phone', 'shipping_company', 'shipping_address', 'shipping_city', 'shipping_postal_code', 'shipping_province', 'shipping_country'])
                : [
                    'first_name' => $request->billing_first_name,
                    'last_name' => $request->billing_last_name,
                    'email' => $request->billing_email ?? $customer?->email,
                    'phone' => $request->billing_phone,
                    'company' => $request->billing_company,
                    'address' => $request->billing_address,
                    'city' => $request->billing_city,
                    'postal_code' => $request->billing_postal_code,
                    'province' => $request->billing_province,
                    'country' => $request->billing_country ?? 'IT',
                ],
            'notes' => $request->notes,
        ];
        
        $order = app(PlaceOrderAction::class)->execute(
            $cart,
            $customer,
            $addresses,
            $request->payment_method
        );
        
        return redirect()
            ->route('public.orders.confirmation', $order->order_number)
            ->with('success', 'Ordine creato con successo');
    }
    
    private function getCart(): Cart
    {
        $sessionToken = session('cart_token');
        
        return Cart::where('session_token', $sessionToken)
            ->orWhere('customer_id', session('skintemple_customer_id'))
            ->with(['items.productVariant.product', 'coupon'])
            ->firstOrFail();
    }
    
    private function getCurrentCustomer(): ?Customer
    {
        $customerId = session('skintemple_customer_id');
        
        return $customerId ? Customer::find($customerId) : null;
    }
}