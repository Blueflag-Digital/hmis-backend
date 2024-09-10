<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Drug;
use App\Models\Hospital;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BrandsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (!$hospital = Hospital::first()) {
            throw new \Exception("Hospital does not exist", 1);
        }

        $brands = [
            ['name' => 'Bayer', 'drug_name' => 'Aspirin'],
            ['name' => 'Bufferin', 'drug_name' => 'Aspirin'],
            ['name' => 'Ecotrin', 'drug_name' => 'Aspirin'],

            ['name' => 'Glucophage', 'drug_name' => 'Metformin'],
            ['name' => 'Fortamet', 'drug_name' => 'Metformin'],
            ['name' => 'Glumetza', 'drug_name' => 'Metformin'],

            ['name' => 'Zestril', 'drug_name' => 'Lisinopril'],
            ['name' => 'Prinivil', 'drug_name' => 'Lisinopril'],
            ['name' => 'Qbrelis', 'drug_name' => 'Lisinopril'],

            ['name' => 'Lipitor', 'drug_name' => 'Atorvastatin'],
            ['name' => 'Atorlip', 'drug_name' => 'Atorvastatin'],
            ['name' => 'Torvast', 'drug_name' => 'Atorvastatin'],

            ['name' => 'Amoxil', 'drug_name' => 'Amoxicillin'],
            ['name' => 'Moxatag', 'drug_name' => 'Amoxicillin'],
            ['name' => 'Larotid', 'drug_name' => 'Amoxicillin'],

            ['name' => 'Microzide', 'drug_name' => 'Hydrochlorothiazide'],
            ['name' => 'Oretic', 'drug_name' => 'Hydrochlorothiazide'],
            ['name' => 'Hydrodiuril', 'drug_name' => 'Hydrochlorothiazide'],

            ['name' => 'Advil', 'drug_name' => 'Ibuprofen'],
            ['name' => 'Motrin', 'drug_name' => 'Ibuprofen'],
            ['name' => 'Nurofen', 'drug_name' => 'Ibuprofen'],

            ['name' => 'ProAir', 'drug_name' => 'Albuterol'],
            ['name' => 'Ventolin', 'drug_name' => 'Albuterol'],
            ['name' => 'Proventil', 'drug_name' => 'Albuterol'],

            ['name' => 'Prilosec', 'drug_name' => 'Omeprazole'],
            ['name' => 'Zegerid', 'drug_name' => 'Omeprazole'],
            ['name' => 'Omesec', 'drug_name' => 'Omeprazole'],

            ['name' => 'Cozaar', 'drug_name' => 'Losartan'],
            ['name' => 'Losacar', 'drug_name' => 'Losartan'],
            ['name' => 'Hyzaar', 'drug_name' => 'Losartan'],
        ];

        foreach ($brands as $brand) {
            // Find the drug by name
            $drug = Drug::where('name', $brand['drug_name'])->first();

            if ($drug) {
                // Create the brand with the associated drug_id
                Brand::create([
                    'name' => $brand['name'],
                    'drug_id' => $drug->id, // Insert the drug_id
                    'hospital_id' => $hospital->id
                ]);
            }
        }
    }
}
