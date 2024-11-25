<?php

return [
    [
        'name' => 'Enable Suppliers Menu',
        'slug' => \Illuminate\Support\Str::slug('enable-suppliers-menu'),
        'value' => false,
        'description' => 'This setting turns on the supllier module and all its attachements/where needed',
        'created_at' => now(),
        'updated_at' => now(),
    ],
    [
        'name' => 'Enable Two Factor Authentication',
        'slug' => \Illuminate\Support\Str::slug('two-factor authentication'),
        'value' => false,
        'description' => 'This setting allows users to login using two-factor authentication. This is particularly important for protecting User Logins.',
        'created_at' => now(),
        'updated_at' => now(),
    ],
    [
        'name' => 'Show Billing Module',
        'slug' => \Illuminate\Support\Str::slug('show-billing-module'),
        'value' => false,
        'description' => 'This setting turns off the module for Billing when turned off',
        'created_at' => now(),
        'updated_at' => now(),
    ],
];
