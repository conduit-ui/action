<?php

return [
    /*
    |--------------------------------------------------------------------------
    | GitHub Actions Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration options for the GitHub Actions package.
    |
    */

    'default_timeout' => env('GITHUB_ACTIONS_DEFAULT_TIMEOUT', 30),

    'rate_limit' => [
        'enabled' => env('GITHUB_ACTIONS_RATE_LIMIT', true),
        'max_attempts' => env('GITHUB_ACTIONS_MAX_ATTEMPTS', 5),
        'retry_delay' => env('GITHUB_ACTIONS_RETRY_DELAY', 1000), // milliseconds
    ],

    'cache' => [
        'enabled' => env('GITHUB_ACTIONS_CACHE_ENABLED', false),
        'ttl' => env('GITHUB_ACTIONS_CACHE_TTL', 300), // seconds
        'prefix' => env('GITHUB_ACTIONS_CACHE_PREFIX', 'github_actions'),
    ],
];
