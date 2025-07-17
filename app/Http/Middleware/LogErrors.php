<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;
use App\Contracts\Services\LogServiceInterface;

class LogErrors
{
    public function __construct(
        protected LogServiceInterface $logService
    ) {}

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Log de erros de segurança
        if ($response->getStatusCode() >= 400) {
            $this->logSecurityEvent($request, $response);
        }

        return $response;
    }

    /**
     * Registra eventos de segurança
     *
     * @param Request $request
     * @param Response $response
     * @return void
     */
    protected function logSecurityEvent(Request $request, Response $response): void
    {
        $logData = [
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'method' => $request->method(),
            'url' => $request->fullUrl(),
            'status_code' => $response->getStatusCode(),
            'user_id' => $request->user()?->id,
            'timestamp' => now()->toISOString(),
        ];

        // Log específico para diferentes tipos de erro
        if ($response->getStatusCode() === 403) {
            $this->logService->warning('Acesso negado', $logData);
        } elseif ($response->getStatusCode() === 429) {
            $this->logService->warning('Rate limit excedido', $logData);
        } elseif ($response->getStatusCode() >= 500) {
            $this->logService->error('Erro do servidor', [], null);
        } else {
            $this->logService->info('Requisição com erro', $logData);
        }
    }
}
