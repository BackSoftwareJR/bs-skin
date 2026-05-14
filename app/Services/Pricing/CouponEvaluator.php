<?php

declare(strict_types=1);

namespace App\Services\Pricing;

use App\Models\Cart;
use App\Models\Coupon;
use App\Models\Customer;

class CouponEvaluator
{
    public function isApplicable(Coupon $coupon, Cart $cart, ?Customer $customer): bool
    {
        // Verifica scadenza
        if ($coupon->expires_at && $coupon->expires_at->isPast()) {
            return false;
        }
        
        // Verifica data inizio
        if ($coupon->starts_at && $coupon->starts_at->isFuture()) {
            return false;
        }
        
        // Verifica soglia minima
        if ($coupon->min_order_amount && $cart->subtotal < $coupon->min_order_amount) {
            return false;
        }
        
        // Verifica limite utilizzi globale
        if ($coupon->usage_limit && $coupon->usage_count >= $coupon->usage_limit) {
            return false;
        }
        
        // Verifica limite utilizzi per customer
        if ($customer && $coupon->usage_limit_per_customer) {
            $customerUsage = $coupon->redemptions()
                ->where('customer_id', $customer->id)
                ->count();
                
            if ($customerUsage >= $coupon->usage_limit_per_customer) {
                return false;
            }
        }
        
        // Verifica applies_to
        return match ($coupon->applies_to) {
            'all' => true,
            'category' => $this->appliesForCategories($coupon, $cart),
            'brand' => $this->appliesForBrands($coupon, $cart),
            'product' => $this->appliesForProducts($coupon, $cart),
            'customer' => $this->appliesForCustomer($coupon, $customer),
            'first_order' => $this->appliesForFirstOrder($customer),
            default => false,
        };
    }
    
    public function calculateDiscount(Coupon $coupon, Cart $cart): float
    {
        $discountAmount = match ($coupon->type) {
            'percentage' => ($coupon->value * $cart->subtotal) / 100,
            'fixed' => min($coupon->value, $cart->subtotal),
            default => 0,
        };
        
        // Applica max_discount se presente per coupon percentage
        if ($coupon->type === 'percentage' && $coupon->max_discount) {
            $discountAmount = min($discountAmount, $coupon->max_discount);
        }
        
        return round($discountAmount, 2);
    }
    
    public function getNotApplicableReason(Coupon $coupon, Cart $cart, ?Customer $customer): string
    {
        if ($coupon->expires_at && $coupon->expires_at->isPast()) {
            return 'Il coupon è scaduto.';
        }
        
        if ($coupon->starts_at && $coupon->starts_at->isFuture()) {
            return 'Il coupon non è ancora attivo.';
        }
        
        if ($coupon->min_order_amount && $cart->subtotal < $coupon->min_order_amount) {
            return sprintf('Importo minimo non raggiunto: €%.2f', $coupon->min_order_amount);
        }
        
        if ($coupon->usage_limit && $coupon->usage_count >= $coupon->usage_limit) {
            return 'Coupon esaurito.';
        }
        
        if ($customer && $coupon->usage_limit_per_customer) {
            $customerUsage = $coupon->redemptions()
                ->where('customer_id', $customer->id)
                ->count();
                
            if ($customerUsage >= $coupon->usage_limit_per_customer) {
                return 'Coupon già utilizzato.';
            }
        }
        
        return match ($coupon->applies_to) {
            'category' => 'Coupon non applicabile a questi prodotti.',
            'brand' => 'Coupon non applicabile a questi brand.',
            'product' => 'Coupon non applicabile a questi prodotti.',
            'customer' => 'Coupon non disponibile per il tuo account.',
            'first_order' => 'Coupon valido solo per il primo ordine.',
            default => 'Coupon non applicabile.',
        };
    }
    
    private function appliesForCategories(Coupon $coupon, Cart $cart): bool
    {
        if (empty($coupon->applicable_ids)) {
            return false;
        }
        
        foreach ($cart->items as $item) {
            $productCategoryIds = $item->productVariant->product->categories->pluck('id')->toArray();
            
            if (array_intersect($coupon->applicable_ids, $productCategoryIds)) {
                return true;
            }
        }
        
        return false;
    }
    
    private function appliesForBrands(Coupon $coupon, Cart $cart): bool
    {
        if (empty($coupon->applicable_ids)) {
            return false;
        }
        
        foreach ($cart->items as $item) {
            $brandId = $item->productVariant->product->brand_id;
            
            if (in_array($brandId, $coupon->applicable_ids)) {
                return true;
            }
        }
        
        return false;
    }
    
    private function appliesForProducts(Coupon $coupon, Cart $cart): bool
    {
        if (empty($coupon->applicable_ids)) {
            return false;
        }
        
        foreach ($cart->items as $item) {
            $productId = $item->productVariant->product->id;
            
            if (in_array($productId, $coupon->applicable_ids)) {
                return true;
            }
        }
        
        return false;
    }
    
    private function appliesForCustomer(Coupon $coupon, ?Customer $customer): bool
    {
        if (!$customer) {
            return false;
        }
        
        return $coupon->customer_id === $customer->id;
    }
    
    private function appliesForFirstOrder(?Customer $customer): bool
    {
        if (!$customer) {
            return true; // Guest checkout considera come primo ordine
        }
        
        return $customer->total_orders === 0;
    }
}