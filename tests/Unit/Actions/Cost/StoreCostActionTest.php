<?php

namespace Tests\Unit\Actions\Cost;

use App\Actions\Cost\StoreCostAction;
use App\Models\Category;
use App\Models\Cost;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StoreCostActionTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_creates_cost_in_database()
    {
        $user = User::factory()->create();
        $category = Category::factory()->create(['user_id' => $user->id]);
        $data = [
            'name' => 'Test Cost',
            'category_id' => $category->id,
            'amount' => 5000000,
            'paid_at' => '2025-05-08'
        ];

        $this->assertDatabaseCount('costs', 0);

        $action = new StoreCostAction();

        $action->handle($user, $data);

        $this->assertDatabaseHas('costs', [
            'name' => 'Test Cost',
            'user_id' => $user->id,
            'category_id' => $category->id,
            'amount' => 5000000
        ]);
    }

    public function test_it_returns_created_cost()
    {
        $user = User::factory()->create();
        $category = Category::factory()->create(['user_id' => $user->id]);
        $data = [
            'name' => 'Test Cost',
            'category_id' => $category->id,
            'amount' => 5000000,
            'paid_at' => '2025-05-08'
        ];
        $action = new StoreCostAction();

        $result = $action->handle($user, $data);

        $this->assertInstanceOf(Cost::class, $result);
        $this->assertEquals('Test Cost', $result->name);
        $this->assertEquals($user->id, $result->user_id);
        $this->assertEquals($category->id, $result->category_id);
        $this->assertEquals(5000000, $result->amount);
    }
}
