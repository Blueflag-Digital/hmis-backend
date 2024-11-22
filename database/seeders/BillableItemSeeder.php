<?php

namespace Database\Seeders;

use App\Models\BillingItem;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BillableItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         BillingItem::factory()->count(10)->create();
    }
}
