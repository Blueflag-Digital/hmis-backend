<?php

namespace Database\Seeders;

use App\Models\DiagnosisCode;
use App\Models\Hospital;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DiagnosisCodesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $hospital = Hospital::first();
        DiagnosisCode::create([
            'code' => 'DA25.Y',
            'diagnosis' => 'Acute gastrooesophageal ulcer',
            'hospital_id' => $hospital->id
        ]);
        DiagnosisCode::create([
            'code' => '1F42',
            'diagnosis' => 'Malaria due to Plasmodium malariae',
            'hospital_id' => $hospital->id
        ]);
        DiagnosisCode::create([
            'code' => '1E50.0',
            'diagnosis' => 'Acute hepatitis A',
            'hospital_id' => $hospital->id
        ]);
    }
}
