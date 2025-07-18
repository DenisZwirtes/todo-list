<?php

use App\Models\User;
use App\Models\Category;
use App\Models\Task;
use App\Services\CategoryService;
use App\Services\TaskService;
use App\Services\LogService;
use App\Services\RateLimiterService;
use App\DTOs\CategoryDTO;
use App\DTOs\TaskDTO;
use App\Repositories\CategoryRepository;
use App\Repositories\TaskRepository;
use App\Contracts\Services\CategoryServiceInterface;
use App\Contracts\Services\TaskServiceInterface;
use App\Contracts\Services\LogServiceInterface;
use App\Contracts\Services\RateLimiterServiceInterface;

test('category service can create category', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $categoryDTO = new CategoryDTO(
        name: 'Test Category',
        color: '#FF0000'
    );

    $service = app(CategoryServiceInterface::class);
    $category = $service->create($categoryDTO);

    expect($category)->toBeInstanceOf(Category::class);
    expect($category->name)->toBe('Test Category');
    expect($category->color)->toBe('#FF0000');
    expect($category->user_id)->toBe($user->id);
});

test('category service can update category', function () {
    $user = User::factory()->create();
    $category = Category::factory()->create(['user_id' => $user->id]);
    $this->actingAs($user);

    $categoryDTO = new CategoryDTO(
        name: 'Updated Category',
        color: '#00FF00'
    );

    $service = app(CategoryServiceInterface::class);
    $updatedCategory = $service->update($category->id, $categoryDTO);

    expect($updatedCategory->name)->toBe('Updated Category');
    expect($updatedCategory->color)->toBe('#00FF00');
});

test('category service can delete category', function () {
    $user = User::factory()->create();
    $category = Category::factory()->create(['user_id' => $user->id]);
    $this->actingAs($user);

    $service = app(CategoryServiceInterface::class);
    $service->delete($category->id);

    expect(Category::find($category->id))->toBeNull();
});

test('category service can find category by id', function () {
    $user = User::factory()->create();
    $category = Category::factory()->create(['user_id' => $user->id]);
    $this->actingAs($user);

    $service = app(CategoryServiceInterface::class);
    $foundCategory = $service->findById($category->id);

    expect($foundCategory)->toBeInstanceOf(Category::class);
    expect($foundCategory->id)->toBe($category->id);
});

test('category service can list user categories', function () {
    $user = User::factory()->create();
    Category::factory()->count(3)->create(['user_id' => $user->id]);
    $this->actingAs($user);

    $service = app(CategoryServiceInterface::class);
    $categories = $service->listUserCategories();

    expect($categories)->toHaveCount(3);
});

test('task service can create task', function () {
    $user = User::factory()->create();
    $category = Category::factory()->create(['user_id' => $user->id]);
    $this->actingAs($user);

    $taskDTO = new TaskDTO(
        title: 'Test Task',
        description: 'Test Description',
        category_id: $category->id,
        is_completed: false,
        assigned_users: [$user->id]
    );

    $service = app(TaskServiceInterface::class);
    $task = $service->create($taskDTO);

    expect($task)->toBeInstanceOf(Task::class);
    expect($task->title)->toBe('Test Task');
    expect($task->description)->toBe('Test Description');
    expect($task->category_id)->toBe($category->id);
});

test('task service can update task', function () {
    $user = User::factory()->create();
    $category = Category::factory()->create(['user_id' => $user->id]);
    $task = Task::factory()->create(['category_id' => $category->id]);
    $user->tasks()->attach($task->id);
    $this->actingAs($user);

    $taskDTO = new TaskDTO(
        title: 'Updated Task',
        description: 'Updated Description',
        category_id: $category->id,
        is_completed: true,
        assigned_users: [$user->id]
    );

    $service = app(TaskServiceInterface::class);
    $updatedTask = $service->update($task->id, $taskDTO);

    expect($updatedTask->title)->toBe('Updated Task');
    expect($updatedTask->description)->toBe('Updated Description');
    expect($updatedTask->is_completed)->toBeTrue();
});

test('task service can delete task', function () {
    $user = User::factory()->create();
    $task = Task::factory()->create();
    $user->tasks()->attach($task->id);
    $this->actingAs($user);

    $service = app(TaskServiceInterface::class);
    $service->delete($task->id);

    expect(Task::find($task->id))->toBeNull();
});

test('task service can find task by id', function () {
    $user = User::factory()->create();
    $task = Task::factory()->create();
    $user->tasks()->attach($task->id);
    $this->actingAs($user);

    $service = app(TaskServiceInterface::class);
    $foundTask = $service->findById($task->id);

    expect($foundTask)->toBeInstanceOf(Task::class);
    expect($foundTask->id)->toBe($task->id);
});

test('task service can list user tasks with filters', function () {
    $user = User::factory()->create();
    $category = Category::factory()->create(['user_id' => $user->id]);

    $tasks = Task::factory()->count(5)->create(['category_id' => $category->id]);
    $user->tasks()->attach($tasks->pluck('id'));

    $this->actingAs($user);

    $service = app(TaskServiceInterface::class);

    // Test filter by completion status
    $completedTasks = $service->listUserTasks(['is_completed' => true]);
    expect($completedTasks)->toHaveCount(0);

    // Test filter by category
    $categoryTasks = $service->listUserTasks(['category_id' => $category->id]);
    expect($categoryTasks)->toHaveCount(5);
});

test('log service can create log entries', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $service = app(LogServiceInterface::class);

    // Test info log
    $service->info('Test info message', ['context' => 'test']);

    // Test error log
    $service->error('Test error message', ['context' => 'test']);

    // Test warning log
    $service->warning('Test warning message', ['context' => 'test']);

    // Test debug log
    $service->debug('Test debug message', ['context' => 'test']);

    expect(\App\Models\Log::count())->toBe(4);
});

test('rate limiter service can check limits', function () {
    $service = app(RateLimiterServiceInterface::class);

    $key = 'test_key';
    $maxAttempts = 5;
    $decayMinutes = 1;

    // Should allow first attempts
    for ($i = 0; $i < $maxAttempts; $i++) {
        expect($service->tooManyAttempts($key, $maxAttempts))->toBeFalse();
        $service->hit($key, $decayMinutes);
    }

    // Should block after max attempts
    expect($service->tooManyAttempts($key, $maxAttempts))->toBeTrue();
});

test('rate limiter service can check remaining attempts', function () {
    $service = app(RateLimiterServiceInterface::class);

    $key = 'test_key';
    $maxAttempts = 5;
    $decayMinutes = 1;

    // Check remaining attempts
    expect($service->remaining($key, $maxAttempts))->toBe(5);

    // Hit once
    $service->hit($key, $decayMinutes);

    expect($service->remaining($key, $maxAttempts))->toBe(4);
});
