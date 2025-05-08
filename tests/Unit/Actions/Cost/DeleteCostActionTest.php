<?php

namespace Tests\Unit\Actions\Cost;

use App\Actions\Cost\DeleteCostAction;
use App\Models\Category;
use App\Models\Cost;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DeleteCostActionTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_deletes_cost_from_database()
    {
        $user = User::factory()->create();
        $category = Category::factory()->create(['user_id' => $user->id]);
        $cost = Cost::factory()->create([
            'user_id' => $user->id,
            'category_id' => $category->id
        ]);

        $action = new DeleteCostAction();

        $this->assertDatabaseCount('costs', 1);

        $action->handle($cost);

        $this->assertDatabaseMissing('costs', [
            'id' => $cost->id,
        ]);
    }

    public function test_it_returns_true_on_successful_deletion()
    {
        $user = User::factory()->create();
        $category = Category::factory()->create(['user_id' => $user->id]);
        $cost = Cost::factory()->create([
            'user_id' => $user->id,
            'category_id' => $category->id
        ]);

        $action = new DeleteCostAction();

        $result = $action->handle($cost);

        $this->assertTrue($result);
    }
}
