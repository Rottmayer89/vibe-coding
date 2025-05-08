<?php

namespace Tests\Unit\Actions\Cost;

use App\Actions\Cost\UpdateCostAction;
use App\Models\Category;
use App\Models\Cost;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UpdateCostActionTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_updates_cost_in_database()
    {
        $user = User::factory()->create();
        $category = Category::factory()->create(['user_id' => $user->id]);
        $newCategory = Category::factory()->create(['user_id' => $user->id]);
        $cost = Cost::factory()->create([
            'user_id' => $user->id,
            'category_id' => $category->id
        ]);

        $data = [
            'name' => 'Updated Cost',
            'category_id' => $newCategory->id,
            'amount' => 10000000,
            'paid_at' => '2025-05-10'
        ];

        $action = new UpdateCostAction();

        $this->assertDatabaseCount('costs', 1);

        $action->handle($cost, $data);

        $this->assertDatabaseHas('costs', [
            'id' => $cost->id,
            'name' => 'Updated Cost',
            'category_id' => $newCategory->id,
            'amount' => 10000000
        ]);
    }

    public function test_it_returns_updated_cost()
    {
        $user = User::factory()->create();
        $category = Category::factory()->create(['user_id' => $user->id]);
        $newCategory = Category::factory()->create(['user_id' => $user->id]);
        $cost = Cost::factory()->create([
            'user_id' => $user->id,
            'category_id' => $category->id
        ]);

        $data = [
            'name' => 'Updated Cost',
            'category_id' => $newCategory->id,
            'amount' => 10000000,
            'paid_at' => '2025-05-10'
        ];

        $action = new UpdateCostAction();

        $result = $action->handle($cost, $data);

        $this->assertInstanceOf(Cost::class, $result);
        $this->assertEquals('Updated Cost', $result->name);
        $this->assertEquals($newCategory->id, $result->category_id);
        $this->assertEquals(10000000, $result->amount);
        $this->assertEquals($cost->id, $result->id);
    }
}
