<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Task;

class TaskSeeder extends Seeder
{
    public function run(): void
    {
        // Para cada categoria, cria 5 tarefas
        Category::all()->each(function ($category) {
            Task::factory(5)->create([
                'category_id' => $category->id,
            ]);
        });
    }
}
