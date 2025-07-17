<?php

test('o ambiente deve ser testing', function () {
    expect(app()->environment())->toBe('testing');
});
