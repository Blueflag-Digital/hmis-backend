<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class InvestigationsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $investigations = [
            ['code' => 'INV001', 'name' => 'Blood Test', 'type' => 'Lab'],
            ['code' => 'INV002', 'name' => 'X-Ray', 'type' => 'Radiology'],
            ['code' => 'INV003', 'name' => 'MRI Scan', 'type' => 'Radiology'],
            ['code' => 'INV004', 'name' => 'Urine Test', 'type' => 'Lab'],
            ['code' => 'INV005', 'name' => 'CT Scan', 'type' => 'Radiology'],
        ];

        DB::table('investigations')->insert($investigations);
    }
}
