<?php

use App\Models\User;
use App\Models\Category;
use Inertia\Testing\AssertableInertia;
use function Pest\Laravel\{get, post, put, delete};

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->actingAs($this->user);

    // Desabilita CSRF para este teste
    disableCsrf();
});

afterEach(function () {
    Category::query()->delete();
    User::query()->delete();
});

test('it displays category index page', function () {
    Category::factory()->count(3)->create(['user_id' => $this->user->id]);

    $response = get(route('categories.index'));

    $response->assertStatus(200);
    $response->assertInertia(fn (AssertableInertia $page) => $page
        ->component('Categories/Index')
        ->has('categories')
        ->has('pagination')
    );
});

test('it creates a new category', function () {
    $data = ['name' => 'Nova Categoria'];

    $response = post(route('categories.store'), $data);

    expect(Category::where('name', 'Nova Categoria')->exists())->toBeTrue();
    $response->assertRedirect(route('categories.index'));
    $response->assertSessionHas('success');
});

test('it validates category creation', function () {
    $response = post(route('categories.store'), [
        'name' => '',
    ]);

    $response->assertStatus(302); // Redireciona de volta com erros
    $response->assertSessionHasErrors('error');
});

test('it updates a category', function () {
    $category = Category::factory()->create(['user_id' => $this->user->id]);

    $data = ['name' => 'Categoria atualizada com sucesso!'];

    $response = put(route('categories.update', $category), $data);

    expect(Category::find($category->id)->name)->toBe('Categoria atualizada com sucesso!');
    $response->assertRedirect(route('categories.index'));
    $response->assertSessionHas('success');
});

test('it deletes a category', function () {
    $category = Category::factory()->create(['user_id' => $this->user->id]);

    $response = delete(route('categories.destroy', $category));

    expect(Category::find($category->id))->toBeNull();
    $response->assertRedirect(route('categories.index'));
    $response->assertSessionHas('success');
});
