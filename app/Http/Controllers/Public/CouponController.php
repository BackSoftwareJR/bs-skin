<?php

declare(strict_types=1);

namespace App\Http\Controllers\Public;

use App\Actions\Cart\ApplyCouponAction;
use App\Actions\Cart\RemoveCouponAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Public\ApplyCouponRequest;
use App\Models\Cart;
use App\Models\Customer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    public function store(ApplyCouponRequest $request): JsonResponse|RedirectResponse
    {
        $cart = $this->getCart();
        $customer = $this->getCurrentCustomer();
        
        $coupon = app(ApplyCouponAction::class)->execute(
            $cart,
            $request->code,
            $customer
        );
        
        if ($request->expectsJson() || $request->hasHeader('X-Livewire')) {
            return response()->json([
                'success' => true,
                'message' => 'Coupon applicato con successo',
                'coupon_code' => $coupon->code,
                'discount_amount' => $cart->discount_total,
                'cart_total' => $cart->total,
            ]);
        }
        
        return back()->with('success', 'Coupon applicato con successo');
    }
    
    public function destroy(Request $request): JsonResponse|RedirectResponse
    {
        $cart = $this->getCart();
        
        app(RemoveCouponAction::class)->execute($cart);
        
        if ($request->expectsJson() || $request->hasHeader('X-Livewire')) {
            return response()->json([
                'success' => true,
                'message' => 'Coupon rimosso',
                'cart_total' => $cart->total,
            ]);
        }
        
        return back()->with('success', 'Coupon rimosso');
    }
    
    private function getCart(): Cart
    {
        $sessionToken = session('cart_token');
        
        return Cart::where('session_token', $sessionToken)
            ->orWhere('customer_id', session('skintemple_customer_id'))
            ->firstOrFail();
    }
    
    private function getCurrentCustomer(): ?Customer
    {
        $customerId = session('skintemple_customer_id');
        
        return $customerId ? Customer::find($customerId) : null;
    }
}