<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Task;
use App\Models\User;

class TaskUserSeeder extends Seeder
{
    public function run(): void
    {
        $userIds = User::pluck('id')->all();
        Task::all()->each(function ($task) use ($userIds) {
            // Vincula cada tarefa a 1 a 3 usuários aleatórios
            $ids = collect($userIds)->shuffle()->take(rand(1, 3))->all();
            $task->users()->sync($ids);
        });
    }
}
