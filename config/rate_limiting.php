<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Rate Limiting Configuration
    |--------------------------------------------------------------------------
    |
    | Aqui você pode configurar os limites de taxa para diferentes partes
    | da sua aplicação. Os valores são em tentativas por minuto.
    |
    */

    'auth' => [
        'enabled' => true,
        'max_attempts' => 10,
        'decay_minutes' => 5,
    ],

    'api' => [
        'enabled' => true,
        'max_attempts' => 60,
        'decay_minutes' => 1,
    ],

    'password_reset' => [
        'enabled' => true,
        'max_attempts' => 5,
        'decay_minutes' => 15,
    ],

    'email_verification' => [
        'enabled' => true,
        'max_attempts' => 10,
        'decay_minutes' => 5,
    ],
];
