<?php

use App\Models\Task;
use Illuminate\Support\Carbon;
use function Pest\Laravel\artisan;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

test('it deletes tasks completed over a week ago', function () {
        Task::factory()->create([
            'is_completed' => true,
            'completed_at' => Carbon::now()->subWeeks(2),
        ]);

        Task::factory()->create([
            'is_completed' => true,
            'completed_at' => Carbon::now()->subDays(3),
        ]);

    artisan('tasks:delete-old-completed')
             ->expectsOutput('1 tarefas concluídas foram deletadas.')
             ->assertExitCode(0);

    expect(Task::count())->toBe(1);
});
