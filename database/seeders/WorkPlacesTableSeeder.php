<?php

namespace Database\Seeders;

use App\Models\Hospital;
use App\Models\WorkPlace;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class WorkPlacesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $hospital = Hospital::first();
        WorkPlace::create([
            'name' => 'Lake Turkana Wind Power',
            'description' => 'The Lake Turkana Wind Power (LTWP) Project. is a wind farm that supplies renewable energy to Kenya',
            'hospital_id'=>$hospital->id
        ]);
        WorkPlace::create([
            'name' => 'Lake Turkana Wind Power 2',
            'description' => 'The Lake Turkana Wind Power 2 (LTWP) Project. is a wind farm that supplies renewable energy to Kenya',
            'hospital_id'=>$hospital->id
        ]);
    }
}
