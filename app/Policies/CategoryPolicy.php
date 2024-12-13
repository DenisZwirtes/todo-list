<?php

namespace App\Policies;

use App\Models\Category;
use App\Models\User;

class CategoryPolicy
{
    /**
     * Verifica se o usuário pode visualizar a categoria.
     */
    public function view(User $user, Category $category): bool
    {
        return $category->user_id === $user->id;
    }

    /**
     * Verifica se o usuário pode criar uma categoria.
     */
    public function create(User $user): bool
    {
        return true; // Todos os usuários autenticados podem criar categorias
    }

    /**
     * Verifica se o usuário pode atualizar a categoria.
     */
    public function update(User $user, Category $category): bool
    {
        return $category->user_id === $user->id;
    }

    /**
     * Verifica se o usuário pode excluir a categoria.
     */
    public function delete(User $user, Category $category): bool
    {
        return $category->user_id === $user->id;
    }
}
