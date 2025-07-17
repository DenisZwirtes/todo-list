<?php

namespace App\Services;

use App\Contracts\Services\RateLimiterServiceInterface;
use Illuminate\Cache\RateLimiter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use Inertia\Inertia;
use Symfony\Component\HttpFoundation\Response;

class RateLimiterService implements RateLimiterServiceInterface
{
    /**
     * Rotas que devem ser limitadas
     *
     * @var array
     */
    protected array $limitedRoutes = [
        'login',
        'register',
        'password.email',
        'password.update',
        'verification.send',
    ];

    public function __construct(
        protected RateLimiter $limiter
    ) {}

    public function shouldRateLimit(Request $request): bool
    {
        $currentRoute = $request->route()?->getName();

        if (!$currentRoute)
            return false;

        return in_array($currentRoute, $this->limitedRoutes);
    }

    public function tooManyAttempts(string $key, int $maxAttempts): bool
    {
        $config = config('rate_limiting.auth');

        if (!$config['enabled'])
            return false;

        return $this->limiter->tooManyAttempts($key, $maxAttempts);
    }

    public function availableIn(string $key): int
    {
        return $this->limiter->availableIn($key);
    }

    public function hit(string $key, int $decayMinutes): void
    {
        $this->limiter->hit($key, $decayMinutes);
    }

    public function formatMessage(int $seconds): string
    {
        if ($seconds >= 60) {
            $minutes = ceil($seconds / 60);
            return Lang::get('auth.throttle_minutes', ['minutes' => $minutes]);
        }

        return Lang::get('auth.throttle', ['seconds' => $seconds]);
    }

    public function handleRateLimitResponse(Request $request, string $message): Response
    {
        if ($request->wantsJson()) {
            return response()->json([
                'message' => $message,
                'retry_after' => $this->availableIn($request->ip())
            ], Response::HTTP_TOO_MANY_REQUESTS, [
                'Retry-After' => $this->availableIn($request->ip()),
                'X-RateLimit-Reset' => time() + $this->availableIn($request->ip())
            ]);
        }

        return Inertia::render('Auth/Login', [
            'status' => $message,
            'errors' => ['email' => [$message]]
        ])->toResponse($request)->setStatusCode(Response::HTTP_TOO_MANY_REQUESTS);
    }

    public function remaining(string $key, int $maxAttempts): int
    {
        return $this->limiter->remaining($key, $maxAttempts);
    }
}
