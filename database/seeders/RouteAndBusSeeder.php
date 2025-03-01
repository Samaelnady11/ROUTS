<?php

namespace Database\Seeders;

use App\Models\Parents;
use App\Models\Route;
use App\Models\Bus;
use App\Models\BusLocation;
use App\Models\Student;
use App\Models\School;
use App\Models\Parent as ParentModel;
use Illuminate\Database\Seeder;

class RouteAndBusSeeder extends Seeder
{
    public function run()
    {
        // Create a school
        $school = School::create([
            'id' => 1, // Ensure the ID is set to 1
            'school_name' => 'Glory School',
            'school_id' => 'SCH001', // Unique school_id
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Create a route
        $route = Route::create([
            'route_name' => 'Morning Route 1',
            'start_location' => '30.0444, 31.2357', // Cairo, Egypt
            'end_location' => '30.8418, 31.3276', // Mansoura, Egypt
            'optimized_path' => json_encode([
                ['lat' => 30.0444, 'lng' => 31.2357],
                ['lat' => 30.1234, 'lng' => 31.2567],
                ['lat' => 30.5678, 'lng' => 31.2789],
                ['lat' => 30.8418, 'lng' => 31.3276]
            ]),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Create a bus
        $bus = Bus::create([
            'bus_number' => 'TEST-001',
            'driver_id' => 2, // Ensure this ID exists in the users table
            'supervisor_id' => 3, // Ensure this ID exists in the users table
            'route_id' => $route->id,
            'capacity' => 30,
        ]);

        // Create a parent
        $parent = Parents::create([
            'user_id' => 1, // Ensure this user_id exists in the users table
            'phone' => '01111111111',
            'address' => '123 Test St',
            'gender' => 'Male',
            'dob' => '1980-01-01',
            'profile_picture' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Create test students with pickup locations
        $students = [
            [
                'first_name' => 'Mohamed',
                'last_name' => 'Essam',
                'school_id' => $school->id,
                'parent_id' => $parent->id,
                'grade' => 'six',
                'pickup_address' => 'Qanat Elswes',
                'pickup_latitude' => 24.7136, // Example coordinates for Riyadh
                'pickup_longitude' => 46.6753,
                'bus_id' => $bus->id,
                'school_name' => 'Glory School', // Provide a value for school_name
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'first_name' => 'Ahmed',
                'last_name' => 'Yussef',
                'school_id' => $school->id,
                'parent_id' => $parent->id,
                'grade' => 'seven',
                'pickup_address' => 'Qanat Elswes',
                'pickup_latitude' => 24.7137, // Nearby location
                'pickup_longitude' => 46.6754,
                'bus_id' => $bus->id,
                'school_name' => 'Glory School', // Provide a value for school_name
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ];

        foreach ($students as $studentData) {
            Student::create($studentData);
        }

        // Create initial bus location
        BusLocation::create([
            'bus_id' => $bus->id,
            'latitude' => 24.7135, // Starting point
            'longitude' => 46.6752,
            'speed' => 40,
            'location_timestamp' => now()
        ]);
    }
}
