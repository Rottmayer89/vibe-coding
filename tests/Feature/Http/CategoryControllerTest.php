<?php

namespace Tests\Feature\Http;

use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryControllerTest extends TestCase
{
    use RefreshDatabase;


    public function test_it_displays_categories_page()
    {
        $user = User::factory()->create();
        $categories = Category::factory()->count(3)->create(['user_id' => $user->id]);

        $response = $this
            ->actingAs($user)
            ->get(route('categories.index'));

        $response->assertStatus(200);
        $response->assertViewHas('categories');
    }

    public function test_it_denies_guest_from_viewing_index()
    {
        $response = $this->get(route('categories.index'));

        $response->assertRedirect(route('login'));
    }

    public function test_it_displays_create_form()
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->get(route('categories.create'));

        $response->assertStatus(200);
        $response->assertViewIs('categories.create');
    }

    public function test_it_denies_guest_from_viewing_create_form()
    {
        $response = $this->get(route('categories.create'));

        $response->assertRedirect(route('login'));
    }

    public function test_it_stores_new_category()
    {
        $user = User::factory()->create();
        $data = ['name' => 'Test Category'];

        $response = $this
            ->actingAs($user)
            ->post(route('categories.store'), $data);

        $response->assertRedirect(route('categories.index'));
        $this->assertDatabaseHas('categories', [
            'name' => 'Test Category',
            'user_id' => $user->id
        ]);
    }

    public function test_it_denies_guest_from_storing_category()
    {
        $data = ['name' => 'Test Category'];

        $response = $this->post(route('categories.store'), $data);

        $response->assertRedirect(route('login'));
        $this->assertDatabaseMissing('categories', ['name' => 'Test Category']);
    }

    public function test_it_validates_name_is_required()
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->post(route('categories.store'), ['name' => '']);

        $response->assertSessionHasErrors('name');
    }

    public function test_it_validates_name_is_string()
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->post(route('categories.store'), ['name' => 123]);

        $response->assertSessionHasErrors('name');
    }

    public function test_it_validates_name_max_length()
    {
        $user = User::factory()->create();
        $longName = str_repeat('a', 101);

        $response = $this
            ->actingAs($user)
            ->post(route('categories.store'), ['name' => $longName]);

        $response->assertSessionHasErrors('name');
    }

    public function test_it_displays_edit_form()
    {
        $user = User::factory()->create();
        $category = Category::factory()->create(['user_id' => $user->id]);

        $response = $this
            ->actingAs($user)
            ->get(route('categories.edit', $category));

        $response->assertStatus(200);
        $response->assertViewIs('categories.edit');
        $response->assertViewHas('category', $category);
    }

    public function test_it_denies_guest_from_viewing_edit_form()
    {
        $user = User::factory()->create();
        $category = Category::factory()->create(['user_id' => $user->id]);

        $response = $this->get(route('categories.edit', $category));

        $response->assertRedirect(route('login'));
    }

    public function test_it_denies_unauthorized_user_from_viewing_edit_form()
    {
        $owner = User::factory()->create();
        $category = Category::factory()->create(['user_id' => $owner->id]);

        $otherUser = User::factory()->create();

        $response = $this
            ->actingAs($otherUser)
            ->get(route('categories.edit', $category));

        $response->assertStatus(403);
    }

    public function test_it_updates_category()
    {
        $user = User::factory()->create();
        $category = Category::factory()->create(['user_id' => $user->id]);
        $data = ['name' => 'Updated Category'];

        $response = $this
            ->actingAs($user)
            ->patch(route('categories.update', $category), $data);

        $response->assertRedirect(route('categories.index'));
        $this->assertDatabaseHas('categories', [
            'id' => $category->id,
            'name' => 'Updated Category'
        ]);
    }

    public function test_it_denies_unauthorized_user_from_updating_category()
    {
        $owner = User::factory()->create();
        $category = Category::factory()->create(['user_id' => $owner->id]);

        $otherUser = User::factory()->create();
        $data = ['name' => 'Updated Category'];

        $response = $this
            ->actingAs($otherUser)
            ->patch(route('categories.update', $category), $data);

        $response->assertStatus(403);
        $this->assertDatabaseMissing('categories', [
            'id' => $category->id,
            'name' => 'Updated Category'
        ]);
    }

    public function test_it_denies_guest_from_updating_category()
    {
        $user = User::factory()->create();
        $category = Category::factory()->create(['user_id' => $user->id]);
        $data = ['name' => 'Updated Category'];

        $response = $this->patch(route('categories.update', $category), $data);

        $response->assertRedirect(route('login'));
        $this->assertDatabaseMissing('categories', [
            'id' => $category->id,
            'name' => 'Updated Category'
        ]);
    }

    public function test_it_deletes_category()
    {
        $user = User::factory()->create();
        $category = Category::factory()->create(['user_id' => $user->id]);

        $response = $this
            ->actingAs($user)
            ->delete(route('categories.destroy', $category));

        $response->assertRedirect(route('categories.index'));
        $this->assertDatabaseMissing('categories', ['id' => $category->id]);
    }

    public function test_it_denies_unauthorized_user_from_deleting_category()
    {
        $owner = User::factory()->create();
        $category = Category::factory()->create(['user_id' => $owner->id]);

        $otherUser = User::factory()->create();

        $response = $this
            ->actingAs($otherUser)
            ->delete(route('categories.destroy', $category));

        $response->assertStatus(403);
        $this->assertDatabaseHas('categories', ['id' => $category->id]);
    }

    public function test_it_denies_guest_from_deleting_category()
    {
        $user = User::factory()->create();
        $category = Category::factory()->create(['user_id' => $user->id]);

        $response = $this->delete(route('categories.destroy', $category));

        $response->assertRedirect(route('login'));
        $this->assertDatabaseHas('categories', ['id' => $category->id]);
    }
}
