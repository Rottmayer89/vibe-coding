<?php

namespace Tests\Unit\Models;

use App\Models\Category;
use App\Models\Cost;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CostTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_has_correct_fields()
    {
        $cost = Cost::factory()->create();
        
        $expected_fields = [
            'id',
            'name',
            'user_id',
            'category_id',
            'amount',
            'paid_at',
            'created_at',
            'updated_at',
        ];
        
        $actual_fields = array_keys($cost->toArray());
        
        sort($expected_fields);
        sort($actual_fields);
        
        $this->assertEquals($expected_fields, $actual_fields);
    }
    
    public function test_it_uses_guarded_property_instead_of_fillable()
    {
        $cost = new Cost();
        
        $this->assertEmpty($cost->getGuarded());
        $this->assertIsArray($cost->getGuarded());
    }
    
    public function test_it_belongs_to_user()
    {
        $cost = new Cost();
        
        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class, $cost->user());
    }
    
    public function test_it_belongs_to_category()
    {
        $cost = new Cost();
        
        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class, $cost->category());
    }
    
    public function test_it_casts_paid_at_to_date()
    {
        $cost = new Cost();
        
        $casts = $cost->getCasts();
        
        $this->assertArrayHasKey('paid_at', $casts);
        $this->assertEquals('date', $casts['paid_at']);
    }
    
    public function test_it_relates_to_correct_user()
    {
        $user = User::factory()->create();
        $category = Category::factory()->create(['user_id' => $user->id]);
        $cost = Cost::factory()->create([
            'user_id' => $user->id,
            'category_id' => $category->id
        ]);
        
        $this->assertInstanceOf(User::class, $cost->user);
        $this->assertEquals($user->id, $cost->user->id);
    }
    
    public function test_it_relates_to_correct_category()
    {
        $user = User::factory()->create();
        $category = Category::factory()->create(['user_id' => $user->id]);
        $cost = Cost::factory()->create([
            'user_id' => $user->id,
            'category_id' => $category->id
        ]);
        
        $this->assertInstanceOf(Category::class, $cost->category);
        $this->assertEquals($category->id, $cost->category->id);
    }
}
