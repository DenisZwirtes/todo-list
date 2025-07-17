<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PreventCommandsInProduction
{
    /**
     * Comandos perigosos que devem ser bloqueados em produção
     */
    protected array $dangerousCommands = [
        'php artisan migrate:fresh',
        'php artisan migrate:reset',
        'php artisan db:seed',
        'php artisan cache:clear',
        'php artisan config:clear',
        'php artisan route:clear',
        'php artisan view:clear',
        'php artisan optimize:clear',
    ];

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (app()->environment('production')) {
            $this->checkForDangerousCommands($request);
        }

        return $next($request);
    }

    /**
     * Verifica se há comandos perigosos na requisição
     *
     * @param Request $request
     * @return void
     */
    protected function checkForDangerousCommands(Request $request): void
    {
        $input = $request->all();

        foreach ($this->dangerousCommands as $command) {
            if ($this->containsCommand($input, $command)) {
                abort(403, 'Comando não permitido em produção');
            }
        }
    }

    /**
     * Verifica se o input contém um comando perigoso
     *
     * @param array $input
     * @param string $command
     * @return bool
     */
    protected function containsCommand(array $input, string $command): bool
    {
        $inputString = json_encode($input);
        return stripos($inputString, $command) !== false;
    }
}
