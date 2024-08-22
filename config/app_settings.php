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
        'name' => 'Show brands Module',
        'slug' => \Illuminate\Support\Str::slug('Show brands Module'),
        'value' => true,
        'description' => 'When activated, this setting will send real-time notifications to doctors regarding patient check-ins, test results, and appointment reminders. Ensuring that notifications are timely and accurate is essential for improving healthcare delivery and patient satisfaction.',
        'created_at' => now(),
        'updated_at' => now(),
    ],
    [
        'name' => 'Enable Online Payments',
        'slug' => \Illuminate\Support\Str::slug('Enable Online Payments'),
        'value' => false,
        'description' => 'This setting allows patients to pay their bills online via a secure payment gateway integrated into the hospital system. Enabling this feature can enhance the patient experience by providing a convenient and efficient way to manage billing and payments.',
        'created_at' => now(),
        'updated_at' => now(),
    ],
    [
        'name' => 'Allow Appointment Scheduling',
        'slug' => \Illuminate\Support\Str::slug('Allow Appointment Scheduling'),
        'value' => true,
        'description' => 'Allows patients to schedule, reschedule, or cancel appointments through the online portal. This feature aims to reduce administrative workload, improve patient satisfaction, and optimize scheduling efficiency.',
        'created_at' => now(),
        'updated_at' => now(),
    ],
    [
        'name' => 'Enable Two-Factor Authentication',
        'slug' => \Illuminate\Support\Str::slug('Enable Two-Factor Authentication'),
        'value' => false,
        'description' => 'This security setting requires users to verify their identity using two-factor authentication, providing an additional layer of security beyond the standard username and password. This is particularly important for protecting sensitive patient data and maintaining system integrity.',
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
