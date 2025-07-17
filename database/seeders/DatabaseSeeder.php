<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Ordem: Usuários -> Categorias -> Tarefas -> Vinculação
        $this->call([
            UserSeeder::class,
            CategorySeeder::class,
            TaskSeeder::class,
            TaskUserSeeder::class,
        ]);
    }
}
