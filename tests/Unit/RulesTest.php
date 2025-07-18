<?php

use App\Models\User;
use App\Models\Category;
use App\Models\Task;
use App\Rules\CategoryOwnership;
use App\Rules\TaskOwnership;

test('category ownership rule validates ownership correctly', function () {
    $user = User::factory()->create();
    $category = Category::factory()->create(['user_id' => $user->id]);

    $this->actingAs($user);
    $rule = new CategoryOwnership();

    $failed = false;
    $rule->validate('category_id', $category->id, function($message) use (&$failed) {
        $failed = true;
    });

    expect($failed)->toBeFalse();
});

test('category ownership rule fails for non-owner', function () {
    $user1 = User::factory()->create();
    $user2 = User::factory()->create();
    $category = Category::factory()->create(['user_id' => $user2->id]);

    $this->actingAs($user1);
    $rule = new CategoryOwnership();

    $failed = false;
    $rule->validate('category_id', $category->id, function($message) use (&$failed) {
        $failed = true;
    });

    expect($failed)->toBeTrue();
});

test('category ownership rule fails for non-existent category', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $rule = new CategoryOwnership();

    $failed = false;
    $rule->validate('category_id', 99999, function($message) use (&$failed) {
        $failed = true;
    });

    expect($failed)->toBeTrue();
});

test('task ownership rule validates ownership correctly', function () {
    $user = User::factory()->create();
    $task = Task::factory()->create();
    $user->tasks()->attach($task->id);

    $this->actingAs($user);
    $rule = new TaskOwnership();

    $failed = false;
    $rule->validate('task_id', $task->id, function($message) use (&$failed) {
        $failed = true;
    });

    expect($failed)->toBeFalse();
});

test('task ownership rule fails for non-owner', function () {
    $user1 = User::factory()->create();
    $user2 = User::factory()->create();
    $task = Task::factory()->create();
    $user2->tasks()->attach($task->id);

    $this->actingAs($user1);
    $rule = new TaskOwnership();

    $failed = false;
    $rule->validate('task_id', $task->id, function($message) use (&$failed) {
        $failed = true;
    });

    expect($failed)->toBeTrue();
});

test('task ownership rule fails for non-existent task', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $rule = new TaskOwnership();

    $failed = false;
    $rule->validate('task_id', 99999, function($message) use (&$failed) {
        $failed = true;
    });

    expect($failed)->toBeTrue();
});

test('rules handle null values gracefully', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $categoryRule = new CategoryOwnership();
    $taskRule = new TaskOwnership();

    $categoryFailed = false;
    $taskFailed = false;

    $categoryRule->validate('category_id', null, function($message) use (&$categoryFailed) {
        $categoryFailed = true;
    });

    $taskRule->validate('task_id', null, function($message) use (&$taskFailed) {
        $taskFailed = true;
    });

    expect($categoryFailed)->toBeTrue();
    expect($taskFailed)->toBeTrue();
});

test('rules handle string values gracefully', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $categoryRule = new CategoryOwnership();
    $taskRule = new TaskOwnership();

    $categoryFailed = false;
    $taskFailed = false;

    $categoryRule->validate('category_id', 'invalid', function($message) use (&$categoryFailed) {
        $categoryFailed = true;
    });

    $taskRule->validate('task_id', 'invalid', function($message) use (&$taskFailed) {
        $taskFailed = true;
    });

    expect($categoryFailed)->toBeTrue();
    expect($taskFailed)->toBeTrue();
});
