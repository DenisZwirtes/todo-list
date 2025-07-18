<?php

use App\Models\User;
use App\Models\Category;
use App\Models\Task;
use App\Models\Log;

test('user model has relationships', function () {
    $user = User::factory()->create();

    expect($user->tasks())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsToMany::class);
    expect($user->categories())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\HasMany::class);
});

test('user model can access tasks through many to many', function () {
    $user = User::factory()->create();
    $task = Task::factory()->create();

    $user->tasks()->attach($task->id);

    expect($user->tasks)->toHaveCount(1);
    expect($user->tasks->first()->id)->toBe($task->id);
});

test('category model has relationships', function () {
    $user = User::factory()->create();
    $category = Category::factory()->create(['user_id' => $user->id]);

    expect($category->user())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class);
    expect($category->tasks())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\HasMany::class);
});

test('category model validates unique name per user', function () {
    $user = User::factory()->create();

    Category::factory()->create([
        'name' => 'Test Category',
        'user_id' => $user->id
    ]);

    $this->expectException(\Illuminate\Database\QueryException::class);

    Category::factory()->create([
        'name' => 'Test Category',
        'user_id' => $user->id
    ]);
});

test('task model has relationships', function () {
    $user = User::factory()->create();
    $category = Category::factory()->create(['user_id' => $user->id]);
    $task = Task::factory()->create([
        'category_id' => $category->id
    ]);

    expect($task->category())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class);
    expect($task->users())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsToMany::class);
});

test('task model can toggle completion status', function () {
    $task = Task::factory()->create(['is_completed' => false]);

    $task->is_completed = true;
    $task->completed_at = now();
    $task->save();

    expect($task->is_completed)->toBeTrue();

    $task->is_completed = false;
    $task->completed_at = null;
    $task->save();

    expect($task->is_completed)->toBeFalse();
});





test('user model can get stats', function () {
    $user = User::factory()->create();

    $tasks = Task::factory()->count(8)->create();
    $user->tasks()->attach($tasks->pluck('id'));

    Category::factory()->count(2)->create(['user_id' => $user->id]);

    expect($user->tasks()->count())->toBe(8);
    expect($user->categories()->count())->toBe(2);
});

test('category model can get task count', function () {
    $user = User::factory()->create();
    $category = Category::factory()->create(['user_id' => $user->id]);

    Task::factory()->count(3)->create([
        'category_id' => $category->id
    ]);

    expect($category->tasks()->count())->toBe(3);
});

test('task model can be filtered by status', function () {
    Task::factory()->count(2)->create(['is_completed' => false]);
    Task::factory()->count(3)->create(['is_completed' => true]);

    expect(Task::where('is_completed', false)->count())->toBe(2);
    expect(Task::where('is_completed', true)->count())->toBe(3);
});
