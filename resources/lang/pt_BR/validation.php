<?php

return [
    'required' => 'O campo :attribute é obrigatório.',
    'max' => [
        'string' => 'O campo :attribute não pode ter mais de :max caracteres.',
    ],
    'min' => [
        'string' => 'O campo :attribute deve ter no mínimo :min caracteres.',
    ],
    'unique' => 'O :attribute já está em uso.',
    'exists' => 'O campo :attribute selecionado é inválido.',
    'email' => 'O campo :attribute deve ser um endereço de email válido.',
    'confirmed' => 'A confirmação do campo :attribute não confere.',
    // Mensagens personalizadas
    'custom' => [
        'name' => [
            'required' => 'O nome é obrigatório.',
            'max' => 'O nome não pode ter mais de :max caracteres.',
        ],
        'email' => [
            'required' => 'O email é obrigatório.',
            'email' => 'Insira um email válido.',
        ],
    ],
    'attributes' => [
        'name' => 'nome',
        'email' => 'email',
        'password' => 'senha',
    ],
];
