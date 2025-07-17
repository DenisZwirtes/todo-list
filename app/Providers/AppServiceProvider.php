<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Contracts\Services\TaskServiceInterface;
use App\Contracts\Services\CategoryServiceInterface;
use App\Services\TaskService;
use App\Services\CategoryService;
use App\Repositories\Interfaces\TaskRepositoryInterface;
use App\Repositories\Interfaces\CategoryRepositoryInterface;
use App\Repositories\TaskRepository;
use App\Repositories\CategoryRepository;
use App\Contracts\Services\RateLimiterServiceInterface;
use App\Contracts\Services\LogServiceInterface;
use App\Services\RateLimiterService;
use App\Services\LogService;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Registrar Repositories
        $this->app->bind(TaskRepositoryInterface::class, TaskRepository::class);
        $this->app->bind(CategoryRepositoryInterface::class, CategoryRepository::class);

        // Registrar Services
        $this->app->bind(TaskServiceInterface::class, TaskService::class);
        $this->app->bind(CategoryServiceInterface::class, CategoryService::class);
        $this->app->bind(RateLimiterServiceInterface::class, RateLimiterService::class);
        $this->app->bind(LogServiceInterface::class, LogService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Inertia::share('auth.user', function () {
            return Auth::user() ? [
                'name' => Auth::user()->name,
                'email' => Auth::user()->email,
            ] : null;
        });
    }
}
