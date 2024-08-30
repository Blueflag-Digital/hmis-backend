<?php

namespace Database\Seeders;

use App\Models\Hospital;
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
        $hospital = Hospital::first();
        $packsizes = ['Small','Medium','Large'];

        foreach ($packsizes as $size) {
            PackSize::create([
                'name' => $size,
                'hospital_id'=>$hospital->id
            ]);
        }

    }
}
