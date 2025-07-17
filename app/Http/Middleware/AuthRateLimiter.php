<?php

namespace App\Http\Middleware;

use App\Contracts\Services\RateLimiterServiceInterface;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthRateLimiter
{
    /**
     * Rotas que devem ser limitadas
     *
     * @var array
     */
    protected array $limitedRoutes = [
        'login' => [
            'max_attempts' => 5,
            'decay_minutes' => 30,
            'by' => 'ip'
        ],
        'register' => [
            'max_attempts' => 3,
            'decay_minutes' => 60,
            'by' => 'ip'
        ],
        'password.email' => [
            'max_attempts' => 3,
            'decay_minutes' => 60,
            'by' => 'ip'
        ],
        'password.update' => [
            'max_attempts' => 3,
            'decay_minutes' => 60,
            'by' => 'ip'
        ],
        'verification.send' => [
            'max_attempts' => 3,
            'decay_minutes' => 60,
            'by' => 'ip'
        ],
    ];

    public function __construct(
        protected RateLimiterServiceInterface $rateLimiterService
    ) {}

    public function handle(Request $request, Closure $next): Response
    {
        if (!$this->rateLimiterService->shouldRateLimit($request)) {
            return $next($request);
        }

        $routeName = $request->route()?->getName();
        $config = $this->limitedRoutes[$routeName] ?? null;

        if (!$config) {
            return $next($request);
        }

        $key = $this->resolveRequestSignature($request, $config['by']);

        $this->rateLimiterService->hit($key, $config['decay_minutes'] * 60);

        if ($this->rateLimiterService->tooManyAttempts($key, $config['max_attempts'])) {
            $seconds = $this->rateLimiterService->availableIn($key);
            $message = $this->rateLimiterService->formatMessage($seconds);
            $this->rateLimiterService->remaining($key, $config['max_attempts']);
            return $this->rateLimiterService->handleRateLimitResponse($request, $message);
        }

        $response = $next($request);

        $response->headers->set('X-RateLimit-Limit', $config['max_attempts']);
        $response->headers->set('X-RateLimit-Remaining', $this->rateLimiterService->remaining($key, $config['max_attempts']));
        $response->headers->set('X-RateLimit-Reset', $this->rateLimiterService->availableIn($key));

        return $response;
    }

    /**
     * Resolve a chave de rate limiting baseada no tipo de identificação
     *
     * @param Request $request
     * @param string $by
     * @return string
     */
    protected function resolveRequestSignature(Request $request, string $by): string
    {
        $routeName = $request->route()?->getName();

        return match($by) {
            'ip' => 'auth:' . $routeName . ':' . $request->ip(),
            'user' => 'auth:' . $routeName . ':' . $request->user()?->id,
            'email' => 'auth:' . $routeName . ':' . $request->input('email'),
            default => 'auth:' . $routeName . ':' . $request->ip()
        };
    }
}
