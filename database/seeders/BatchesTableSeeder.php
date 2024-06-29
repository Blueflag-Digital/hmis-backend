<?php

namespace Database\Seeders;

use App\Models\Batch;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BatchesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Batch::create([
            'drug_id' => 1,
            'quantity_received' => 100,
            'quantity_available' => 90,
            'supplier_id' => 1,
            'lpo' => 'LPO123',
            'buying_price' => 50.00,
            'selling_price' => 60.00,
            'pack_size_id' => 1,
            'unit_of_measure_id' => 1
        ]);
        Batch::create([
            'drug_id' => 2,
            'quantity_received' => 200,
            'quantity_available' => 180,
            'supplier_id' => 2,
            'lpo' => 'LPO124',
            'buying_price' => 40.00,
            'selling_price' => 55.00,
            'pack_size_id' => 1,
            'unit_of_measure_id' => 1
        ]);
    }
}
