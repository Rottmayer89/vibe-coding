<?php

namespace Tests\Unit\Models;

use App\Models\Category;
use App\Models\Cost;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_has_correct_fields()
    {
        $category = Category::factory()->create();

        $expected_fields = [
            'id',
            'name',
            'user_id',
            'created_at',
            'updated_at',
        ];

        $actual_fields = array_keys($category->toArray());

        sort($expected_fields);
        sort($actual_fields);

        $this->assertEquals($expected_fields, $actual_fields);
    }

    public function test_it_uses_guarded_property_instead_of_fillable()
    {
        $category = new Category();

        $this->assertEmpty($category->getGuarded());
        $this->assertIsArray($category->getGuarded());
    }

    public function test_it_belongs_to_user()
    {
        $category = new Category();

        $this->assertInstanceOf(BelongsTo::class, $category->user());
    }

    public function test_it_relates_to_correct_user()
    {
        $user = User::factory()->create();
        $category = Category::factory()->create(['user_id' => $user->id]);
        
        $this->assertInstanceOf(User::class, $category->user);
        $this->assertEquals($user->id, $category->user->id);
    }
    
    public function test_it_has_many_costs()
    {
        $category = new Category();
        
        $this->assertInstanceOf(HasMany::class, $category->costs());
    }
    
    public function test_it_can_have_multiple_costs()
    {
        $user = User::factory()->create();
        $category = Category::factory()->create(['user_id' => $user->id]);
        $costs = Cost::factory()->count(3)->create([
            'user_id' => $user->id,
            'category_id' => $category->id
        ]);
        
        $this->assertCount(3, $category->costs);
        $this->assertInstanceOf(Cost::class, $category->costs->first());
    }
}
