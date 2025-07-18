<?php

use App\Models\Log;
use App\Models\User;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;
use function Pest\Laravel\post;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

test('it displays logs index page', function () {
    /** @var User $user */
    $user = User::factory()->create();
    actingAs($user);

    $response = get('/logs');
    $response->assertStatus(200);
    $response->assertViewIs('logs.index');
});

test('it shows log details', function () {
    /** @var User $user */
    $user = User::factory()->create();
    $log = Log::factory()->create(['user_id' => $user->id]);
    actingAs($user);

    $response = get("/logs/{$log->id}");
    $response->assertStatus(200);
    $response->assertViewIs('logs.show');
    $response->assertViewHas('log', $log);
});

test('it clears old logs', function () {
    /** @var User $user */
    $user = User::factory()->create();
    actingAs($user);

    // Criar logs antigos
    Log::factory()->create([
        'created_at' => now()->subDays(40),
    ]);

    // Criar logs recentes
    Log::factory()->create([
        'created_at' => now()->subDays(10),
    ]);

    $response = post('/logs/clear', ['days' => 30]);
    $response->assertRedirect('/logs');
    $response->assertSessionHas('success', '1 logs antigos foram removidos.');

    expect(Log::count())->toBe(1);
});

test('it exports logs to csv', function () {
    /** @var User $user */
    $user = User::factory()->create();
    Log::factory()->count(3)->create(['user_id' => $user->id]);
    actingAs($user);

    $response = get('/logs/export');
    $response->assertStatus(200);
    $response->assertHeader('Content-Type', 'text/csv; charset=UTF-8');
});

test('it filters logs by level', function () {
    /** @var User $user */
    $user = User::factory()->create();
    Log::factory()->create(['level' => 'error', 'user_id' => $user->id]);
    Log::factory()->create(['level' => 'info', 'user_id' => $user->id]);
    actingAs($user);

    $response = get('/logs?level=error');
    $response->assertStatus(200);
    $response->assertViewHas('logs');

    $logs = $response->viewData('logs');
    expect($logs->count())->toBe(1);
    expect($logs->first()->level)->toBe('error');
});

test('it searches logs by message', function () {
    /** @var User $user */
    $user = User::factory()->create();
    Log::factory()->create([
        'message' => 'Test error message',
        'user_id' => $user->id
    ]);
    Log::factory()->create([
        'message' => 'Another message',
        'user_id' => $user->id
    ]);
    actingAs($user);

    $response = get('/logs?search=error');
    $response->assertStatus(200);
    $response->assertViewHas('logs');

    $logs = $response->viewData('logs');
    expect($logs->count())->toBe(1);
    expect($logs->first()->message)->toContain('error');
});

test('it filters logs by user', function () {
    /** @var User $user1 */
    $user1 = User::factory()->create();
    /** @var User $user2 */
    $user2 = User::factory()->create();

    Log::factory()->create(['user_id' => $user1->id]);
    Log::factory()->create(['user_id' => $user2->id]);

    actingAs($user1);

    $response = get("/logs?user_id={$user1->id}");
    $response->assertStatus(200);
    $response->assertViewHas('logs');

    $logs = $response->viewData('logs');
    expect($logs->count())->toBe(1);
    expect($logs->first()->user_id)->toBe($user1->id);
});

test('it shows log statistics', function () {
    /** @var User $user */
    $user = User::factory()->create();
    Log::factory()->create(['level' => 'error', 'user_id' => $user->id]);
    Log::factory()->create(['level' => 'warning', 'user_id' => $user->id]);
    Log::factory()->create(['level' => 'info', 'user_id' => $user->id]);
    actingAs($user);

    $response = get('/logs');
    $response->assertStatus(200);
    $response->assertViewHas('stats');

    $stats = $response->viewData('stats');
    expect($stats['total'])->toBe(3);
    expect($stats['errors'])->toBe(1);
    expect($stats['warnings'])->toBe(1);
    expect($stats['info'])->toBe(1);
});
