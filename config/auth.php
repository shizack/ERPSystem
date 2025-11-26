<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Authentication Defaults
    |--------------------------------------------------------------------------
    */

    'defaults' => [
        'guard' => env('AUTH_GUARD', 'web'),
        'passwords' => env('AUTH_PASSWORD_BROKER', 'users'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Authentication Guards
    |--------------------------------------------------------------------------
    | Defines your custom guards: 'admin' and 'employee'
    */

    'guards' => [
        'web' => [
            'driver' => 'session',
            'provider' => 'users',
        ],
        'admin' => [
            'driver' => 'session',
            'provider' => 'admins',
            'session' => 'admin',  // Use the admin session configuration
        ],
        'employee' => [
            'driver' => 'session',
            'provider' => 'employees',
            'session' => 'employee',  // Use the employee session configuration
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Redirect Paths for Unauthenticated Users (The Fix)
    |--------------------------------------------------------------------------
    | This is the key fix. It tells the framework where to redirect for each custom guard.
    */
    'redirects' => [
        'admin' => 'admin.login',    // Redirect failed admin attempts to the admin login route
        'employee' => 'employee.login', // Redirect failed employee attempts to the employee login route
        'web' => 'login',            // Fallback for the default web guard
    ],

    /*
    |--------------------------------------------------------------------------
    | User Providers
    |--------------------------------------------------------------------------
    | Defines how to fetch user data for each guard
    */
    'providers' => [
        'users' => [
            'driver' => 'eloquent',
            'model' => App\Models\User::class,
        ],
        'admins' => [
            'driver' => 'eloquent',
            'model' => App\Models\Admin::class, // Your Admin model
        ],
        'employees' => [
            'driver' => 'eloquent',
            'model' => App\Models\Employee::class, // Your Employee model
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Resetting Passwords
    |--------------------------------------------------------------------------
    */
    'passwords' => [
        'users' => [
            'provider' => 'users',
            'table' => env('AUTH_PASSWORD_RESET_TOKEN_TABLE', 'password_reset_tokens'),
            'expire' => 60,
            'throttle' => 60,
        ],
        'admins' => [
            'provider' => 'admins',
            'table' => 'password_reset_tokens',
            'expire' => 60,
            'throttle' => 60,
        ],
        'employees' => [
            'provider' => 'employees',
            'table' => 'password_reset_tokens',
            'expire' => 60,
            'throttle' => 60,
        ],
    ],

    'password_timeout' => env('AUTH_PASSWORD_TIMEOUT', 10800),

];