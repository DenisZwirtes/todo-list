<?php

use App\Models\Task;
use App\Models\Category;
use App\Models\User;
use Inertia\Testing\AssertableInertia;
use function Pest\Laravel\get;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->actingAs($this->user);
});

test('it filters tasks by category', function () {
    $category1 = Category::factory()->create(['user_id' => $this->user->id]);
    $category2 = Category::factory()->create(['user_id' => $this->user->id]);

    $task1 = Task::factory()->create([
        'category_id' => $category1->id,
        'title' => 'Tarefa Categoria 1',
    ]);

    $task2 = Task::factory()->create([
        'category_id' => $category2->id,
        'title' => 'Tarefa Categoria 2',
    ]);

    $task1->users()->attach($this->user->id);
    $task2->users()->attach($this->user->id);

    $response = get(route('tasks.index', ['category_id' => $category1->id]));

    $response->assertStatus(200);
    $response->assertInertia(fn (AssertableInertia $page) => $page
        ->component('Tasks/Index')
        ->has('tasks')
        ->has('categories')
        ->has('filters')
    );
});

test('it filters tasks by completion status', function () {
    $task1 = Task::factory()->create([
        'is_completed' => true,
    ]);

    $task2 = Task::factory()->create([
        'is_completed' => false,
    ]);

    $task1->users()->attach($this->user->id);
    $task2->users()->attach($this->user->id);

    $response = get(route('tasks.index', ['completed' => true]));

    $response->assertStatus(200);
    $response->assertInertia(fn (AssertableInertia $page) => $page
        ->component('Tasks/Index')
        ->has('tasks')
        ->has('categories')
        ->has('filters')
    );
});

test('it filters tasks by category and completion', function () {
    $category = Category::factory()->create(['user_id' => $this->user->id]);

    $task1 = Task::factory()->create([
        'category_id' => $category->id,
        'is_completed' => true,
    ]);

    $task2 = Task::factory()->create([
        'category_id' => $category->id,
        'is_completed' => false,
    ]);

    $task1->users()->attach($this->user->id);
    $task2->users()->attach($this->user->id);

    $response = get(route('tasks.index', [
        'category_id' => $category->id,
        'completed' => true,
    ]));

    $response->assertStatus(200);
    $response->assertInertia(fn (AssertableInertia $page) => $page
        ->component('Tasks/Index')
        ->has('tasks')
        ->has('categories')
        ->has('filters')
    );
});
