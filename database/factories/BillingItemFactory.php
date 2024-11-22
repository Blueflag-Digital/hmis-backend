<?php

namespace Database\Factories;

use App\Models\BillingItem;
use App\Models\Hospital;
use App\Models\PatientVisit;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\BillingItem>
 */
class BillingItemFactory extends Factory
{
    protected $model = BillingItem::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        
        return [
            'patient_visit_id' => PatientVisit::inRandomOrder()->first()->id, // Randomly selects a PatientVisit
            'hospital_id' => Hospital::inRandomOrder()->first()->id, // Randomly selects a Hospital
            'billable_type' => 'App\Models\Invoice', // Replace with your actual polymorphic model
            'billable_id' => \App\Models\Invoice::inRandomOrder()->first()->id,
            'quantity' => $this->faker->numberBetween(1, 10),
            'unit_price' => $this->faker->randomFloat(2, 10, 500),
            'amount' => function (array $attributes) {
                    return $attributes['quantity'] * $attributes['unit_price']; // Dynamically calculates amount
                },
            'status' => $this->faker->randomElement(['pending', 'paid']),
        ];
    }
}
