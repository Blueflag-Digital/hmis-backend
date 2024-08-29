<?php

return [
    [
        'name' => 'Sick Leave',
        'slug' => \Illuminate\Support\Str::slug('sick leave'),
        'description' => 'This sick leave  enables the patient portal, allowing patients to access their medical records, schedule appointments, and communicate with their healthcare providers online. It is crucial to provide secure access and ensure that all patient data is protected in compliance with HIPAA regulations.',
        'created_at' => now(),
        'updated_at' => now(),
    ],
    [
        'name' => 'Referral Leave',
        'slug' => \Illuminate\Support\Str::slug('referral leave'),
        'description' => 'When activated, this setting will send real-time notifications to doctors regarding patient check-ins, test results, and appointment reminders. Ensuring that notifications are timely and accurate is essential for improving healthcare delivery and patient satisfaction.',
        'created_at' => now(),
        'updated_at' => now(),
    ],
    [
        'name' => 'Medical Evacuation',
        'slug' => \Illuminate\Support\Str::slug('Medical Evacuation'),
        'description' => 'When activated, this setting will send real-time notifications to doctors regarding patient check-ins, test results, and appointment reminders. Ensuring that notifications are timely and accurate is essential for improving healthcare delivery and patient satisfaction.',
        'created_at' => now(),
        'updated_at' => now(),
    ],

];
