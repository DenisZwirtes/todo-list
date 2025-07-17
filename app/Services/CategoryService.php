<?php

namespace App\Services;

use App\Contracts\Services\CategoryServiceInterface;
use App\DTOs\CategoryDTO;
use App\Models\Category;
use App\Repositories\Interfaces\CategoryRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Exception;

class CategoryService implements CategoryServiceInterface
{
    protected $categoryRepository;

    public function __construct(CategoryRepositoryInterface $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    public function listUserCategories(): LengthAwarePaginator
    {
        $user = Auth::user();

        if (!$user) {
            throw new Exception('Usuário não autenticado');
        }

        return $this->categoryRepository->listByUser($user->id);
    }

    public function create(CategoryDTO $categoryDTO): Category
    {
        $user = Auth::user();

        if (!$user) {
            throw new Exception('Usuário não autenticado');
        }

        $data = $categoryDTO->toArray();
        $data['user_id'] = $user->id;

        return $this->categoryRepository->create($data);
    }

    public function update(int $categoryId, CategoryDTO $categoryDTO): Category
    {
        $user = Auth::user();

        if (!$user) {
            throw new Exception('Usuário não autenticado');
        }

        // Verificar se a categoria pertence ao usuário
        $category = $this->categoryRepository->findById($categoryId);

        if (!$category || $category->user_id !== $user->id) {
            throw new Exception('Categoria não encontrada ou não pertence ao usuário');
        }

        $data = $categoryDTO->toArray();
        $data['user_id'] = $user->id;

        return $this->categoryRepository->update($categoryId, $data);
    }

    public function delete(int $categoryId): bool
    {
        $user = Auth::user();

        if (!$user) {
            throw new Exception('Usuário não autenticado');
        }

        // Verificar se a categoria pertence ao usuário
        $category = $this->categoryRepository->findById($categoryId);

        if (!$category || $category->user_id !== $user->id) {
            throw new Exception('Categoria não encontrada ou não pertence ao usuário');
        }

        return $this->categoryRepository->delete($categoryId);
    }

    public function findById(int $categoryId): ?Category
    {
        $user = Auth::user();

        if (!$user) {
            throw new Exception('Usuário não autenticado');
        }

        $category = $this->categoryRepository->findById($categoryId);

        if (!$category || $category->user_id !== $user->id) {
            return null;
        }

        return $category;
    }

    public function listForSelect()
    {
        $user = Auth::user();

        if (!$user) {
            throw new Exception('Usuário não autenticado');
        }

        return $this->categoryRepository->listForSelect($user->id);
    }
}
