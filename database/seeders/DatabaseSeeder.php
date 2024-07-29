<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        $this->call([
            // UsersTableSeeder::class,
            // DepartmentsTableSeeder::class,
            // DiagnosisCodesTableSeeder::class,
            // InvestigationsTableSeeder::class,
            // PackSizesTableSeeder::class,
            // UnitsOfMeasuresTableSeeder::class,
            WorkPlacesTableSeeder::class,
            SettingsTableSeeder::class,
            PaymentTableSeeder::class,
            SubscriptionTableSeeder::class
        ]);
    }
}
