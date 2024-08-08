<?php

namespace Database\Seeders;

use App\Models\City;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CityTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        City::create([
            'name'=> 'Nairobi'
        ]);
        City::create([
            'name'=> 'Nakuru'
        ]);
        City::create([
            'name'=> 'Kisumu'
        ]);
    }
}
