<?php

namespace Database\Seeders;

use App\Models\Drug;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DrugsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Drug::create(['brand_id' => 1, 'name' => 'Drug 1']);
        Drug::create(['brand_id' => 2, 'name' => 'Drug 2']);
    }
}
