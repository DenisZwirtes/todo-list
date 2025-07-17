<?php

namespace App\Enums;

enum TaskStatus: string
{
    case PENDING = 'pending';
    case IN_PROGRESS = 'in_progress';
    case COMPLETED = 'completed';
    case CANCELLED = 'cancelled';

    /**
     * Retorna o label em português do status.
     *
     * @return string
     */
    public function label(): string
    {
        return match($this) {
            self::PENDING => 'Pendente',
            self::IN_PROGRESS => 'Em Progresso',
            self::COMPLETED => 'Concluída',
            self::CANCELLED => 'Cancelada',
        };
    }

    /**
     * Retorna a cor do status.
     *
     * @return string
     */
    public function color(): string
    {
        return match($this) {
            self::PENDING => 'yellow',
            self::IN_PROGRESS => 'blue',
            self::COMPLETED => 'green',
            self::CANCELLED => 'red',
        };
    }

    /**
     * Retorna todos os valores do enum.
     *
     * @return array
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    /**
     * Retorna todos os labels do enum.
     *
     * @return array
     */
    public static function labels(): array
    {
        return [
            self::PENDING->value => self::PENDING->label(),
            self::IN_PROGRESS->value => self::IN_PROGRESS->label(),
            self::COMPLETED->value => self::COMPLETED->label(),
            self::CANCELLED->value => self::CANCELLED->label(),
        ];
    }
}
