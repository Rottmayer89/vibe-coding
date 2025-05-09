<?php

namespace App\Policies;

use App\Models\Cost;
use App\Models\User;

class CostPolicy
{
    public function update(User $user, Cost $cost): bool
    {
        return $user->id === $cost->user_id;
    }

    public function delete(User $user, Cost $cost): bool
    {
        return $user->id === $cost->user_id;
    }
}
