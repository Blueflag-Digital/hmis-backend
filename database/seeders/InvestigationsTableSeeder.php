<?php

namespace Database\Seeders;

use App\Models\Hospital;
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
        // $investigations = [
        //     ['code' => 'INV001', 'name' => 'Blood Test', 'type' => 'Lab'],
        //     ['code' => 'INV002', 'name' => 'X-Ray', 'type' => 'Radiology'],
        //     ['code' => 'INV003', 'name' => 'MRI Scan', 'type' => 'Radiology'],
        //     ['code' => 'INV004', 'name' => 'Urine Test', 'type' => 'Lab'],
        //     ['code' => 'INV005', 'name' => 'CT Scan', 'type' => 'Radiology'],
        // ];

        // DB::table('investigations')->insert($investigations);


        if (!$hospital = Hospital::first() ) {
            throw new \Exception("Hospital does not exist", 1);
        }

        $investigations = [
            ['code' => 'INV001', 'name' => 'Blood Test', 'type' => 'Lab', 'hospital_id' => $hospital->id],
            ['code' => 'INV002', 'name' => 'X-Ray', 'type' => 'Radiology', 'hospital_id' => $hospital->id],
            ['code' => 'INV003', 'name' => 'MRI Scan', 'type' => 'Radiology', 'hospital_id' => $hospital->id],
            ['code' => 'INV004', 'name' => 'Urine Test', 'type' => 'Lab', 'hospital_id' => $hospital->id],
            ['code' => 'INV005', 'name' => 'CT Scan', 'type' => 'Radiology', 'hospital_id' => $hospital->id],
        ];

        DB::table('investigations')->insert($investigations);




    }
}
