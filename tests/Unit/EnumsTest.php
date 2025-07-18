<?php

use App\Enums\LogOperation;
use App\Enums\Priority;
use App\Enums\TaskStatus;

test('log operation enum has all expected values', function () {
    expect(LogOperation::CREATE->value)->toBe('criar');
    expect(LogOperation::READ->value)->toBe('listar');
    expect(LogOperation::UPDATE->value)->toBe('atualizar');
    expect(LogOperation::DELETE->value)->toBe('excluir');
    expect(LogOperation::VALIDATE->value)->toBe('validar');
});

test('log operation enum can be used in array context', function () {
    $operations = [
        LogOperation::CREATE->value,
        LogOperation::READ->value,
        LogOperation::UPDATE->value,
        LogOperation::DELETE->value,
        LogOperation::VALIDATE->value
    ];

    expect($operations)->toHaveCount(5);
    expect($operations)->toContain('criar');
    expect($operations)->toContain('listar');
    expect($operations)->toContain('atualizar');
    expect($operations)->toContain('excluir');
    expect($operations)->toContain('validar');
});

test('priority enum has all expected values', function () {
    expect(Priority::LOW->value)->toBe('low');
    expect(Priority::MEDIUM->value)->toBe('medium');
    expect(Priority::HIGH->value)->toBe('high');
    expect(Priority::URGENT->value)->toBe('urgent');
});

test('priority enum can be used in array context', function () {
    $priorities = [
        Priority::LOW->value,
        Priority::MEDIUM->value,
        Priority::HIGH->value,
        Priority::URGENT->value
    ];

    expect($priorities)->toHaveCount(4);
    expect($priorities)->toContain('low');
    expect($priorities)->toContain('medium');
    expect($priorities)->toContain('high');
    expect($priorities)->toContain('urgent');
});

test('task status enum has all expected values', function () {
    expect(TaskStatus::PENDING->value)->toBe('pending');
    expect(TaskStatus::IN_PROGRESS->value)->toBe('in_progress');
    expect(TaskStatus::COMPLETED->value)->toBe('completed');
    expect(TaskStatus::CANCELLED->value)->toBe('cancelled');
});

test('task status enum can be used in array context', function () {
    $statuses = [
        TaskStatus::PENDING->value,
        TaskStatus::IN_PROGRESS->value,
        TaskStatus::COMPLETED->value,
        TaskStatus::CANCELLED->value
    ];

    expect($statuses)->toHaveCount(4);
    expect($statuses)->toContain('pending');
    expect($statuses)->toContain('in_progress');
    expect($statuses)->toContain('completed');
    expect($statuses)->toContain('cancelled');
});

test('enums can be compared', function () {
    expect(LogOperation::CREATE)->toBe(LogOperation::CREATE);
    expect(LogOperation::CREATE)->not->toBe(LogOperation::UPDATE);

    expect(Priority::HIGH)->toBe(Priority::HIGH);
    expect(Priority::HIGH)->not->toBe(Priority::LOW);

    expect(TaskStatus::COMPLETED)->toBe(TaskStatus::COMPLETED);
    expect(TaskStatus::COMPLETED)->not->toBe(TaskStatus::PENDING);
});

test('enums can be used in switch statements', function () {
    $operation = LogOperation::CREATE;
    $result = '';

    switch ($operation) {
        case LogOperation::CREATE:
            $result = 'creating';
            break;
        case LogOperation::UPDATE:
            $result = 'updating';
            break;
        default:
            $result = 'other';
    }

    expect($result)->toBe('creating');
});

test('enums can be used in conditional statements', function () {
    $priority = Priority::HIGH;

    if ($priority === Priority::HIGH || $priority === Priority::URGENT) {
        $isHighPriority = true;
    } else {
        $isHighPriority = false;
    }

    expect($isHighPriority)->toBeTrue();
});
