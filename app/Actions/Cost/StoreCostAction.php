<?php

namespace App\Actions\Cost;

use App\Models\Cost;
use App\Models\User;

class StoreCostAction
{
    public function handle(User $user, array $data): Cost
    {
        return $user->costs()->create([
            'name' => $data['name'],
            'category_id' => $data['category_id'],
            'amount' => $data['amount'],
            'paid_at' => $data['paid_at'] ?? null
        ]);
    }
}
