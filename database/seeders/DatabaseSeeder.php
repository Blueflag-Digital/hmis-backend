<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {


        $this->call([
            RolesTableSeeder::class,
            HospitalTableSeeder::class,
            UsersTableSeeder::class,
            CityTableSeeder::class,
            DepartmentsTableSeeder::class,
            DiagnosisCodesTableSeeder::class,
            InvestigationsTableSeeder::class,
            PackSizesTableSeeder::class,
            UnitsOfMeasuresTableSeeder::class,
            WorkPlacesTableSeeder::class,
            DrugsTableSeeder::class,
            BrandsTableSeeder::class,
            // PaymentTableSeeder::class,
            // SubscriptionTableSeeder::class
            ProcedureSeeder::class,
        ]);
    }
}
