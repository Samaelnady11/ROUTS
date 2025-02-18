<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Create driver user
        User::create([
            'name' => 'Driver One',
            'email' => 'driver@example.com',
            'password' => Hash::make('password'),
            'role' => 'driver'
        ]);

        // Create supervisor user
        User::create([
            'name' => 'Supervisor One',
            'email' => 'supervisor@example.com',
            'password' => Hash::make('password'),
            'role' => 'supervisor'
        ]);
    }
} 