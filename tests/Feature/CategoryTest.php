<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Category;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    private $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->actingAs($this->user);
    }

    protected function tearDown(): void
    {
        Category::query()->delete();
        User::query()->delete();

        parent::tearDown();
    }

    /** @test */
    public function it_displays_category_index_page()
    {
        Category::factory()->count(3)->create(['user_id' => $this->user->id]);

        $response = $this->get(route('categories.index'));

        $response->assertStatus(200);
        $response->assertSee(__('messages.my_categories'));
    }

    /** @test */
    public function it_creates_a_new_category()
    {
        $data = ['name' => 'Nova Categoria'];

        $response = $this->post(route('categories.store'), $data);

        $this->assertDatabaseHas('categories', ['name' => 'Nova Categoria']);
        $response->assertRedirect(route('categories.index'));
    }

    /** @test */
    public function it_validates_category_creation()
    {
        $response = $this->post(route('categories.store'), [
            'name' => '',
        ]);

        $response->assertSessionHasErrors('name');
    }

    /** @test */
    public function it_updates_a_category()
    {
        $category = Category::factory()->create(['user_id' => $this->user->id]);

        $data = ['name' => 'Categoria atualizada com sucesso!'];

        $response = $this->put(route('categories.update', $category), $data);

        $this->assertDatabaseHas('categories', ['id' => $category->id, 'name' => 'Categoria atualizada com sucesso!']);
        $response->assertRedirect(route('categories.show', $category));
    }

    /** @test */
    public function it_deletes_a_category()
    {
        $category = Category::factory()->create(['user_id' => $this->user->id]);

        $response = $this->delete(route('categories.destroy', $category));

        $this->assertDatabaseMissing('categories', ['id' => $category->id]);
        $response->assertRedirect(route('categories.index'));
    }
}
