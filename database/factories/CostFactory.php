<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class CostFactory extends Factory
{
    public function definition(): array
    {
        $user = User::factory()->create();
        $category = Category::factory()->create(['user_id' => $user->id]);

        return [
            'name' => $this->faker->sentence(3),
            'user_id' => $user->id,
            'category_id' => $category->id,
            'amount' => $this->faker->numberBetween(1000, 100_000_000_000),
            'paid_at' => $this->faker->date(),
        ];
    }
}
