<?php

namespace App\DTOs;

class TaskDTO
{
    public function __construct(
        public string $title,
        public ?string $description = null,
        public bool $is_completed = false,
        public ?int $category_id = null,
        public ?array $assigned_users = null
    ) {}

    /**
     * Cria um DTO a partir dos dados validados.
     *
     * @param array $validated
     * @return self
     */
    public static function fromValidated(array $validated): self
    {
        return new self(
            title: $validated['title'],
            description: $validated['description'] ?? null,
            is_completed: $validated['is_completed'] ?? false,
            category_id: $validated['category_id'] ?? null,
            assigned_users: $validated['assigned_users'] ?? null
        );
    }

    /**
     * Converte o DTO para array.
     *
     * @return array
     */
    public function toArray(): array
    {
        return [
            'title' => $this->title,
            'description' => $this->description,
            'is_completed' => $this->is_completed,
            'category_id' => $this->category_id,
            'assigned_users' => $this->assigned_users,
        ];
    }

    /**
     * Regras de validação para criação de tarefa.
     *
     * @return array
     */
    public static function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'is_completed' => 'boolean',
            'category_id' => 'nullable|exists:categories,id',
            'assigned_users' => 'nullable|array',
            'assigned_users.*' => 'exists:users,id',
        ];
    }

    /**
     * Regras de validação para atualização de tarefa.
     *
     * @param int $taskId
     * @return array
     */
    public static function updateRules(int $taskId): array
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'is_completed' => 'boolean',
            'category_id' => 'nullable|exists:categories,id',
            'assigned_users' => 'nullable|array',
            'assigned_users.*' => 'exists:users,id',
        ];
    }

    /**
     * Mensagens de validação personalizadas.
     *
     * @return array
     */
    public static function messages(): array
    {
        return [
            'title.required' => 'O título da tarefa é obrigatório.',
            'title.max' => 'O título da tarefa não pode ter mais de 255 caracteres.',
            'description.max' => 'A descrição não pode ter mais de 1000 caracteres.',
            'is_completed.boolean' => 'O status de conclusão deve ser verdadeiro ou falso.',
            'category_id.exists' => 'A categoria selecionada não existe.',
            'assigned_users.array' => 'Os usuários atribuídos devem ser uma lista.',
            'assigned_users.*.exists' => 'Um dos usuários atribuídos não existe.',
        ];
    }
}
