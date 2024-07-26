<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SettingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         DB::table('settings')->insert([
            [
                'name' => 'Show Suppliers Module',
                'slug' => Str::slug('Enable Patient Portal'),
                'value' => true,
                'description' => 'This setting enables the patient portal, allowing patients to access their medical records, schedule appointments, and communicate with their healthcare providers online. It is crucial to provide secure access and ensure that all patient data is protected in compliance with HIPAA regulations.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Show brands Module',
                'slug' => Str::slug('Show brands Module'),
                'value' => true,
                'description' => 'When activated, this setting will send real-time notifications to doctors regarding patient check-ins, test results, and appointment reminders. Ensuring that notifications are timely and accurate is essential for improving healthcare delivery and patient satisfaction.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Enable Online Payments',
                'slug' => Str::slug('Enable Online Payments'),
                'value' => false,
                'description' => 'This setting allows patients to pay their bills online via a secure payment gateway integrated into the hospital system. Enabling this feature can enhance the patient experience by providing a convenient and efficient way to manage billing and payments.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Allow Appointment Scheduling',
                 'slug' => Str::slug('Allow Appointment Scheduling'),
                'value' => true,
                'description' => 'Allows patients to schedule, reschedule, or cancel appointments through the online portal. This feature aims to reduce administrative workload, improve patient satisfaction, and optimize scheduling efficiency.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Enable Two-Factor Authentication',
                'slug' => Str::slug('Enable Two-Factor Authentication'),
                'value' => false,
                'description' => 'This security setting requires users to verify their identity using two-factor authentication, providing an additional layer of security beyond the standard username and password. This is particularly important for protecting sensitive patient data and maintaining system integrity.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Enable Password',
                'slug' => Str::slug('Enable Password'),
                'value' => false,
                'description' => 'This security setting requires users to verify their identity using two-factor authentication, providing an additional layer of security beyond the standard username and password. This is particularly important for protecting sensitive patient data and maintaining system integrity.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
