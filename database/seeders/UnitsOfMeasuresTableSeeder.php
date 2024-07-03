<?php

namespace Database\Seeders;

use App\Models\UnitOfMeasure;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UnitsOfMeasuresTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        UnitOfMeasure::create(['name' => 'Kilogram']);
        UnitOfMeasure::create(['name' => 'Liter']);
        UnitOfMeasure::create(['name' => 'Packet']);
    }
}
