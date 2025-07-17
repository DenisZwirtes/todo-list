<?php

use App\Models\User;
use App\Models\Task;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

test('it can associate users with tasks', function () {
    $user1 = User::factory()->create(['name' => 'Usuário 1']);
    $user2 = User::factory()->create(['name' => 'Usuário 2']);

    $task = Task::factory()->create();

    $task->users()->attach([$user1->id, $user2->id]);

    $task->load('users');
    $user1->load('tasks');
    $user2->load('tasks');

    // Verifica se os usuários estão associados à tarefa
    expect($task->users->pluck('id')->toArray())->toContain($user1->id);
    expect($task->users->pluck('id')->toArray())->toContain($user2->id);

    // Verifica se a tarefa está associada aos usuários
    expect($user1->tasks->pluck('id')->toArray())->toContain($task->id);
    expect($user2->tasks->pluck('id')->toArray())->toContain($task->id);
});
