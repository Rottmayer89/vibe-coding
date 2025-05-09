<?php

namespace Tests\Feature\Http;

use App\Models\Category;
use App\Models\Cost;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CostControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Index tests
     */
    public function test_it_denies_guest_from_viewing_index()
    {
        $response = $this->get(route('costs.index'));

        $response->assertRedirect(route('login'));
    }

    public function test_it_displays_costs_page()
    {
        $user = User::factory()->create();
        $category = Category::factory()->create(['user_id' => $user->id]);
        Cost::factory()->count(3)->create([
            'user_id' => $user->id,
            'category_id' => $category->id
        ]);

        Cost::factory()->count(7)->create();

        $response = $this
            ->actingAs($user)
            ->get(route('costs.index'));

        $response->assertStatus(200);
        $response->assertViewHas('costs', function ($costs) {
            return $costs->count() == 3;
        });
    }



    /**
     * Create tests
     */
    public function test_it_denies_guest_from_viewing_create_form()
    {
        $response = $this->get(route('costs.create'));

        $response->assertRedirect(route('login'));
    }

    public function test_it_displays_create_form()
    {
        $user = User::factory()->create();
        Category::factory()->count(3)->create(['user_id' => $user->id]);
        Category::factory()->count(7)->create();

        $response = $this
            ->actingAs($user)
            ->get(route('costs.create'));

        $response->assertStatus(200);
        $response->assertViewIs('costs.create');
        $response->assertViewHas('categories', function ($categories) {
            return $categories->count() == 3;
        });
    }




    /**
     * Store tests
     */
    public function test_it_denies_guest_from_storing_cost()
    {
        $user = User::factory()->create();
        $category = Category::factory()->create(['user_id' => $user->id]);

        $data = [
            'name' => 'Test Cost',
            'category_id' => $category->id,
            'amount' => 5000000,
            'paid_at' => '2025-05-09'
        ];

        $response = $this->post(route('costs.store'), $data);

        $response->assertRedirect(route('login'));
    }

    public function test_it_stores_new_cost()
    {
        $user = User::factory()->create();
        $category = Category::factory()->create(['user_id' => $user->id]);

        $data = [
            'name' => 'Test Cost',
            'category_id' => $category->id,
            'amount' => 5000000,
            'paid_at' => '2025-05-09'
        ];

        $response = $this
            ->actingAs($user)
            ->post(route('costs.store'), $data);

        $response->assertRedirect(route('costs.index'));
        $response->assertSessionHasNoErrors();
    }

    public function test_it_validates_name_is_required()
    {
        $user = User::factory()->create();
        $category = Category::factory()->create(['user_id' => $user->id]);

        $data = [
            'name' => '',
            'category_id' => $category->id,
            'amount' => 5000000,
            'paid_at' => '2025-05-09'
        ];

        $response = $this
            ->actingAs($user)
            ->post(route('costs.store'), $data);

        $response->assertSessionHasErrors('name');
    }

    public function test_it_validates_name_is_string()
    {
        $user = User::factory()->create();
        $category = Category::factory()->create(['user_id' => $user->id]);

        $data = [
            'name' => 123,
            'category_id' => $category->id,
            'amount' => 5000000,
            'paid_at' => '2025-05-09'
        ];

        $response = $this
            ->actingAs($user)
            ->post(route('costs.store'), $data);

        $response->assertSessionHasErrors('name');
    }

    public function test_it_validates_name_max_length()
    {
        $user = User::factory()->create();
        $category = Category::factory()->create(['user_id' => $user->id]);
        $longName = str_repeat('a', 101);

        $data = [
            'name' => $longName,
            'category_id' => $category->id,
            'amount' => 5000000,
            'paid_at' => '2025-05-09'
        ];

        $response = $this
            ->actingAs($user)
            ->post(route('costs.store'), $data);

        $response->assertSessionHasErrors('name');
    }

    public function test_it_validates_category_is_required()
    {
        $user = User::factory()->create();

        $data = [
            'name' => 'Test Cost',
            'category_id' => '',
            'amount' => 5000000,
            'paid_at' => '2025-05-09'
        ];

        $response = $this
            ->actingAs($user)
            ->post(route('costs.store'), $data);

        $response->assertSessionHasErrors('category_id');
    }

    public function test_it_validates_category_belongs_to_user()
    {
        $user = User::factory()->create();
        $other_user = User::factory()->create();
        $other_category = Category::factory()->create(['user_id' => $other_user->id]);

        $data = [
            'name' => 'Test Cost',
            'category_id' => $other_category->id,
            'amount' => 5000000,
            'paid_at' => '2025-05-09'
        ];

        $response = $this
            ->actingAs($user)
            ->post(route('costs.store'), $data);

        $response->assertSessionHasErrors('category_id');
    }

    public function test_it_validates_amount_is_required()
    {
        $user = User::factory()->create();
        $category = Category::factory()->create(['user_id' => $user->id]);

        $data = [
            'name' => 'Test Cost',
            'category_id' => $category->id,
            'amount' => '',
            'paid_at' => '2025-05-09'
        ];

        $response = $this
            ->actingAs($user)
            ->post(route('costs.store'), $data);

        $response->assertSessionHasErrors('amount');
    }

    public function test_it_validates_amount_is_numeric()
    {
        $user = User::factory()->create();
        $category = Category::factory()->create(['user_id' => $user->id]);

        $data = [
            'name' => 'Test Cost',
            'category_id' => $category->id,
            'amount' => 'not-a-number',
            'paid_at' => '2025-05-09'
        ];

        $response = $this
            ->actingAs($user)
            ->post(route('costs.store'), $data);

        $response->assertSessionHasErrors('amount');
    }

    public function test_it_validates_amount_is_not_negative()
    {
        $user = User::factory()->create();
        $category = Category::factory()->create(['user_id' => $user->id]);

        $data = [
            'name' => 'Test Cost',
            'category_id' => $category->id,
            'amount' => -1000,
            'paid_at' => '2025-05-09'
        ];

        $response = $this
            ->actingAs($user)
            ->post(route('costs.store'), $data);

        $response->assertSessionHasErrors('amount');
    }

    public function test_it_validates_paid_at_is_required()
    {
        $user = User::factory()->create();
        $category = Category::factory()->create(['user_id' => $user->id]);

        $data = [
            'name' => 'Test Cost',
            'category_id' => $category->id,
            'amount' => 5000000,
            'paid_at' => ''
        ];

        $response = $this
            ->actingAs($user)
            ->post(route('costs.store'), $data);

        $response->assertSessionHasErrors('paid_at');
    }

    public function test_it_validates_paid_at_is_date()
    {
        $user = User::factory()->create();
        $category = Category::factory()->create(['user_id' => $user->id]);

        $data = [
            'name' => 'Test Cost',
            'category_id' => $category->id,
            'amount' => 5000000,
            'paid_at' => 'not-a-date'
        ];

        $response = $this
            ->actingAs($user)
            ->post(route('costs.store'), $data);

        $response->assertSessionHasErrors('paid_at');
    }



    /**
     * Edit tests
     */
    public function test_it_denies_guest_from_viewing_edit_form()
    {
        $user = User::factory()->create();
        $category = Category::factory()->create(['user_id' => $user->id]);
        $cost = Cost::factory()->create([
            'user_id' => $user->id,
            'category_id' => $category->id
        ]);

        $response = $this->get(route('costs.edit', $cost));

        $response->assertRedirect(route('login'));
    }

    public function test_it_displays_edit_form()
    {
        $user = User::factory()->create();
        $category = Category::factory()->create(['user_id' => $user->id]);
        Category::factory()->count(5)->create();

        $cost = Cost::factory()->create([
            'user_id' => $user->id,
            'category_id' => $category->id
        ]);

        $response = $this
            ->actingAs($user)
            ->get(route('costs.edit', $cost));

        $response->assertStatus(200);
        $response->assertViewIs('costs.edit');
        $response->assertViewHas('cost', $cost);
        $response->assertViewHas('categories', function ($categories) {
            return $categories->count() == 1;
        });
    }


    public function test_it_denies_unauthorized_user_from_viewing_edit_form()
    {
        $owner = User::factory()->create();
        $category = Category::factory()->create(['user_id' => $owner->id]);
        $cost = Cost::factory()->create([
            'user_id' => $owner->id,
            'category_id' => $category->id
        ]);

        $other_user = User::factory()->create();

        $response = $this
            ->actingAs($other_user)
            ->get(route('costs.edit', $cost));

        $response->assertStatus(403);
    }



    /**
     * Update tests
     */
    public function test_it_denies_guest_from_updating_cost()
    {
        $user = User::factory()->create();
        $category = Category::factory()->create(['user_id' => $user->id]);

        $cost = Cost::factory()->create([
            'user_id' => $user->id,
            'category_id' => $category->id
        ]);

        $data = [
            'name' => 'Updated Cost',
            'category_id' => $category->id,
            'amount' => 10000000,
            'paid_at' => '2025-05-10'
        ];

        $response = $this->patch(route('costs.update', $cost), $data);

        $response->assertRedirect(route('login'));
    }


    public function test_it_updates_cost()
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

        $response = $this
            ->actingAs($user)
            ->patch(route('costs.update', $cost), $data);

        $response->assertRedirect(route('costs.index'));
        $response->assertSessionHasNoErrors();
    }

    public function test_it_denies_unauthorized_user_from_updating_cost()
    {
        $owner = User::factory()->create();
        $category = Category::factory()->create(['user_id' => $owner->id]);

        $cost = Cost::factory()->create([
            'user_id' => $owner->id,
            'category_id' => $category->id
        ]);

        $other_user = User::factory()->create();
        $other_category = Category::factory()->create(['user_id' => $other_user->id]);

        $data = [
            'name' => 'Updated Cost',
            'category_id' => $other_category->id,
            'amount' => 10000000,
            'paid_at' => '2025-05-10'
        ];

        $response = $this
            ->actingAs($other_user)
            ->patch(route('costs.update', $cost), $data);

        $response->assertStatus(403);
    }




    /**
     * Delete tests
     */
    public function test_it_denies_guest_from_deleting_cost()
    {
        $user = User::factory()->create();
        $category = Category::factory()->create(['user_id' => $user->id]);

        $cost = Cost::factory()->create([
            'user_id' => $user->id,
            'category_id' => $category->id
        ]);

        $response = $this->delete(route('costs.destroy', $cost));

        $response->assertRedirect(route('login'));
    }


    public function test_it_deletes_cost()
    {
        $user = User::factory()->create();
        $category = Category::factory()->create(['user_id' => $user->id]);

        $cost = Cost::factory()->create([
            'user_id' => $user->id,
            'category_id' => $category->id
        ]);

        $response = $this
            ->actingAs($user)
            ->delete(route('costs.destroy', $cost));

        $response->assertRedirect(route('costs.index'));
    }

    public function test_it_denies_unauthorized_user_from_deleting_cost()
    {
        $owner = User::factory()->create();
        $category = Category::factory()->create(['user_id' => $owner->id]);

        $cost = Cost::factory()->create([
            'user_id' => $owner->id,
            'category_id' => $category->id
        ]);

        $other_user = User::factory()->create();

        $response = $this
            ->actingAs($other_user)
            ->delete(route('costs.destroy', $cost));

        $response->assertStatus(403);
    }
}
