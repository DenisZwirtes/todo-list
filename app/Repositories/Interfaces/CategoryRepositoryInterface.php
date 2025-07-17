<?php

namespace App\Repositories\Interfaces;

use App\Models\Category;
use Illuminate\Pagination\LengthAwarePaginator;

interface CategoryRepositoryInterface
{
    /**
     * Lista todas as categorias do usuário.
     *
     * @param int $userId
     * @return LengthAwarePaginator
     */
    public function listByUser(int $userId): LengthAwarePaginator;

    /**
     * Cria uma nova categoria.
     *
     * @param array $data
     * @return Category
     */
    public function create(array $data): Category;

    /**
     * Atualiza uma categoria.
     *
     * @param int $id
     * @param array $data
     * @return Category
     */
    public function update(int $id, array $data): Category;

    /**
     * Remove uma categoria.
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool;

    /**
     * Encontra uma categoria por ID.
     *
     * @param int $id
     * @return Category|null
     */
    public function findById(int $id): ?Category;

    /**
     * Lista categorias para select.
     *
     * @param int $userId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function listForSelect(int $userId);
}
