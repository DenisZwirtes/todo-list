<?php

use Illuminate\Foundation\Testing\RefreshDatabase;

// Configurar ambiente de teste
putenv('APP_ENV=testing');

uses(RefreshDatabase::class)->in('Feature');
uses(RefreshDatabase::class)->in('Unit');

// Configurações globais do Pest
expect()->extend('toBeValidTask', function () {
    return $this->toHaveKeys(['id', 'title', 'description', 'completed', 'user_id']);
});

expect()->extend('toBeValidUser', function () {
    return $this->toHaveKeys(['id', 'name', 'email']);
});

// Configurar o Laravel para os testes
uses(\Tests\TestCase::class)->in('Feature');
uses(\Tests\TestCase::class)->in('Unit');

// Helper para desabilitar CSRF em testes
function disableCsrf() {
    return test()->withoutMiddleware(\App\Http\Middleware\VerifyCsrfToken::class);
}
