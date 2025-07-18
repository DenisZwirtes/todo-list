<?php

use App\Enums\LogOperation;
use App\Enums\Priority;
use App\Enums\TaskStatus;

test('log operation enum has all expected values', function () {
    expect(LogOperation::cases())->toHaveCount(5);
    expect(LogOperation::CREATE->value)->toBe('criar');
    expect(LogOperation::READ->value)->toBe('listar');
    expect(LogOperation::UPDATE->value)->toBe('atualizar');
    expect(LogOperation::DELETE->value)->toBe('excluir');
    expect(LogOperation::VALIDATE->value)->toBe('validar');
});

test('log operation enum can be used in array context', function () {
    $operations = LogOperation::cases();
    expect($operations)->toBeArray();
    expect($operations)->toHaveCount(5);
});

test('priority enum has all expected values', function () {
    expect(Priority::cases())->toHaveCount(4);
    expect(Priority::LOW->value)->toBe('low');
    expect(Priority::MEDIUM->value)->toBe('medium');
    expect(Priority::HIGH->value)->toBe('high');
    expect(Priority::URGENT->value)->toBe('urgent');
});

test('priority enum can be used in array context', function () {
    $priorities = Priority::cases();
    expect($priorities)->toBeArray();
    expect($priorities)->toHaveCount(4);
});

test('task status enum has all expected values', function () {
    expect(TaskStatus::cases())->toHaveCount(4);
    expect(TaskStatus::PENDING->value)->toBe('pending');
    expect(TaskStatus::IN_PROGRESS->value)->toBe('in_progress');
    expect(TaskStatus::COMPLETED->value)->toBe('completed');
    expect(TaskStatus::CANCELLED->value)->toBe('cancelled');
});

test('task status enum can be used in array context', function () {
    $statuses = TaskStatus::cases();
    expect($statuses)->toBeArray();
    expect($statuses)->toHaveCount(4);
});

test('enums can be compared', function () {
    expect(LogOperation::CREATE)->toBe(LogOperation::CREATE);
    expect(LogOperation::CREATE)->not->toBe(LogOperation::READ);

    expect(Priority::HIGH)->toBe(Priority::HIGH);
    expect(Priority::HIGH)->not->toBe(Priority::LOW);

    expect(TaskStatus::PENDING)->toBe(TaskStatus::PENDING);
    expect(TaskStatus::PENDING)->not->toBe(TaskStatus::COMPLETED);
});

test('enums can be used in switch statements', function () {
    $operation = LogOperation::CREATE;
    $result = match($operation) {
        LogOperation::CREATE => 'created',
        LogOperation::READ => 'read',
        LogOperation::UPDATE => 'updated',
        LogOperation::DELETE => 'deleted',
        LogOperation::VALIDATE => 'validated',
    };

    expect($result)->toBe('created');
});

test('enums can be used in conditional statements', function () {
    $priority = Priority::HIGH;

    if ($priority === Priority::HIGH) {
        expect($priority)->toBe(Priority::HIGH);
    }

    $status = TaskStatus::COMPLETED;
    if ($status === TaskStatus::COMPLETED) {
        expect($status)->toBe(TaskStatus::COMPLETED);
    }
});
