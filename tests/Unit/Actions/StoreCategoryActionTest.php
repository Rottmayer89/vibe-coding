<?php

namespace Tests\Unit\Actions;

use App\Actions\StoreCategoryAction;
use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StoreCategoryActionTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_creates_category_in_database()
    {
        $user = User::factory()->create();
        $data = ['name' => 'Test Category'];
        $action = new StoreCategoryAction();

        $this->assertDatabaseHasCount('categories', 0);

        $action->handle($user, $data);

        $this->assertDatabaseHas('categories', [
            'name' => 'Test Category',
            'user_id' => $user->id,
        ]);
    }

    public function test_it_returns_created_category()
    {

        $user = User::factory()->create();
        $data = ['name' => 'Test Category'];
        $action = new StoreCategoryAction();

        $result = $action->handle($user, $data);

        $this->assertInstanceOf(Category::class, $result);
        $this->assertEquals('Test Category', $result->name);
        $this->assertEquals($user->id, $result->user_id);
    }
}
