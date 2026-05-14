<?php

namespace App\Policies;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CustomerPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->can('view_customers');
    }

    public function view(User $user, Customer $customer): bool
    {
        return $user->can('view_customers');
    }

    public function create(User $user): bool
    {
        return $user->can('create_customers');
    }

    public function update(User $user, Customer $customer): bool
    {
        return $user->can('update_customers');
    }

    public function delete(User $user, Customer $customer): bool
    {
        return $user->can('delete_customers');
    }

    public function restore(User $user, Customer $customer): bool
    {
        return $user->can('restore_customers');
    }

    public function forceDelete(User $user, Customer $customer): bool
    {
        return $user->can('force_delete_customers');
    }
}