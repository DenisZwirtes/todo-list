<?php

use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class)->in('Feature');
uses(RefreshDatabase::class)->in('Unit');

// Configurações globais do Pest
expect()->extend('toBeValidTask', function () {
    return $this->toHaveKeys(['id', 'title', 'description', 'completed', 'user_id']);
});

expect()->extend('toBeValidUser', function () {
    return $this->toHaveKeys(['id', 'name', 'email']);
});
