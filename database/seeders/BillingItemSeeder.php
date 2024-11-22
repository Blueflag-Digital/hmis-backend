<?php

namespace Database\Seeders;

use App\Models\BillingItem;
use App\Models\Hospital;
use App\Models\PatientVisit;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class BillingItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();
         foreach (range(1, 10) as $index) {

            $quantity = $faker->numberBetween(1, 10);
            $unitPrice = $faker->randomFloat(2, 10, 500);


            BillingItem::create( [
            'patient_visit_id' => PatientVisit::inRandomOrder()->first()->id, // Randomly selects a PatientVisit
            'hospital_id' => Hospital::inRandomOrder()->first()->id, // Randomly selects a Hospital
            'billable_type' => 'App\Models\Invoice', // Replace with your actual polymorphic model
            'billable_id' => \App\Models\Invoice::inRandomOrder()->first()->id,
            'quantity' => $quantity,
            'unit_price' => $unitPrice ,
            'amount' => $quantity * $unitPrice,
            'status' => $faker->randomElement(['pending', 'paid']),
        ]);
         }

        
    }
}
