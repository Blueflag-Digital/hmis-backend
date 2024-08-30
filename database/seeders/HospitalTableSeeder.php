<?php

namespace Database\Seeders;

use App\Models\Hospital;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class HospitalTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Hospital::create([
            'hospital_name'=>'Responsemed',
            'slug'=> Str::slug('response med'),
            'location' => 'Nairobi',
            'contact' => '0789890000'
        ]);
    }
}
