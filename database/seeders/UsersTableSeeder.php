<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('users')->insert([
            [
                'name' => 'Essam Aref',
                'email' => 'essam@example.com',
                'password' => Hash::make('password'),
                'role' => 'parent',
                'role_based_id' => 'RB11111',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Driver One',
                'email' => 'driver@example.com',
                'password' => Hash::make('password'),
                'role' => 'driver',
                'role_based_id' => 'RB12356',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Supervisor One',
                'email' => 'supervisor@example.com',
                'password' => Hash::make('password'),
                'role' => 'supervisor',
                'role_based_id' => 'RB12357',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
