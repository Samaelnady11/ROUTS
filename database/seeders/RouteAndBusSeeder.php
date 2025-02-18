<?php

namespace Database\Seeders;

use App\Models\Route;
use App\Models\Bus;
use App\Models\Student;
use Illuminate\Database\Seeder;
use App\Models\BusLocation;
// BusLocation::all();


class RouteAndBusSeeder extends Seeder
{
    public function run(): void
    {
        
        $route = Route::create([
            // 'id' => 5,
            'route_name' => 'Morning Route 1',
            'start_location' => '30.0444, 31.2357', // Cairo, Egypt
                'end_location' => '30.8418, 31.3276', // Mansoura, Egypt
                'optimized_path' => json_encode([
                    ['lat' => 30.0444, 'lng' => 31.2357], 
                    ['lat' => 30.1234, 'lng' => 31.2567], 
                    ['lat' => 30.5678, 'lng' => 31.2789], 
                    ['lat' => 30.8418, 'lng' => 31.3276]]),
                    'created_at' => now(),
                    'updated_at' => now(),
            // 'description' => 'Morning pickup route for north area'
        ]);
        $bus = Bus::create([
            'bus_number' => 'TEST-001',
            'driver_id' => 1, // Make sure this ID exists
            'supervisor_id' => 2, // Make sure this ID exists
            'route_id' => 1,  // Make sure to include this
            'capacity' => 30,
        ]);

        // Create geofence settings for the bus
        // GeofenceSetting::create([
        //     'bus_id' => $bus->id,
        //     'notification_radius' => 500.00, // 500 meters
        //     'is_active' => true
        // ]);
        $parent =[
            'first_name' => 'essam',
            'last_name' => 'aref',

            'phone' => '01111111111',
            'email' => 'mohamed@gmail.com',
        ];


        // Create test students with pickup locations
        // These coordinates are example locations - adjust them based on your testing area
        $students = [
            [
                'first_name' => 'mohamed',
                'last_name' => 'essam',
                'school_id'=>1,
                'parent_id'=>1,
                'grade'=>'six',
                'pickup_address'=>'qanat elswes',
                'pickup_latitude' => 24.7136, // Example coordinates for Riyadh
                'pickup_longitude' => 46.6753,
                'bus_id' => $bus->id
            ],

            [
                // 'name' => 'Test Student 2',
                'first_name' => 'ahmed',
                'last_name' => 'yussef',
                'school_id'=>1,
                'parent_id'=>1,
                'grade'=>'seven',
                'pickup_address'=>'qanat elswes',
                'pickup_latitude' => 24.7137, // Nearby location
                'pickup_longitude' => 46.6754,
                'bus_id' => $bus->id
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

        // Create route first
        

        // Then create bus with proper string quotes
        Bus::create([
            'bus_number' => 'TEST-001',  // Note the quotes around TEST-001
            'driver_id' => 1,
            'supervisor_id' => 2,
            'route_id' => $route->id,
            'capacity' => 30
        ]);
    }
} 