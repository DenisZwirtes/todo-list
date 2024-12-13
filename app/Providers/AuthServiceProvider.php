<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * O mapeamento das policies para os modelos da aplicação.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        \App\Models\Task::class => \App\Policies\TaskPolicy::class,
        \App\Models\Category::class => \App\Policies\CategoryPolicy::class,
    ];

    /**
     * Registra quaisquer serviços de autenticação/autorizações.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        // Defina Gates ou lógicas adicionais aqui
    }
}
