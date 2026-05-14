<?php

declare(strict_types=1);

namespace App\Actions\Cart;

use App\Models\Cart;

class RemoveCouponAction
{
    public function execute(Cart $cart): void
    {
        $cart->coupon_id = null;
        $cart->save();
        $cart->recalculate();
    }
}