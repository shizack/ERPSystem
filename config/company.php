<?php

return [
    'name' => env('COMPANY_NAME', 'Mayet Beach Resort'),
    'phone' => env('COMPANY_PHONE', ''),
    'email' => env('COMPANY_EMAIL', ''),
    'address' => env('COMPANY_ADDRESS', ''),
    'logo_path' => env('COMPANY_LOGO', 'images/logo.png'),

    'manager' => [
        'name' => env('COMPANY_MANAGER_NAME', ''),
        'address' => env('COMPANY_MANAGER_ADDRESS', ''),
        'contact_person' => env('COMPANY_MANAGER_CONTACT_PERSON', ''),
        'phone' => env('COMPANY_MANAGER_PHONE', ''),
    ],
];
