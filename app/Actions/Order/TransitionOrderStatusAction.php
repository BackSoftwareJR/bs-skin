<?php

declare(strict_types=1);

namespace App\Actions\Order;

use App\Enums\OrderStatus;
use App\Events\OrderStatusChanged;
use App\Exceptions\InvalidOrderTransitionException;
use App\Models\Order;
use App\Models\OrderStatusHistory;
use App\Models\User;

class TransitionOrderStatusAction
{
    public function execute(Order $order, OrderStatus $newStatus, ?string $reason = null, ?User $performedBy = null): Order
    {
        $oldStatus = $order->status;
        
        // TODO: Implementare logica canTransitionTo quando l'enum OrderStatus sarà creato
        // if (!$oldStatus->canTransitionTo($newStatus)) {
        //     throw new InvalidOrderTransitionException();
        // }
        
        $order->status = $newStatus->value;
        
        // Gestisci timestamp specifici per stato
        match ($newStatus) {
            OrderStatus::PAID => $order->paid_at = now(),
            OrderStatus::SHIPPED => $order->shipped_at = now(),
            OrderStatus::DELIVERED => $order->delivered_at = now(),
            OrderStatus::CANCELLED => $order->cancelled_at = now(),
            default => null,
        };
        
        $order->save();
        
        // Crea history record
        OrderStatusHistory::create([
            'order_id' => $order->id,
            'from_status' => $oldStatus->value,
            'to_status' => $newStatus->value,
            'reason' => $reason,
            'performed_by_user_id' => $performedBy?->id,
        ]);
        
        event(new OrderStatusChanged($order, $oldStatus, $newStatus));
        
        return $order;
    }
}