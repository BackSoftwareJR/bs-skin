<?php

declare(strict_types=1);

namespace App\Actions\Cart;

use App\Exceptions\CouponNotApplicableException;
use App\Exceptions\CouponNotFoundException;
use App\Models\Cart;
use App\Models\Coupon;
use App\Models\Customer;
use App\Services\Pricing\CouponEvaluator;

class ApplyCouponAction
{
    public function __construct(
        private CouponEvaluator $couponEvaluator
    ) {}
    
    public function execute(Cart $cart, string $code, ?Customer $customer): Coupon
    {
        $coupon = Coupon::where('code', strtoupper($code))
            ->where('is_active', true)
            ->where('starts_at', '<=', now())
            ->where(function ($query) {
                $query->whereNull('expires_at')
                      ->orWhere('expires_at', '>', now());
            })
            ->first();
            
        if (!$coupon) {
            throw new CouponNotFoundException();
        }
        
        if (!$this->couponEvaluator->isApplicable($coupon, $cart, $customer)) {
            $reason = $this->couponEvaluator->getNotApplicableReason($coupon, $cart, $customer);
            throw new CouponNotApplicableException($reason);
        }
        
        $cart->coupon_id = $coupon->id;
        $cart->save();
        $cart->recalculate();
        
        return $coupon;
    }
}