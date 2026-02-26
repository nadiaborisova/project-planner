<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    public function view(User $user, User $model): bool
    {
        return $user->id === $model->id || 
            $user->teams()->whereHas('users', function($q) use ($model) {
                $q->where('users.id', $model->id);
            })->exists();
    }

    public function update(User $user, User $model): bool
    {
        return $user->id === $model->id;
    }
}
