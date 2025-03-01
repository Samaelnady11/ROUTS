<?php

namespace App\Http\Controllers;


use App\Models\Bus;
use App\Models\Geofencing;
use App\Models\Geofence; // Ensure this model exists in the specified namespace
use Illuminate\Http\Request;
use App\Services\GeofencingService;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Support\Facades\Log;

class GeofencingController extends Controller
{
    private $geofencingService;

    public function checkStudentGeofence(Request $request, $studentId)
    {
        $geofence = Geofencing::where('student_id', $studentId)->first();
        if (!$geofence) {
            return response()->json(['message' => 'Geofence not found for the student.'], 404);
        }

        $latitude = $request->input('latitude');
        $longitude = $request->input('longitude');

        if ($geofence->isInsideGeofence($latitude, $longitude)) {
            return response()->json(['message' => 'The student is inside the geofence.']);
        } else {
            return response()->json(['message' => 'The student is outside the geofence.']);
        }
    }


    public function checkBusGeofence(Request $request, $busId)
    {
        $bus = Bus::findOrFail($busId);

        $latitude = $bus->current_latitude;
        $longitude = $bus->current_longitude;

        $geofenceLatitude = $request->input('geofence_latitude');
        $geofenceLongitude = $request->input('geofence_longitude');
        $geofenceRadius = $request->input('geofence_radius');

        $distance = $this->calculateDistance($latitude, $longitude, $geofenceLatitude, $geofenceLongitude);

        if ($distance <= $geofenceRadius) {
            return response()->json(['message' => 'The bus is inside the geofence.']);
        } else {
            return response()->json(['message' => 'The bus is outside the geofence.']);
        }
    }


    private function calculateDistance($lat1, $lon1, $lat2, $lon2)
    {
        $lat1 = deg2rad($lat1);
        $lon1 = deg2rad($lon1);
        $lat2 = deg2rad($lat2);
        $lon2 = deg2rad($lon2);

        $dlat = $lat2 - $lat1;
        $dlon = $lon2 - $lon1;

        $a = sin($dlat / 2) * sin($dlat / 2) +
            cos($lat1) * cos($lat2) *
            sin($dlon / 2) * sin($dlon / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        $radius = 6371;
        return $radius * $c * 1000;
    }

    public function __construct(GeofencingService $geofencingService)
    {
        $this->geofencingService = $geofencingService;
    }

    public function checkBusLocation(Request $request)
    {
        $request->validate([
            'bus_id' => 'required|integer',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric'
        ]);

        $bus = Bus::where('id', $request->input('bus_id'))->firstOrFail();
        $latitude = $request->input('latitude');
        $longitude = $request->input('longitude');

        $this->geofencingService->checkProximity($bus, $latitude, $longitude);

        return response()->json(['message' => 'Bus location checked and notifications sent if within geofence.']);
    }
    // public function create(Request $request)
    // {
    //     $validated = $request->validate([
    //         'name' => 'required|string',
    //         'latitude' => 'required|numeric',
    //         'longitude' => 'required|numeric',
    //         'radius' => 'required|numeric',
    //         'student_id' => 'required|exists:students,id'
    //     ]);

    //     dd($validated); // Debugging: Check if this data is received correctly

    //     $geofence = Geofencing::create($validated);

    //     return response()->json([
    //         'success' => true,
    //         'message' => 'Geofence created successfully',
    //         'geofence_id' => $geofence->id
    //     ]);
    // }


    public function create(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string',
                'latitude' => 'required|numeric',
                'longitude' => 'required|numeric',
                'radius' => 'required|numeric'
            ]);

            // Enable query log
            DB::enableQueryLog();

            // Insert into database
            $inserted = DB::table('geofencing')->insert([
                'name' => $validated['name'],
                'latitude' => $validated['latitude'],
                'longitude' => $validated['longitude'],
                'radius' => $validated['radius'],
                'student_id' => $request->input('student_id'),
                'bus_id' => $request->input('bus_id'),
                'created_at' => now(),
                'updated_at' => now()
            ]);

            // Log query for debugging
            Log::info('Geofence Insert Query:', DB::getQueryLog());

            return response()->json([
                'success' => $inserted,
                'message' => $inserted ? 'Geofence created successfully' : 'Failed to create geofence'
            ]);
        } catch (Exception $e) {
            Log::error('Geofence Insertion Error:', ['error' => $e->getMessage()]);

            return response()->json([
                'success' => false,
                'message' => 'Error creating geofence',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
