<?php

namespace Database\Seeders;

use App\Models\Hospital;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use PDO;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (!$hospital = Hospital::first() ) {
            throw new \Exception("Hospital does not exist", 1);
        }
       $user =  User::create([
            'name' => 'superAdmin',
            'email' => 'superadmin@gmail.com',
            'password' => Hash::make('password'),
        ]);

         $user2 =  User::create([
            'name' => 'Daniel',
            'email' => 'daniel@gmail.com',
            'password' => Hash::make('12345678'),
            'hospital_id'=>$hospital->id
        ]);

        if($user){
            \DB::table('model_has_roles')->insert([
                ['role_id' => 1, 'model_type' => 'App\\Models\\User', 'model_id' => $user->id],
                ['role_id' => 2, 'model_type' => 'App\\Models\\User', 'model_id' => $user->id],
            ]);

        }
        if($user2){
            \DB::table('model_has_roles')->insert([
                ['role_id' => 2, 'model_type' => 'App\\Models\\User', 'model_id' => $user2->id],
            ]);

        }


    }
}
