<?php

namespace App\Contracts\Services;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

interface RateLimiterServiceInterface
{
    /**
     * Verifica se a requisição deve ser limitada.
     *
     * @param Request $request
     * @return bool
     */
    public function shouldRateLimit(Request $request): bool;

    /**
     * Verifica se houve muitas tentativas.
     *
     * @param string $key
     * @param int $maxAttempts
     * @return bool
     */
    public function tooManyAttempts(string $key, int $maxAttempts): bool;

    /**
     * Obtém o tempo restante em segundos.
     *
     * @param string $key
     * @return int
     */
    public function availableIn(string $key): int;

    /**
     * Registra uma tentativa.
     *
     * @param string $key
     * @param int $decayMinutes
     * @return void
     */
    public function hit(string $key, int $decayMinutes): void;

    /**
     * Formata a mensagem de erro.
     *
     * @param int $seconds
     * @return string
     */
    public function formatMessage(int $seconds): string;

    /**
     * Processa a resposta de rate limit.
     *
     * @param Request $request
     * @param string $message
     * @return Response
     */
    public function handleRateLimitResponse(Request $request, string $message): Response;

    /**
     * Retorna o número de tentativas restantes.
     *
     * @param string $key
     * @param int $maxAttempts
     * @return int
     */
    public function remaining(string $key, int $maxAttempts): int;
}
