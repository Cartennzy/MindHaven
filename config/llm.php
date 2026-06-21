<?php

return [
    'provider' => env('LLM_PROVIDER', 'gemini'),

    'gemini' => [
        'api_key' => env('GEMINI_API_KEY'),
        'model' => env('GEMINI_MODEL', 'gemini-3.5-flash'),
        'url' => env('GEMINI_API_URL', 'https://generativelanguage.googleapis.com/v1beta/models'),
    ],

    'openai' => [
        'api_key' => env('OPENAI_API_KEY'),
        'model' => env('OPENAI_MODEL', 'gpt-4o-mini'),
        'url' => env('OPENAI_API_URL', 'https://api.openai.com/v1/chat/completions'),
    ],
];