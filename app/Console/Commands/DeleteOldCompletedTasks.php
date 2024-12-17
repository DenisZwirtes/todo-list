<?php

namespace App\Console\Commands;

use App\Models\Task;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class DeleteOldCompletedTasks extends Command
{
    protected $signature = 'tasks:delete-old-completed';
    protected $description = 'Deleta tarefas concluídas há mais de uma semana';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $oneWeekAgo = Carbon::now()->subWeek();

        $deleted = Task::where('is_completed', true)
                       ->where('completed_at', '<=', $oneWeekAgo)
                       ->delete();

        $this->info("{$deleted} tarefas concluídas foram deletadas.");
    }
}
