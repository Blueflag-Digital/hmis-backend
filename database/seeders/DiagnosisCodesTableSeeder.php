<?php

namespace Database\Seeders;

use App\Models\DiagnosisCode;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DiagnosisCodesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DiagnosisCode::create([
            'code' => 'DA25.Y',
            'diagnosis' => 'Acute gastrooesophageal ulcer',
            'hospital_id' => 1
        ]);
        DiagnosisCode::create([
            'code' => '1F42',
            'diagnosis' => 'Malaria due to Plasmodium malariae',
            'hospital_id' => 1
        ]);
        DiagnosisCode::create([
            'code' => '1E50.0',
            'diagnosis' => 'Acute hepatitis A',
            'hospital_id' => 1
        ]);
    }
}
