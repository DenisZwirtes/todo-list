<?php

namespace App\Contracts\Services;

interface LogServiceInterface
{
    /**
     * Registra um log de erro
     *
     * @param string $message
     * @param array $context
     * @param \Throwable|null $exception
     * @return void
     */
    public function error(string $message, array $context = [], ?\Throwable $exception = null): void;

    /**
     * Registra um log de aviso
     *
     * @param string $message
     * @param array $context
     * @return void
     */
    public function warning(string $message, array $context = []): void;

    /**
     * Registra um log informativo
     *
     * @param string $message
     * @param array $context
     * @return void
     */
    public function info(string $message, array $context = []): void;

    /**
     * Registra um log de debug
     *
     * @param string $message
     * @param array $context
     * @return void
     */
    public function debug(string $message, array $context = []): void;
}
