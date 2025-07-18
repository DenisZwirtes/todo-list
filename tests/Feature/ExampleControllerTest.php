<?php

use App\Models\User;
use App\Support\Logging\FluentLogger;
use App\Support\Logging\LoggerHelper;
use App\Enums\LogOperation;
use function Pest\Laravel\{get, post};

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->actingAs($this->user);
    disableCsrf();
});

test('example direct usage logs successfully', function () {
    $response = post('/example/direct-usage', [
        'test_data' => 'example_value'
    ]);

    $response->assertStatus(200);
    $response->assertJson(['message' => 'Operação realizada com sucesso']);
});

test('example with helper logs successfully', function () {
    $response = post('/example/helper', [
        'helper_data' => 'test_value'
    ]);

    $response->assertStatus(200);
    $response->assertJson(['message' => 'Operação com helper realizada']);
});

test('example validation logs successfully', function () {
    $response = post('/example/validation', [
        'validation_data' => 'test'
    ]);

    $response->assertStatus(200);
    $response->assertJson(['message' => 'Validação registrada']);
});

test('example listing logs successfully', function () {
    $response = post('/example/listing', [
        'listing_data' => 'test'
    ]);

    $response->assertStatus(200);
    $response->assertJson(['message' => 'Listagem registrada']);
});

test('example chained operations logs successfully', function () {
    $response = post('/example/chained', [
        'chained_data' => 'test'
    ]);

    $response->assertStatus(200);
    $response->assertJson(['message' => 'Operação encadeada realizada']);
});


