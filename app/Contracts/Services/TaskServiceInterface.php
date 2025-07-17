<?php

namespace App\Contracts\Services;

use App\DTOs\TaskDTO;
use App\Models\Task;
use Illuminate\Pagination\LengthAwarePaginator;

interface TaskServiceInterface
{
    /**
     * Lista todas as tarefas do usuário autenticado.
     *
     * @param array $filters
     * @return LengthAwarePaginator
     */
    public function listUserTasks(array $filters = []): LengthAwarePaginator;

    /**
     * Cria uma nova tarefa.
     *
     * @param TaskDTO $taskDTO
     * @return Task
     */
    public function create(TaskDTO $taskDTO): Task;

    /**
     * Atualiza uma tarefa.
     *
     * @param int $taskId
     * @param TaskDTO $taskDTO
     * @return Task
     */
    public function update(int $taskId, TaskDTO $taskDTO): Task;

    /**
     * Remove uma tarefa.
     *
     * @param int $taskId
     * @return bool
     */
    public function delete(int $taskId): bool;

    /**
     * Encontra uma tarefa por ID.
     *
     * @param int $taskId
     * @return Task|null
     */
    public function findById(int $taskId): ?Task;

    /**
     * Marca uma tarefa como concluída.
     *
     * @param int $taskId
     * @return Task
     */
    public function markAsCompleted(int $taskId): Task;

    /**
     * Remove tarefas concluídas há mais de uma semana.
     *
     * @return int
     */
    public function deleteOldCompleted(): int;
}
