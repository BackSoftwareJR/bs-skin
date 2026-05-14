<?php

namespace App\Policies;

use App\Models\Order;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class OrderPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->can('view_orders');
    }

    public function view(User $user, Order $order): bool
    {
        return $user->can('view_orders');
    }

    public function create(User $user): bool
    {
        // Ordini creati solo dal frontend, non dall'admin
        return false;
    }

    public function update(User $user, Order $order): bool
    {
        return $user->can('update_orders');
    }

    public function delete(User $user, Order $order): bool
    {
        return $user->can('delete_orders');
    }

    public function restore(User $user, Order $order): bool
    {
        return $user->can('restore_orders');
    }

    public function forceDelete(User $user, Order $order): bool
    {
        return $user->can('force_delete_orders');
    }
}