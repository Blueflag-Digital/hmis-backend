<?php

namespace Database\Seeders;

use App\Models\Hospital;
use App\Models\UnitOfMeasure;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UnitsOfMeasuresTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $units = ['Kilogram','Liter','Packet'];
        $hospital = Hospital::first();
        foreach ($units as $unit) {
           UnitOfMeasure::create([
                'name' => $unit,
                'hospital_id'=>$hospital->id
            ]);
        }

    }
}


