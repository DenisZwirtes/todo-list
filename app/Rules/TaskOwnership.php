<?php

namespace App\Rules;

use App\Models\Task;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class TaskOwnership implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $task = Task::find($value);

        if (!$task) {
            $fail('A tarefa não foi encontrada.');
            return;
        }

        if ($task->user_id !== auth()->id()) {
            $fail('Esta tarefa não pertence ao usuário autenticado.');
        }
    }
}
