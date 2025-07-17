<?php

namespace App\Enums;

enum LogOperation: string
{
    case CREATE = 'criar';
    case READ = 'listar';
    case UPDATE = 'atualizar';
    case DELETE = 'excluir';
    case VALIDATE = 'validar';

    /**
     * Retorna o nome legível da operação
     */
    public function label(): string
    {
        return match($this) {
            self::CREATE => 'Criar',
            self::READ => 'Listar',
            self::UPDATE => 'Atualizar',
            self::DELETE => 'Excluir',
            self::VALIDATE => 'Validar',
        };
    }

    /**
     * Retorna todas as operações disponíveis
     */
    public static function all(): array
    {
        return [
            self::CREATE,
            self::READ,
            self::UPDATE,
            self::DELETE,
            self::VALIDATE,
        ];
    }
}
