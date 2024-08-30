<?php

return [
    [
        'name' => 'Enable Suppliers Menu',
        'slug' => \Illuminate\Support\Str::slug('enable-suppliers-menu'),
        'value' => false,
        'description' => 'This setting enables the patient portal, allowing patients to access their medical records, schedule appointments, and communicate with their healthcare providers online. It is crucial to provide secure access and ensure that all patient data is protected in compliance with HIPAA regulations.',
        'created_at' => now(),
        'updated_at' => now(),
    ],
    [
        'name' => 'Reset Password',
        'slug' => \Illuminate\Support\Str::slug('Reset Password'),
        'value' => false,
        'description' => 'This Reset Password setting requires users to verify their identity using two-factor authentication, providing an additional layer of security beyond the standard username and password. This is particularly important for protecting sensitive patient data and maintaining system integrity.',
        'created_at' => now(),
        'updated_at' => now(),
     ],
];
