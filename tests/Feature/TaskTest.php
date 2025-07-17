<?php

use App\Models\User;
use App\Models\Task;
use App\Models\Category;
use Illuminate\Support\Facades\DB;
use Inertia\Testing\AssertableInertia;
use function Pest\Laravel\{get, post, put, delete};

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->actingAs($this->user);

    // Desabilita CSRF para este teste
    disableCsrf();
});

afterEach(function () {
    Task::query()->delete();
    User::query()->delete();
});

test('it displays task index page', function () {
    $tasks = Task::factory()->count(3)->create();

    foreach ($tasks as $task) {
        $task->users()->attach($this->user->id);
    }

    $response = get(route('tasks.index'));

    $response->assertStatus(200);
    $response->assertInertia(fn (AssertableInertia $page) => $page
        ->component('Tasks/Index')
        ->has('tasks')
        ->has('categories')
        ->has('filters')
    );
});

test('it creates a new task', function () {
    $category = Category::factory()->create(['user_id' => $this->user->id]);

    $data = [
        'title' => 'Nova Tarefa',
        'description' => 'Descrição da nova tarefa',
        'category_id' => $category->id,
        'is_completed' => false,
        'users' => [$this->user->id],
    ];

    $response = post(route('tasks.store'), $data);

    expect(Task::where('title', 'Nova Tarefa')->exists())->toBeTrue();
    expect(DB::table('task_user')->where('user_id', $this->user->id)->exists())->toBeTrue();

    $response->assertRedirect(route('tasks.index'));
    $response->assertSessionHas('success');
});

test('it validates task creation', function () {
    $response = post(route('tasks.store'), [
        'title' => '',
    ]);

    $response->assertStatus(302); // Redireciona de volta com erros
    $response->assertSessionHasErrors('error');
});

test('it updates a task', function () {
    $category = Category::factory()->create(['user_id' => $this->user->id]);

    $task = Task::factory()->create([
        'category_id' => $category->id,
    ]);

    $task->users()->attach($this->user->id);

    $data = [
        'title' => 'Tarefa Atualizada',
        'description' => 'Descrição Atualizada',
        'category_id' => $category->id,
        'users' => [$this->user->id],
    ];

    $response = put(route('tasks.update', $task), $data);

    expect(Task::find($task->id)->title)->toBe('Tarefa Atualizada');
    expect(DB::table('task_user')->where('task_id', $task->id)->where('user_id', $this->user->id)->exists())->toBeTrue();

    $response->assertRedirect(route('tasks.index'));
    $response->assertSessionHas('success');
});

test('it deletes a task', function () {
    $task = Task::factory()->create();

    $task->users()->attach($this->user->id);

    $response = delete(route('tasks.destroy', $task));

    expect(Task::find($task->id))->toBeNull();
    expect(DB::table('task_user')->where('task_id', $task->id)->exists())->toBeFalse();

    $response->assertRedirect(route('tasks.index'));
    $response->assertSessionHas('success');
});
