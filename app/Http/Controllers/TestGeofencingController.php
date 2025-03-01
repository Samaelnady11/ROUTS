<?php

namespace App\Http\Controllers;

use App\Models\Bus;
use App\Services\GeofencingService;
use Illuminate\Http\Request;
use App\Models\Notification;

use App\Events\BusProximityEvent;

event(new BusProximityEvent($parent->id, "The bus is near your child's location."));


class TestGeofencingController extends Controller


// private $geofencingService;

// public function __construct(GeofencingService $geofencingService)
// {
//     $this->geofencingService = $geofencingService;
// }

// public function simulateMovement(Request $request, Bus $bus)
// {
//     // Simulate bus moving towards student locations
//     $currentLocation = $bus->currentLocation;

//     if (!$currentLocation) {
//         return response()->json(['error' => 'No initial location found'], 404);
//     }

//     // Move bus slightly closer to first student
//     $newLatitude = $currentLocation->latitude + 0.0001; // Small increment
//     $newLongitude = $currentLocation->longitude + 0.0001;

//     // Create new location
//     $location = $bus->locations()->create([
//         'latitude' => $newLatitude,
//         'longitude' => $newLongitude,
//         'speed' => 30,
//         'location_timestamp' => now()
//     ]);

//     // Check proximity and trigger notifications
//     $this->geofencingService->checkProximity($bus, $newLatitude, $newLongitude);

//     return response()->json([
//         'message' => 'Bus movement simulated',
//         'location' => $location,
//         'next_coordinates' => [
//             'latitude' => $newLatitude,
//             'longitude' => $newLongitude
//         ]
//     ]);
// }

// public function testNotification(Request $request)
// {
//     $fcmService = new \App\Services\FCMService();

//     return $fcmService->send(
//         $request->input('fcm_token'),
//         'Test Notification',
//         'This is a test notification from the school bus system',
//         ['type' => 'test']
//     );
// }

// public function checkProximity(Bus $bus)
// {
//     $latitude = $bus->currentLocation->latitude;
//     $longitude = $bus->currentLocation->longitude;
//     $this->geofencingService->checkProximity($bus, $latitude, $longitude);

//     return response()->json(['status' => 'success', 'message' => 'Proximity check completed.']);
// }




// namespace App\Http\Controllers;

// use App\Models\Bus;
// use App\Services\GeofencingService;
// use Illuminate\Http\Request;

// class TestGeofencingController extends Controller
{
    private $geofencingService;

    // public function __construct(GeofencingService $geofencingService)
    // {
    //     $this->geofencingService = $geofencingService;
    // }

    // public function simulateMovement(Request $request, Bus $bus)
    // {
    //     // Validate the request
    //     $request->validate([
    //         'latitude' => 'required|numeric',
    //         'longitude' => 'required|numeric'
    //     ]);

    //     // Get the current location of the bus
    //     $currentLocation = $bus->currentLocation;

    //     if (!$currentLocation) {
    //         return response()->json(['error' => 'No initial location found'], 404);
    //     }

    //     // Move bus slightly closer to first student
    //     $newLatitude = $currentLocation->latitude + 2.0001; // Small increment
    //     $newLongitude = $currentLocation->longitude + 2.0001;

    //     // Create new location
    //     $location = $bus->locations()->update([
    //         'latitude' => $newLatitude,
    //         'longitude' => $newLongitude,
    //         'speed' => 30,
    //         'location_timestamp' => now()
    //     ]);

    //     // Check proximity and trigger notifications
    //     $this->geofencingService->checkProximity($bus, $newLatitude, $newLongitude);

    //     return response()->json([
    //         'message' => 'Bus movement simulated',
    //         'location' => $location,
    //         'next_coordinates' => [
    //             'latitude' => $newLatitude,
    //             'longitude' => $newLongitude
    //         ]
    //     ]);
    // }

    // public function testNotification(Request $request)
    // {
    //     $fcmService = new \App\Services\FCMService();

    //     return $fcmService->send(
    //         $request->input('fcm_token'),
    //         'Test Notification',
    //         'This is a test notification from the school bus system',
    //         ['type' => 'test']
    //     );
    // }

    // public function checkProximity(Bus $bus)
    // {
    //     $latitude = $bus->currentLocation->latitude;
    //     $longitude = $bus->currentLocation->longitude;
    //     $this->geofencingService->checkProximity($bus, $latitude, $longitude);

    //     return response()->json(['status' => 'success', 'message' => 'Proximity check completed.']);


    public function __construct(GeofencingService $geofencingService)
    {
        $this->geofencingService = $geofencingService;
    }

    public function simulateMovement(Request $request, Bus $bus)
    {
        // Validate the request
        $request->validate([
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric'
        ]);

        // Get the current location of the bus
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
        $this->geofencingService->checkProximity($bus, $newLatitude, $newLongitude);

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

    // public function checkProximity(Bus $bus)
    // {
    //     $latitude = $bus->currentLocation->latitude;
    //     $longitude = $bus->currentLocation->longitude;
    //     $this->geofencingService->checkProximity($bus, $latitude, $longitude);

    //     return response()->json(['status' => 'success', 'message' => 'Proximity check completed.']);
    // }


    public function checkProximity(Bus $bus)
    {
        $latitude = $bus->currentLocation->latitude;
        $longitude = $bus->currentLocation->longitude;

        // Retrieve all students assigned to this bus
        $students = $bus->students;

        foreach ($students as $student) {
            $studentLatitude = $student->home_location_latitude;
            $studentLongitude = $student->home_location_longitude;
            $radius = $student->geofence_radius ?? 500; // Default radius if not set

            // Check if the bus is within the student's geofence radius
            $isWithinRadius = $this->geofencingService->isWithinRadius($latitude, $longitude, $studentLatitude, $studentLongitude, $radius);

            if ($isWithinRadius) {
                // Send notification to the parent
                $parent = $student->parent;
                if ($parent) {
                    $this->sendNotification($parent, "The bus is near your child's location.");
                }
            }
        }

        return response()->json(['status' => 'success', 'message' => 'Proximity check completed.']);
    }

    /**
     * Function to send a notification to a parent
     */
    private function sendNotification($parent, $message)
    {
        // Example: Store notification in the database
        Notification::create([
            'user_id' => $parent->id,
            'message' => $message,
            'type' => 'bus_proximity'
        ]);

        // Example: Send real-time notification using Firebase or Pusher
        event(new BusProximityEvent($parent->id, $message));
    }
}
