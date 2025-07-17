<?php

namespace App\Enums;

enum Priority: string
{
    case LOW = 'low';
    case MEDIUM = 'medium';
    case HIGH = 'high';
    case URGENT = 'urgent';

    /**
     * Retorna o label em português da prioridade.
     *
     * @return string
     */
    public function label(): string
    {
        return match($this) {
            self::LOW => 'Baixa',
            self::MEDIUM => 'Média',
            self::HIGH => 'Alta',
            self::URGENT => 'Urgente',
        };
    }

    /**
     * Retorna a cor da prioridade.
     *
     * @return string
     */
    public function color(): string
    {
        return match($this) {
            self::LOW => 'gray',
            self::MEDIUM => 'blue',
            self::HIGH => 'orange',
            self::URGENT => 'red',
        };
    }

    /**
     * Retorna o valor numérico da prioridade.
     *
     * @return int
     */
    public function value(): int
    {
        return match($this) {
            self::LOW => 1,
            self::MEDIUM => 2,
            self::HIGH => 3,
            self::URGENT => 4,
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
            self::LOW->value => self::LOW->label(),
            self::MEDIUM->value => self::MEDIUM->label(),
            self::HIGH->value => self::HIGH->label(),
            self::URGENT->value => self::URGENT->label(),
        ];
    }
}
