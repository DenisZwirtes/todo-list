<?php

namespace App\Contracts\Services;

use App\DTOs\CategoryDTO;
use App\Models\Category;
use Illuminate\Pagination\LengthAwarePaginator;

interface CategoryServiceInterface
{
    /**
     * Lista todas as categorias do usuário autenticado.
     *
     * @return LengthAwarePaginator
     */
    public function listUserCategories(): LengthAwarePaginator;

    /**
     * Cria uma nova categoria.
     *
     * @param CategoryDTO $categoryDTO
     * @return Category
     */
    public function create(CategoryDTO $categoryDTO): Category;

    /**
     * Atualiza uma categoria.
     *
     * @param int $categoryId
     * @param CategoryDTO $categoryDTO
     * @return Category
     */
    public function update(int $categoryId, CategoryDTO $categoryDTO): Category;

    /**
     * Remove uma categoria.
     *
     * @param int $categoryId
     * @return bool
     */
    public function delete(int $categoryId): bool;

    /**
     * Encontra uma categoria por ID.
     *
     * @param int $categoryId
     * @return Category|null
     */
    public function findById(int $categoryId): ?Category;

    /**
     * Lista categorias para select.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function listForSelect();
}
