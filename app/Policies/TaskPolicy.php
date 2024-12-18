<?php

namespace App\Policies;

use App\Models\Task;
use App\Models\User;

class TaskPolicy
{
    /**
     * Determina se o usuário pode visualizar qualquer tarefa.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determina se o usuário pode visualizar uma tarefa específica.
     */
    public function view(User $user, Task $task): bool
    {
        return $task->users->contains($user);
    }

    /**
     * Determina se o usuário pode criar uma nova tarefa.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determina se o usuário pode atualizar uma tarefa específica.
     */
    public function update(User $user, Task $task): bool
    {
        return $task->users->contains($user);
    }

    /**
     * Determina se o usuário pode deletar uma tarefa específica.
     */
    public function delete(User $user, Task $task): bool
    {
        return $task->users->contains($user);
    }

    /**
     * Determina se o usuário pode restaurar uma tarefa deletada.
     */
    public function restore(User $user, Task $task): bool
    {
        return $task->users->contains($user);
    }

    /**
     * Determina se o usuário pode forçar a exclusão de uma tarefa.
     */
    public function forceDelete(User $user, Task $task): bool
    {
        return $task->users->contains($user);
    }
}
