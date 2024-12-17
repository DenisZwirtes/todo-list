<?php

namespace Tests\Feature;

use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class DeleteOldCompletedTasksTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_deletes_tasks_completed_over_a_week_ago()
    {
        Task::factory()->create([
            'is_completed' => true,
            'completed_at' => Carbon::now()->subWeeks(2),
        ]);

        Task::factory()->create([
            'is_completed' => true,
            'completed_at' => Carbon::now()->subDays(3),
        ]);

        $this->artisan('tasks:delete-old-completed')
             ->expectsOutput('1 tarefas concluídas foram deletadas.')
             ->assertExitCode(0);

        $this->assertDatabaseCount('tasks', 1);
    }
}
