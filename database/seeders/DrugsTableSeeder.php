<?php

namespace Database\Seeders;

use App\Models\Drug;
use App\Models\Hospital;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DrugsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        if (!$hospital = Hospital::first()) {
            throw new \Exception("Hospital does not exist", 1);
        }

        $drugs = [
            ['name' => 'Aspirin'],
            ['name' => 'Metformin'],
            ['name' => 'Lisinopril'],
            ['name' => 'Atorvastatin'],
            ['name' => 'Amoxicillin'],
            ['name' => 'Hydrochlorothiazide'],
            ['name' => 'Ibuprofen'],
            ['name' => 'Albuterol'],
            ['name' => 'Omeprazole'],
            ['name' => 'Losartan'],
        ];

        foreach ($drugs as $drug) {
            Drug::create([
                'name' => $drug['name'],
                'hospital_id' => $hospital->id, // Insert the hospital_id
            ]);
        }
    }
}
