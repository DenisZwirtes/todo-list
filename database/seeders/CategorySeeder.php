<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        // Para cada usuário, cria 3 categorias
        User::all()->each(function ($user) {
            Category::factory(3)->create([
                'user_id' => $user->id,
            ]);
        });
    }
}
