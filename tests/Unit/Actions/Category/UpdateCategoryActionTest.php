<?php

namespace Tests\Unit\Actions\Category;

use App\Actions\Category\UpdateCategoryAction;
use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UpdateCategoryActionTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_updates_category_in_database()
    {
        $user = User::factory()->create();
        $category = Category::factory()->create(['user_id' => $user->id]);
        $data = ['name' => 'Updated Category'];
        $action = new UpdateCategoryAction();

        $this->assertDatabaseCount('categories', 1);

        $action->handle($category, $data);

        $this->assertDatabaseHas('categories', [
            'id' => $category->id,
            'name' => 'Updated Category',
            'user_id' => $user->id,
        ]);
    }

    public function test_it_returns_updated_category()
    {
        $user = User::factory()->create();
        $category = Category::factory()->create(['user_id' => $user->id]);
        $data = ['name' => 'Updated Category'];
        $action = new UpdateCategoryAction();

        $result = $action->handle($category, $data);

        $this->assertInstanceOf(Category::class, $result);
        $this->assertEquals('Updated Category', $result->name);
        $this->assertEquals($category->id, $result->id);
        $this->assertEquals($user->id, $result->user_id);
    }
}
