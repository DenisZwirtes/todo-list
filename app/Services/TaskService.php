<?php

namespace App\Services;

use App\Contracts\Services\TaskServiceInterface;
use App\DTOs\TaskDTO;
use App\Models\Task;
use App\Repositories\Interfaces\TaskRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Exception;

class TaskService implements TaskServiceInterface
{
    protected $taskRepository;

    public function __construct(TaskRepositoryInterface $taskRepository)
    {
        $this->taskRepository = $taskRepository;
    }

    public function listUserTasks(array $filters = []): LengthAwarePaginator
    {
        $user = Auth::user();

        if (!$user) {
            throw new Exception('Usuário não autenticado');
        }

        return $this->taskRepository->listByUser($user->id, $filters);
    }

    public function create(TaskDTO $taskDTO): Task
    {
        $user = Auth::user();

        if (!$user) {
            throw new Exception('Usuário não autenticado');
        }

        $data = $taskDTO->toArray();
        $data['assigned_users'] = [$user->id];

        return $this->taskRepository->create($data);
    }

    public function update(int $taskId, TaskDTO $taskDTO): Task
    {
        $user = Auth::user();

        if (!$user) {
            throw new Exception('Usuário não autenticado');
        }

        // Verificar se a tarefa pertence ao usuário
        $task = $this->taskRepository->findById($taskId);

        if (!$task || !$task->users->contains($user->id)) {
            throw new Exception('Tarefa não encontrada ou não pertence ao usuário');
        }

        $data = $taskDTO->toArray();

        return $this->taskRepository->update($taskId, $data);
    }

    public function delete(int $taskId): bool
    {
        $user = Auth::user();

        if (!$user) {
            throw new Exception('Usuário não autenticado');
        }

        // Verificar se a tarefa pertence ao usuário
        $task = $this->taskRepository->findById($taskId);

        if (!$task || !$task->users->contains($user->id)) {
            throw new Exception('Tarefa não encontrada ou não pertence ao usuário');
        }

        return $this->taskRepository->delete($taskId);
    }

    public function findById(int $taskId): ?Task
    {
        $user = Auth::user();

        if (!$user) {
            throw new Exception('Usuário não autenticado');
        }

        $task = $this->taskRepository->findById($taskId);

        if (!$task || !$task->users->contains($user->id)) {
            return null;
        }

        return $task;
    }

    public function markAsCompleted(int $taskId): Task
    {
        $user = Auth::user();

        if (!$user) {
            throw new Exception('Usuário não autenticado');
        }

        // Verificar se a tarefa pertence ao usuário
        $task = $this->taskRepository->findById($taskId);

        if (!$task || !$task->users->contains($user->id)) {
            throw new Exception('Tarefa não encontrada ou não pertence ao usuário');
        }

        return $this->taskRepository->markAsCompleted($taskId);
    }

    public function deleteOldCompleted(): int
    {
        return $this->taskRepository->deleteOldCompleted();
    }
}
