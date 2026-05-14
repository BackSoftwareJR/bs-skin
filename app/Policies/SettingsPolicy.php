<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class SettingsPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->can('view_settings');
    }

    public function view(User $user): bool
    {
        return $user->can('view_settings');
    }

    public function update(User $user): bool
    {
        return $user->can('update_settings');
    }
}