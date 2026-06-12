<?php

declare(strict_types=1);

return [
    'api_key' => env('DEEPSEEK_API_KEY'),
    'base_url' => env('DEEPSEEK_BASE_URL', 'https://api.deepseek.com/v1'),
    'model' => env('DEEPSEEK_MODEL', 'deepseek-chat'),
    'request_timeout' => (int) env('DEEPSEEK_REQUEST_TIMEOUT', 600),
    'max_retries' => (int) env('DEEPSEEK_MAX_RETRIES', 2),
];
