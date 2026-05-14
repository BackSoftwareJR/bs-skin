<?php

namespace App\Livewire\Public\Account;

use App\Models\Order;
use Livewire\Component;

class RecentOrders extends Component
{
    public function render()
    {
        $customerId = session('skintemple_customer_id');
        
        $recentOrders = $customerId 
            ? Order::where('customer_id', $customerId)
                ->with(['items'])
                ->latest()
                ->limit(3)
                ->get()
            : collect();
        
        return view('livewire.public.account.recent-orders', [
            'recentOrders' => $recentOrders
        ]);
    }
}