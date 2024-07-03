<?php

namespace Database\Seeders;

use App\Models\PackSize;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PackSizesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PackSize::create(['name' => 'Small']);
        PackSize::create(['name' => 'Medium']);
        PackSize::create(['name' => 'Large']);
    }
}
