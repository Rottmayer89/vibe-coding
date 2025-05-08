<?php

namespace Tests\Unit\Models;

use App\Models\Category;
use App\Models\Cost;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_has_correct_fields()
    {
        $user = User::factory()->create();

        $expected_fields = [
            'id',
            'name',
            'email',
            'email_verified_at',
            'created_at',
            'updated_at',
        ];

        $actual_fields = array_keys($user->toArray());

        sort($expected_fields);
        sort($actual_fields);

        $this->assertEquals($expected_fields, $actual_fields);
    }

    public function test_it_has_correct_casts()
    {
        $user = new User();

        $expected_casts = [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'id' => 'int',
        ];

        $actual_casts = $user->getCasts();

        $this->assertEquals($expected_casts, $actual_casts);
    }

    public function test_it_uses_guarded_property_instead_of_fillable()
    {
        $user = new User();

        $this->assertEmpty($user->getGuarded());
        $this->assertIsArray($user->getGuarded());
    }

    public function test_it_has_many_categories()
    {
        $user = new User();

        $this->assertInstanceOf(HasMany::class, $user->categories());
    }

    public function test_it_can_have_multiple_categories()
    {
        $user = User::factory()->create();
        $categories = Category::factory()->count(3)->create(['user_id' => $user->id]);

        $this->assertCount(3, $user->categories);
        $this->assertInstanceOf(Category::class, $user->categories->first());
    }

    public function test_it_has_many_costs()
    {
        $user = new User();

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\HasMany::class, $user->costs());
    }

    public function test_it_can_have_multiple_costs()
    {
        $user = User::factory()->create();
        $category = Category::factory()->create(['user_id' => $user->id]);
        $costs = Cost::factory()->count(3)->create([
            'user_id' => $user->id,
            'category_id' => $category->id
        ]);

        $this->assertCount(3, $user->costs);
        $this->assertInstanceOf(Cost::class, $user->costs->first());
    }
}
