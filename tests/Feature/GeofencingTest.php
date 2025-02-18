<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Bus;
use App\Models\Student;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GeofencingTest extends TestCase
{
    use RefreshDatabase;

    public function test_geofencing_notifications()
    {
        // Seed test data
        $this->seed(GeofencingTestSeeder::class);

        $bus = Bus::first();
        
        // Simulate 10 movements
        for ($i = 0; $i < 10; $i++) {
            $response = $this->postJson("/api/test/simulate-movement/{$bus->id}");
            
            $response->assertStatus(200);
            
            // Wait 2 seconds between movements
            sleep(2);
        }
    }
} 