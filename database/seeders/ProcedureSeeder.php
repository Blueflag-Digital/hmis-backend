<?php

namespace Database\Seeders;

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
        $procedures = [
            ['name' => 'Physical Examination'],
            ['name' => 'Colonoscopy'],
            ['name' => 'Endoscopy'],
            ['name' => 'Surgical Biopsy'],
        ];

        foreach ($procedures as $procedure) {
            Procedure::create($procedure);
        }
    }
}
