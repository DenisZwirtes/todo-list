<?php

namespace App\Repositories;

use App\Models\Category;
use App\Repositories\Interfaces\CategoryRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;

class CategoryRepository implements CategoryRepositoryInterface
{
    public function listByUser(int $userId): LengthAwarePaginator
    {
        return Category::where('user_id', $userId)
            ->withCount('tasks')
            ->orderBy('name')
            ->paginate(15);
    }

    public function create(array $data): Category
    {
        return Category::create($data);
    }

    public function update(int $id, array $data): Category
    {
        $category = Category::findOrFail($id);
        $category->update($data);

        return $category;
    }

    public function delete(int $id): bool
    {
        $category = Category::findOrFail($id);

        // Verificar se há tarefas associadas
        if ($category->tasks()->count() > 0) {
            throw new \Exception('Não é possível excluir uma categoria que possui tarefas associadas.');
        }

        return $category->delete();
    }

    public function findById(int $id): ?Category
    {
        return Category::withCount('tasks')->find($id);
    }

    public function listForSelect(int $userId)
    {
        return Category::where('user_id', $userId)
            ->select('id', 'name', 'color')
            ->orderBy('name')
            ->get();
    }
}
