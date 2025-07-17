<?php

use function Pest\Laravel\get;

test('example', function () {
    $response = get('/');

    $response->assertStatus(200);
});

test('home page loads', function () {
    $response = get('/');

    $response->assertStatus(200);
    $response->assertSee('Todo List');
});

test('tasks page requires authentication', function () {
    $response = get('/tasks');

    $response->assertRedirect('/login');
});
