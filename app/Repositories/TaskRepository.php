<?php

namespace App\Repositories;

use App\Models\Task;
use App\Repositories\Interfaces\TaskRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;

class TaskRepository implements TaskRepositoryInterface
{
    public function listByUser(int $userId, array $filters = []): LengthAwarePaginator
    {
        $query = Task::whereHas('users', function ($q) use ($userId) {
            $q->where('user_id', $userId);
        })->with(['category', 'users']);

        // Aplicar filtros
        if (isset($filters['category_id']) && $filters['category_id']) {
            $query->where('category_id', $filters['category_id']);
        }

        if (isset($filters['is_completed'])) {
            $query->where('is_completed', $filters['is_completed']);
        }

        if (isset($filters['search']) && $filters['search']) {
            $query->where(function ($q) use ($filters) {
                $q->where('title', 'like', '%' . $filters['search'] . '%')
                  ->orWhere('description', 'like', '%' . $filters['search'] . '%');
            });
        }

        // Ordenação
        $query->orderBy('created_at', 'desc');

        return $query->paginate(15);
    }

    public function create(array $data): Task
    {
        $task = Task::create($data);

        // Sincronizar usuários atribuídos
        if (isset($data['assigned_users']) && is_array($data['assigned_users'])) {
            $task->users()->sync($data['assigned_users']);
        }

        return $task->load(['category', 'users']);
    }

    public function update(int $id, array $data): Task
    {
        $task = Task::findOrFail($id);
        $task->update($data);

        // Sincronizar usuários atribuídos
        if (isset($data['assigned_users'])) {
            $task->users()->sync($data['assigned_users'] ?? []);
        }

        return $task->load(['category', 'users']);
    }

    public function delete(int $id): bool
    {
        $task = Task::findOrFail($id);

        // Remover relacionamentos
        $task->users()->detach();

        return $task->delete();
    }

    public function findById(int $id): ?Task
    {
        return Task::with(['category', 'users'])->find($id);
    }

    public function markAsCompleted(int $id): Task
    {
        $task = Task::findOrFail($id);
        $task->update(['is_completed' => true]);

        return $task->load(['category', 'users']);
    }

    public function deleteOldCompleted(): int
    {
        $oneWeekAgo = now()->subWeek();

        return Task::where('is_completed', true)
            ->where('updated_at', '<', $oneWeekAgo)
            ->delete();
    }
}
