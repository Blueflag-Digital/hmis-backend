<?php

namespace Database\Seeders;

use App\Models\Subscription;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SubscriptionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         $subscriptions = [
            ['hospital_id' => '1', 'payment_id' => '6', 'expires_on' => '2024-10-29 09:31:16','status'=>'true'],
            ['hospital_id' => '2', 'payment_id' => '7', 'expires_on' => '2024-10-29 09:31:16','status'=>'true'],
            ['hospital_id' => '3', 'payment_id' => '8', 'expires_on' => '2024-07-29 09:31:16','status'=>'true'],
        ];
        foreach ($subscriptions as $key => $subscription) {
             Subscription::create([
                'hospital_id' => $subscription['hospital_id'],
                'payment_id' => $subscription['payment_id'],
                'expires_on' => $subscription['expires_on'],
                'status' => $subscription['status'],
             ]);
        }
    }
}
