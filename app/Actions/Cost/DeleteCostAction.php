<?php

namespace App\Actions\Cost;

use App\Models\Cost;

class DeleteCostAction
{
    public function handle(Cost $cost): bool
    {
        return $cost->delete();
    }
}
