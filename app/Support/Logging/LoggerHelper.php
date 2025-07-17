<?php

namespace App\Support\Logging;

use App\Enums\LogOperation;
use Illuminate\Http\Request;

class LoggerHelper
{
    /**
     * Cria uma nova instância do FluentLogger
     */
    public static function create(): FluentLogger
    {
        return new FluentLogger();
    }

    /**
     * Helper para operações CRUD
     */
    public static function crud(LogOperation $operation, string $model, $id = null): FluentLogger
    {
        return (new FluentLogger())->crud($operation, $model, $id);
    }

    /**
     * Helper para criação
     */
    public static function createModel(string $model, $id = null): FluentLogger
    {
        return (new FluentLogger())->crud(LogOperation::CREATE, $model, $id);
    }

    /**
     * Helper para leitura
     */
    public static function readModel(string $model, $id = null): FluentLogger
    {
        return (new FluentLogger())->crud(LogOperation::READ, $model, $id);
    }

    /**
     * Helper para atualização
     */
    public static function updateModel(string $model, $id = null): FluentLogger
    {
        return (new FluentLogger())->crud(LogOperation::UPDATE, $model, $id);
    }

    /**
     * Helper para exclusão
     */
    public static function deleteModel(string $model, $id = null): FluentLogger
    {
        return (new FluentLogger())->crud(LogOperation::DELETE, $model, $id);
    }

    /**
     * Helper para validação
     */
    public static function validateModel(string $model, array $errors = []): FluentLogger
    {
        return (new FluentLogger())->validation($model, $errors);
    }

    /**
     * Helper para listagem
     */
    public static function listModel(string $model): FluentLogger
    {
        return (new FluentLogger())->listing($model);
    }
}
