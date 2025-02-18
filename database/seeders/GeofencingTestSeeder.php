<?php

namespace Database\Seeders;

use App\Models\Bus;
use App\Models\Student;
use App\Models\BusLocation;
use App\Models\GeofenceSetting;
use Illuminate\Database\Seeder;

class GeofencingTestSeeder extends Seeder
{
    public function run()
    {
        // Create a test bus
        $bus = Bus::create([
            'bus_number' => 'TEST-001',
            'driver_id' => 1, // Make sure this ID exists
            'supervisor_id' => 2, // Make sure this ID exists
            'route_id' => 1,  // Make sure to include this
            'capacity' => 30,
        ]);
        
        // Create geofence settings for the bus
        GeofenceSetting::create([
            'bus_id' => $bus->id,
            'notification_radius' => 500.00, // 500 meters
            'is_active' => true
        ]);

        // Create test students with pickup locations
        // These coordinates are example locations - adjust them based on your testing area
        $students = [
            [
                'name' => 'Test Student 1',
                'pickup_latitude' => 24.7136, // Example coordinates for Riyadh
                'pickup_longitude' => 46.6753,
                'bus_id' => $bus->id
            ],
            [
                'name' => 'Test Student 2',
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
    }
} 