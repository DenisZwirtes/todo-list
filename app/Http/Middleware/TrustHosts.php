<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TrustHosts
{
    /**
     * Hosts confiáveis permitidos
     */
    protected array $trustedHosts = [
        'localhost',
        '127.0.0.1',
        '::1',
        // Adicione seus domínios de produção aqui
        // 'seudominio.com',
        // 'www.seudominio.com',
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
        $host = $request->getHost();

        if (!in_array($host, $this->trustedHosts)) {
            abort(403, 'Host não autorizado');
        }

        return $next($request);
    }
}
