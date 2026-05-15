<?php

declare(strict_types=1);

namespace App\Livewire\Public\Account;

use App\Models\Order;
use Livewire\Component;

class RecentOrders extends Component
{
    public function render()
    {
        $customerId = auth('customer')->id();

        $recentOrders = $customerId
            ? Order::where('customer_id', $customerId)
                ->with(['items'])
                ->latest()
                ->limit(5)
                ->get()
            : collect();

        return view('livewire.public.account.recent-orders', [
            'recentOrders' => $recentOrders,
        ]);
    }
}
