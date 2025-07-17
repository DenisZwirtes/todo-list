<?php

namespace App\Repositories\Interfaces;

use App\Models\Task;
use Illuminate\Pagination\LengthAwarePaginator;

interface TaskRepositoryInterface
{
    /**
     * Lista todas as tarefas do usuário.
     *
     * @param int $userId
     * @param array $filters
     * @return LengthAwarePaginator
     */
    public function listByUser(int $userId, array $filters = []): LengthAwarePaginator;

    /**
     * Cria uma nova tarefa.
     *
     * @param array $data
     * @return Task
     */
    public function create(array $data): Task;

    /**
     * Atualiza uma tarefa.
     *
     * @param int $id
     * @param array $data
     * @return Task
     */
    public function update(int $id, array $data): Task;

    /**
     * Remove uma tarefa.
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool;

    /**
     * Encontra uma tarefa por ID.
     *
     * @param int $id
     * @return Task|null
     */
    public function findById(int $id): ?Task;

    /**
     * Marca uma tarefa como concluída.
     *
     * @param int $id
     * @return Task
     */
    public function markAsCompleted(int $id): Task;

    /**
     * Remove tarefas concluídas há mais de uma semana.
     *
     * @return int
     */
    public function deleteOldCompleted(): int;
}
