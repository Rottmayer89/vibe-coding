<?php

namespace App\Actions\Cost;

use App\Models\Cost;

class UpdateCostAction
{
    public function handle(Cost $cost, array $data): Cost
    {
        $cost->update([
            'name' => $data['name'],
            'category_id' => $data['category_id'],
            'amount' => $data['amount'],
            'paid_at' => $data['paid_at'] ?? null
        ]);

        return $cost->fresh();
    }
}
