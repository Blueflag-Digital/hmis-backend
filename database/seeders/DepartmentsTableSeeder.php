<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\Hospital;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DepartmentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        if (!$hospital = Hospital::first() ) {
            throw new \Exception("Hospital does not exist", 1);
        }

        Department::create([
            'name' => 'Doctor',
            'hospital_id'=>$hospital->id
        ]);

        Department::create([
            'name' => 'Lab',
            'hospital_id'=>$hospital->id
        ]);

        Department::create([
            'name' => 'Radiology',
            'hospital_id'=>$hospital->id
        ]);

        Department::create([
            'name' => 'Pharmacy',
            'hospital_id'=>$hospital->id
        ]);

        Department::create([
            'name' => 'Nurse',
            'hospital_id'=>$hospital->id
        ]);
    }
}
