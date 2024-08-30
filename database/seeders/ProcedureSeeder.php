<?php

namespace Database\Seeders;

use App\Models\Hospital;
use App\Models\Procedure;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProcedureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $procedures = ['Physical Examination','Colonoscopy','Endoscopy','Surgical Biopsy'];
        $hospital = Hospital::first();
        foreach ($procedures as $procedure) {
            Procedure::create([
                'name' => $procedure,
                'hospital_id'=>$hospital->id
            ]);
        }
    }
}
