<?php

use App\Models\Log;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use function Pest\Laravel\artisan;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

test('clean old logs command removes logs older than specified days', function () {
    // Criar logs antigos
    Log::factory()->create([
        'created_at' => now()->subDays(40),
    ]);

    // Criar logs recentes
    Log::factory()->create([
        'created_at' => now()->subDays(10),
    ]);

    artisan('logs:clean', ['--days' => 30])
        ->expectsOutput('Removendo logs mais antigos que 30 dias...')
        ->expectsOutput('1 logs foram removidos.')
        ->assertExitCode(0);

    expect(Log::count())->toBe(1);
});

test('clean old logs command uses default 30 days', function () {
    // Criar logs antigos
    Log::factory()->create([
        'created_at' => now()->subDays(40),
    ]);

    // Criar logs recentes
    Log::factory()->create([
        'created_at' => now()->subDays(10),
    ]);

    artisan('logs:clean')
        ->expectsOutput('Removendo logs mais antigos que 30 dias...')
        ->expectsOutput('1 logs foram removidos.')
        ->assertExitCode(0);

    expect(Log::count())->toBe(1);
});

test('hash user passwords command exists', function () {
    artisan('users:hash-passwords')
        ->assertExitCode(0);
});

test('rehash user passwords command exists', function () {
    artisan('users:rehash-passwords')
        ->assertExitCode(0);
});
