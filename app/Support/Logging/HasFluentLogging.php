<?php

namespace App\Support\Logging;

use App\Enums\LogOperation;
use Illuminate\Http\Request;

trait HasFluentLogging
{
    /**
     * Log de criação
     */
    protected function logCreate(string $model, $id = null, array $context = []): void
    {
        LoggerHelper::createModel($model, $id)
            ->context($context)
            ->log();
    }

    /**
     * Log de leitura
     */
    protected function logRead(string $model, $id = null, array $context = []): void
    {
        LoggerHelper::readModel($model, $id)
            ->context($context)
            ->log();
    }

    /**
     * Log de atualização
     */
    protected function logUpdate(string $model, $id = null, array $context = []): void
    {
        LoggerHelper::updateModel($model, $id)
            ->context($context)
            ->log();
    }

    /**
     * Log de exclusão
     */
    protected function logDelete(string $model, $id = null, array $context = []): void
    {
        LoggerHelper::deleteModel($model, $id)
            ->context($context)
            ->log();
    }

    /**
     * Log de validação
     */
    protected function logValidation(string $model, array $errors = [], array $context = []): void
    {
        LoggerHelper::validateModel($model, $errors)
            ->context($context)
            ->log();
    }

    /**
     * Log de listagem
     */
    protected function logListing(string $model, array $context = []): void
    {
        LoggerHelper::listModel($model)
            ->context($context)
            ->log();
    }

    /**
     * Log de erro com exceção
     */
    protected function logError(string $model, LogOperation $operation, \Throwable $exception, array $context = []): void
    {
        LoggerHelper::crud($operation, $model)
            ->exception($exception)
            ->context($context)
            ->log();
    }

    /**
     * Log de erro com request
     */
    protected function logErrorWithRequest(string $model, LogOperation $operation, Request $request, \Throwable $exception = null, array $context = []): void
    {
        $logger = LoggerHelper::crud($operation, $model)
            ->request($request)
            ->context($context);

        if ($exception) {
            $logger->exception($exception);
        }

        $logger->log();
    }
}
