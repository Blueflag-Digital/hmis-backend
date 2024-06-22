<?php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DepartmentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Department::create([
            'name' => 'Doctor'
        ]);

        Department::create([
            'name' => 'Lab'
        ]);

        Department::create([
            'name' => 'Radiology'
        ]);

        Department::create([
            'name' => 'Pharmacy'
        ]);

        Department::create([
            'name' => 'Nurse'
        ]);
    }
}
