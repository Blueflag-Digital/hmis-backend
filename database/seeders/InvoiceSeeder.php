<?php

namespace Database\Seeders;

use App\Models\Hospital;
use App\Models\Invoice;
use App\Models\PatientVisit;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class InvoiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        // Loop to create multiple invoices (change the number as needed)
        foreach (range(1, 10) as $index) {
            Invoice::create([
                'patient_visit_id' => PatientVisit::inRandomOrder()->first()->id, // Randomly selects a PatientVisit
                'hospital_id' => Hospital::inRandomOrder()->first()->id, // Randomly selects a Hospital
                'invoice_number' => $faker->unique()->numerify('INV-#######'), // Unique invoice number
                'total_amount' => $faker->randomFloat(2, 100, 1000), // Random total amount between 100 and 1000
                'status' => $faker->randomElement(['pending', 'paid', 'cancelled']), // Random status
                'paid_at' => $faker->dateTimeBetween('-1 month', 'now'), // Random date within the past month (or null for pending)
                'payment_method' => $faker->randomElement(['1', '2', '3']), // Random payment method (or null for unpaid)
                'payment_reference' => $faker->randomElement([null, $faker->uuid]), // Random payment reference (nullable)
            ]);
        }
    }
}
