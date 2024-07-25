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
        Drug::create(['name' => 'Drug 1']);
        Drug::create(['name' => 'Drug 2']);
    }
}
