<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            ['SUPER_ADMIN'=>'Super Admin'],
            ['ADMIN'=> 'Admin'],
            ['DOCTOR'=> 'Doctor'],
            ['NURSE'=> 'Nurse'],
            ['PHARMACY'=> 'Pharmacy'],
            ['RECEPTIONIST'=>'Receptionist']
        ];

        foreach ($roles as $role) {
            foreach ($role as $key => $value) {
                \DB::table('roles')->insert([
                    'name' => $value,
                    'guard_name' => 'web' // Default value for guard_name
                ]);
            }
        }




    }
}
