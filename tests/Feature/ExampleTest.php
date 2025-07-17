<?php

use function Pest\Laravel\get;

test('example', function () {
    $response = get('/');

    $response->assertStatus(302); // Redireciona para login
    $response->assertRedirect('/login');
});

test('home page loads', function () {
    $response = get('/');

    $response->assertStatus(302); // Redireciona para login
    $response->assertRedirect('/login');
});

test('tasks page requires authentication', function () {
    $this->expectException(\Illuminate\Auth\AuthenticationException::class);
    get('/tasks');
});
