<?php

declare(strict_types=1);

namespace App\Services\Notifications;

use App\Mail\Admin\LowStockAlertMail;
use App\Mail\Admin\NewOrderNotificationMail;
use App\Mail\Admin\FailedPaymentNotificationMail;
use App\Models\Inventory;
use App\Models\Order;
use App\Models\Payment;
use App\Support\AdminNotifier;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class AdminNotificationService
{
    public function notifyNewOrder(Order $order): void
    {
        $mailable = new NewOrderNotificationMail($order);
        AdminNotifier::notify($mailable);
    }
    
    public function notifyLowStock(Collection $items): void
    {
        if ($items->isEmpty()) {
            return;
        }
        
        $mailable = new LowStockAlertMail($items);
        AdminNotifier::notify($mailable);
    }
    
    public function notifyFailedPayment(Order $order, Payment $payment): void
    {
        $mailable = new FailedPaymentNotificationMail($order, $payment);
        AdminNotifier::notify($mailable);
    }
    
    public function getLowStockItems(): Collection
    {
        return Inventory::where('quantity', '<=', DB::raw('threshold_low'))
            ->with([
                'productVariant.product.brand',
                'productVariant.product.media'
            ])
            ->get()
            ->map(function ($inventory) {
                $product = $inventory->productVariant->product;
                
                return [
                    'id' => $inventory->id,
                    'product_name' => $product->name,
                    'brand_name' => $product->brand?->name,
                    'sku' => $inventory->productVariant->sku ?? $product->sku,
                    'current_quantity' => $inventory->quantity,
                    'threshold_low' => $inventory->threshold_low,
                    'threshold_critical' => $inventory->threshold_critical,
                    'allow_backorder' => $inventory->allow_backorder,
                    'image' => $product->getFirstMediaUrl('gallery'),
                    'status' => $this->getStockStatus($inventory),
                ];
            });
    }
    
    private function getStockStatus(Inventory $inventory): string
    {
        if ($inventory->quantity <= 0 && !$inventory->allow_backorder) {
            return 'out_of_stock';
        }
        
        if ($inventory->quantity <= $inventory->threshold_critical) {
            return 'critical';
        }
        
        if ($inventory->quantity <= $inventory->threshold_low) {
            return 'low';
        }
        
        return 'ok';
    }
}