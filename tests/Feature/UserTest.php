<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use function Pest\Laravel\{get, post};

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

beforeEach(function () {
    // Desabilita CSRF para este teste
    disableCsrf();
});

test('it registers a new user', function () {
    $data = [
        'name' => 'Denis Teste',
        'email' => 'denis@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ];

    $response = post(route('register'), $data);

    expect(User::where('email', 'denis@example.com')->exists())->toBeTrue();
    $response->assertRedirect(route('home'));
});

test('it logs in a user with valid credentials', function () {
    $user = User::factory()->create([
        'password' => Hash::make('password'),
    ]);

    $response = post(route('login'), [
        'email' => $user->email,
        'password' => 'password',
    ]);

    $this->assertAuthenticatedAs($user);
    $response->assertRedirect(route('home'));
});

test('it fails to log in with invalid credentials', function () {
    User::factory()->create([
        'email' => 'denis@example.com',
        'password' => Hash::make('password'),
    ]);

    $this->expectException(\Illuminate\Validation\ValidationException::class);

    post(route('login'), [
        'email' => 'denis@example.com',
        'password' => 'wrong-password',
    ]);
});

test('it denies access to unauthenticated users', function () {
    $this->expectException(\Illuminate\Auth\AuthenticationException::class);
    get(route('home'));
});
