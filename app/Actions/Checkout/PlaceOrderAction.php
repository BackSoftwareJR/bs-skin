<?php

declare(strict_types=1);

namespace App\Actions\Checkout;

use App\Events\OrderPlaced;
use App\Exceptions\InsufficientStockException;
use App\Models\Cart;
use App\Models\Customer;
use App\Models\Inventory;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\StockMovement;
use App\Models\CouponRedemption;
use Illuminate\Support\Facades\DB;

class PlaceOrderAction
{
    public function execute(Cart $cart, ?Customer $customer, array $addresses, string $paymentMethod): Order
    {
        return DB::transaction(function () use ($cart, $customer, $addresses, $paymentMethod) {
            // 1. Lock inventory per ogni item
            foreach ($cart->items as $item) {
                $inventory = Inventory::where('product_variant_id', $item->product_variant_id)
                    ->lockForUpdate()
                    ->first();
                    
                if ($inventory && $inventory->quantity <= 0 && !$inventory->allow_backorder) {
                    throw new InsufficientStockException($item->productVariant->product->name);
                }
                
                if ($inventory && $inventory->quantity < $item->quantity && !$inventory->allow_backorder) {
                    throw new InsufficientStockException($item->productVariant->product->name);
                }
            }
            
            // 2. Genera numero ordine progressivo
            $year = now()->year;
            $lastOrder = Order::where('order_number', 'like', "SK-{$year}-%")
                ->orderBy('order_number', 'desc')
                ->first();
                
            $sequence = 1;
            if ($lastOrder) {
                $parts = explode('-', $lastOrder->order_number);
                $sequence = ((int) end($parts)) + 1;
            }
            
            $orderNumber = sprintf('SK-%d-%05d', $year, $sequence);
            
            // 3. Crea ordine
            $order = Order::create([
                'order_number' => $orderNumber,
                'customer_id' => $customer?->id,
                'guest_email' => $customer ? null : $addresses['billing']['email'],
                'status' => 'pending',
                'payment_status' => 'pending',
                'invoice_status' => 'none',
                'subtotal' => $cart->subtotal,
                'discount_total' => $cart->discount_total,
                'tax_total' => $cart->tax_total,
                'shipping_total' => 0, // TODO: calcolare spedizione
                'total' => $cart->total,
                'currency' => 'EUR',
                'coupon_id' => $cart->coupon_id,
                'shipping_address_json' => $addresses['shipping'],
                'billing_address_json' => $addresses['billing'],
                'notes' => $addresses['notes'] ?? null,
                'payment_metadata' => [],
            ]);
            
            // 4. Crea order items
            foreach ($cart->items as $item) {
                $product = $item->productVariant->product;
                
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'product_variant_id' => $item->product_variant_id,
                    'quantity' => $item->quantity,
                    'unit_price' => $item->unit_price_snapshot,
                    'tax_rate' => $product->tax_rate ?? 22.00,
                    'tax_amount' => $item->subtotal_snapshot * (($product->tax_rate ?? 22.00) / 100),
                    'discount_amount' => 0,
                    'subtotal' => $item->subtotal_snapshot,
                    'total' => $item->subtotal_snapshot,
                    'product_snapshot_json' => [
                        'name' => $product->name,
                        'sku' => $item->productVariant->sku ?? $product->sku,
                        'image' => $product->getFirstMediaUrl('gallery'),
                    ],
                ]);
            }
            
            // 5. Decrementa inventory e crea stock movements
            foreach ($cart->items as $item) {
                $inventory = Inventory::where('product_variant_id', $item->product_variant_id)->first();
                
                if ($inventory) {
                    $inventory->quantity -= $item->quantity;
                    $inventory->last_movement_at = now();
                    $inventory->save();
                    
                    StockMovement::create([
                        'inventory_id' => $inventory->id,
                        'type' => 'sale',
                        'quantity_change' => -$item->quantity,
                        'quantity_before' => $inventory->quantity + $item->quantity,
                        'quantity_after' => $inventory->quantity,
                        'reference_type' => Order::class,
                        'reference_id' => $order->id,
                        'notes' => "Vendita ordine {$order->order_number}",
                        'performed_by_user_id' => null,
                    ]);
                }
            }
            
            // 6. Crea payment
            Payment::create([
                'order_id' => $order->id,
                'provider' => $paymentMethod,
                'method' => $paymentMethod,
                'status' => 'pending',
                'amount' => $order->total,
                'currency' => 'EUR',
                'gateway_transaction_id' => null,
                'metadata_json' => [],
                'webhook_payload_json' => [],
            ]);
            
            // 7. Gestisci coupon redemption
            if ($cart->coupon_id) {
                $coupon = $cart->coupon;
                
                CouponRedemption::create([
                    'coupon_id' => $coupon->id,
                    'customer_id' => $customer?->id,
                    'order_id' => $order->id,
                    'discount_amount' => $cart->discount_total,
                    'redeemed_at' => now(),
                ]);
                
                $coupon->increment('usage_count');
            }
            
            // 8. Aggiorna statistiche customer
            if ($customer) {
                $customer->increment('total_orders');
                $customer->total_spent = $customer->total_spent + $order->total;
                $customer->save();
            }
            
            // 9. Svuota carrello
            $cart->items()->delete();
            $cart->coupon_id = null;
            $cart->subtotal = 0;
            $cart->discount_total = 0;
            $cart->tax_total = 0;
            $cart->total = 0;
            $cart->save();
            
            // 10. Dispatch evento
            event(new OrderPlaced($order));
            
            return $order;
        });
    }
}