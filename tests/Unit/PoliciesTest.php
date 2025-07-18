<?php

use App\Models\User;
use App\Models\Category;
use App\Models\Task;
use App\Policies\CategoryPolicy;
use App\Policies\TaskPolicy;

test('category policy allows user to view own categories', function () {
    $user = User::factory()->create();
    $category = Category::factory()->create(['user_id' => $user->id]);
    $policy = new CategoryPolicy();

    expect($policy->view($user, $category))->toBeTrue();
});

test('category policy denies user to view other users categories', function () {
    $user1 = User::factory()->create();
    $user2 = User::factory()->create();
    $category = Category::factory()->create(['user_id' => $user2->id]);
    $policy = new CategoryPolicy();

    expect($policy->view($user1, $category))->toBeFalse();
});

test('category policy allows user to create categories', function () {
    $user = User::factory()->create();
    $policy = new CategoryPolicy();

    expect($policy->create($user))->toBeTrue();
});

test('category policy allows user to update own categories', function () {
    $user = User::factory()->create();
    $category = Category::factory()->create(['user_id' => $user->id]);
    $policy = new CategoryPolicy();

    expect($policy->update($user, $category))->toBeTrue();
});

test('category policy denies user to update other users categories', function () {
    $user1 = User::factory()->create();
    $user2 = User::factory()->create();
    $category = Category::factory()->create(['user_id' => $user2->id]);
    $policy = new CategoryPolicy();

    expect($policy->update($user1, $category))->toBeFalse();
});

test('category policy allows user to delete own categories', function () {
    $user = User::factory()->create();
    $category = Category::factory()->create(['user_id' => $user->id]);
    $policy = new CategoryPolicy();

    expect($policy->delete($user, $category))->toBeTrue();
});

test('category policy denies user to delete other users categories', function () {
    $user1 = User::factory()->create();
    $user2 = User::factory()->create();
    $category = Category::factory()->create(['user_id' => $user2->id]);
    $policy = new CategoryPolicy();

    expect($policy->delete($user1, $category))->toBeFalse();
});

test('task policy allows user to view own tasks', function () {
    $user = User::factory()->create();
    $task = Task::factory()->create();
    $user->tasks()->attach($task->id);
    $policy = new TaskPolicy();

    expect($policy->view($user, $task))->toBeTrue();
});

test('task policy denies user to view other users tasks', function () {
    $user1 = User::factory()->create();
    $user2 = User::factory()->create();
    $task = Task::factory()->create();
    $user2->tasks()->attach($task->id);
    $policy = new TaskPolicy();

    expect($policy->view($user1, $task))->toBeFalse();
});

test('task policy allows user to create tasks', function () {
    $user = User::factory()->create();
    $policy = new TaskPolicy();

    expect($policy->create($user))->toBeTrue();
});

test('task policy allows user to update own tasks', function () {
    $user = User::factory()->create();
    $task = Task::factory()->create();
    $user->tasks()->attach($task->id);
    $policy = new TaskPolicy();

    expect($policy->update($user, $task))->toBeTrue();
});

test('task policy denies user to update other users tasks', function () {
    $user1 = User::factory()->create();
    $user2 = User::factory()->create();
    $task = Task::factory()->create();
    $user2->tasks()->attach($task->id);
    $policy = new TaskPolicy();

    expect($policy->update($user1, $task))->toBeFalse();
});

test('task policy allows user to delete own tasks', function () {
    $user = User::factory()->create();
    $task = Task::factory()->create();
    $user->tasks()->attach($task->id);
    $policy = new TaskPolicy();

    expect($policy->delete($user, $task))->toBeTrue();
});

test('task policy denies user to delete other users tasks', function () {
    $user1 = User::factory()->create();
    $user2 = User::factory()->create();
    $task = Task::factory()->create();
    $user2->tasks()->attach($task->id);
    $policy = new TaskPolicy();

    expect($policy->delete($user1, $task))->toBeFalse();
});


