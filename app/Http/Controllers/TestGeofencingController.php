<?php

namespace App\Http\Controllers;

use App\Models\Bus;
use App\Services\GeofencingService;
use Illuminate\Http\Request;

class TestGeofencingController extends Controller
{
    private $geofencingService;

    public function __construct(GeofencingService $geofencingService)
    {
        $this->geofencingService = $geofencingService;
    }

    public function simulateMovement(Request $request, Bus $bus)
    {
        // Simulate bus moving towards student locations
        $currentLocation = $bus->currentLocation;

        if (!$currentLocation) {
            return response()->json(['error' => 'No initial location found'], 404);
        }

        // Move bus slightly closer to first student
        $newLatitude = $currentLocation->latitude + 0.0001; // Small increment
        $newLongitude = $currentLocation->longitude + 0.0001;

        // Create new location
        $location = $bus->locations()->create([
            'latitude' => $newLatitude,
            'longitude' => $newLongitude,
            'speed' => 30,
            'location_timestamp' => now()
        ]);

        // Check proximity and trigger notifications
        $this->geofencingService->checkProximity($bus);

        return response()->json([
            'message' => 'Bus movement simulated',
            'location' => $location,
            'next_coordinates' => [
                'latitude' => $newLatitude,
                'longitude' => $newLongitude
            ]
        ]);
    }

    public function testNotification(Request $request)
    {
        $fcmService = new \App\Services\FCMService();

        return $fcmService->send(
            $request->input('fcm_token'),
            'Test Notification',
            'This is a test notification from the school bus system',
            ['type' => 'test']
        );
    }

    public function checkProximity(Bus $bus)
    {
        $this->geofencingService->checkProximity($bus);

        return response()->json(['status' => 'success', 'message' => 'Proximity check completed.']);
    }
}
