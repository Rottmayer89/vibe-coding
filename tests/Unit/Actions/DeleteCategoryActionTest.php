<?php

namespace Tests\Unit\Actions;

use App\Actions\DeleteCategoryAction;
use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DeleteCategoryActionTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_deletes_category_from_database()
    {
        $user = User::factory()->create();
        $category = Category::factory()->create(['user_id' => $user->id]);
        $action = new DeleteCategoryAction();

        $this->assertDatabaseCount('categories', 1);

        $action->handle($category);

        $this->assertDatabaseMissing('categories', [
            'id' => $category->id,
        ]);
    }

    public function test_it_returns_true_on_successful_deletion()
    {
        $user = User::factory()->create();
        $category = Category::factory()->create(['user_id' => $user->id]);
        $action = new DeleteCategoryAction();

        $result = $action->handle($category);

        $this->assertTrue($result);
    }
}
