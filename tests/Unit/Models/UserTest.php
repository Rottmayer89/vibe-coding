<?php

namespace Tests\Unit\Models;

use App\Models\User;
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
}
