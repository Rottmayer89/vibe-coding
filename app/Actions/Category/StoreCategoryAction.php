<?php

namespace App\Actions\Category;

use App\Models\Category;
use App\Models\User;

class StoreCategoryAction
{
    public function handle(User $user, array $data): Category
    {
        return $user->categories()->create([
            'name' => $data['name']
        ]);
    }
}
