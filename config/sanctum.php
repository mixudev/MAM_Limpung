<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Stateful Domains
    |--------------------------------------------------------------------------
    |
    | For SPA authentication (cookie-based), list your frontend domains here.
    | For pure API token auth, this list can remain empty.
    |
    */
    'stateful' => explode(',', env('SANCTUM_STATEFUL_DOMAINS', implode(',', [
        'localhost',
        'localhost:3000',
        '127.0.0.1',
        '127.0.0.1:8000',
        env('APP_URL') ? parse_url(env('APP_URL'), PHP_URL_HOST) : null,
    ]))),

    /*
    |--------------------------------------------------------------------------
    | Sanctum Guards
    |--------------------------------------------------------------------------
    |
    | Defines which guards Sanctum's middleware should check when looking for
    | an authenticated user. The 'web' guard allows SPA cookie-based auth.
    |
    */
    'guard' => ['web'],

    /*
    |--------------------------------------------------------------------------
    | Expiration Minutes
    |--------------------------------------------------------------------------
    |
    | Controls token expiration. Set to null for non-expiring tokens.
    | Override per-token via User::issueToken($name, $abilities, $expiresInMinutes).
    |
    | Default: 24 hours = 1440 minutes
    |
    */
    'expiration' => env('SANCTUM_TOKEN_EXPIRATION', 1440),

    /*
    |--------------------------------------------------------------------------
    | Token Prefix
    |--------------------------------------------------------------------------
    |
    | Prefix added to tokens stored in the database for easier identification
    | in case of token leaks and audit logs.
    |
    */
    'token_prefix' => env('SANCTUM_TOKEN_PREFIX', ''),

    /*
    |--------------------------------------------------------------------------
    | Sanctum Middleware
    |--------------------------------------------------------------------------
    |
    | Middleware used when Sanctum verifies incoming requests.
    |
    */
    'middleware' => [
        'authenticate_session' => Laravel\Sanctum\Http\Middleware\AuthenticateSession::class,
        'encrypt_cookies'      => Illuminate\Cookie\Middleware\EncryptCookies::class,
        'validate_csrf_token'  => Illuminate\Foundation\Http\Middleware\ValidateCsrfToken::class,
    ],

];
