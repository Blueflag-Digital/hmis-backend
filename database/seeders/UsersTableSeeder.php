<?php

namespace Database\Seeders;

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
       $user =  User::create([
            'name' => 'superAdmin',
            'email' => 'superadmin@gmail.com',
            'password' => Hash::make('password'),
        ]);
        if($user){
            \DB::table('model_has_roles')->insert([
                ['role_id' => 1, 'model_type' => 'App\\Models\\User', 'model_id' => $user->id],
                ['role_id' => 2, 'model_type' => 'App\\Models\\User', 'model_id' => $user->id],
            ]);

        }

    }
}
