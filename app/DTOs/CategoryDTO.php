<?php

namespace App\DTOs;

class CategoryDTO
{
    public function __construct(
        public string $name,
        public ?string $description = null,
        public ?string $color = null,
        public ?int $user_id = null
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
            name: $validated['name'],
            description: $validated['description'] ?? null,
            color: $validated['color'] ?? null,
            user_id: $validated['user_id'] ?? null
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
            'name' => $this->name,
            'description' => $this->description,
            'color' => $this->color,
            'user_id' => $this->user_id,
        ];
    }

    /**
     * Regras de validação para criação de categoria.
     *
     * @return array
     */
    public static function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'color' => 'nullable|string|max:7|regex:/^#[0-9A-F]{6}$/i',
        ];
    }

    /**
     * Regras de validação para atualização de categoria.
     *
     * @param int $categoryId
     * @return array
     */
    public static function updateRules(int $categoryId): array
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'color' => 'nullable|string|max:7|regex:/^#[0-9A-F]{6}$/i',
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
            'name.required' => 'O nome da categoria é obrigatório.',
            'name.max' => 'O nome da categoria não pode ter mais de 255 caracteres.',
            'description.max' => 'A descrição não pode ter mais de 500 caracteres.',
            'color.regex' => 'A cor deve estar no formato hexadecimal (ex: #FF0000).',
        ];
    }
}
